<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Concerns\ScopedToBranch;
use App\Models\Concerns\ScopedToCompany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, ScopedToCompany, ScopedToBranch {
        hasRole as protected traitHasRole;
        hasPermissionTo as protected traitHasPermissionTo;
    }

    /**
     * Override Spatie's hasRole to strictly enforce role status (Active/Inactive).
     */
    public function hasRole($roles, string $guard = null): bool
    {
        // 1. Get all roles currently assigned to the user that are ACTIVE (status = 1)
        // We check both the current team context and the global context (null)
        $originalTeamId = getPermissionsTeamId();

        try {
            $hasActiveRole = function ($roleNames, $teamId) use ($guard) {
                // Normalize $roleNames to an array of strings if it's a collection or array of objects
                $names = [];
                if (is_iterable($roleNames)) {
                    foreach ($roleNames as $role) {
                        $names[] = is_string($role) ? $role : (is_numeric($role) ? $role : $role->name);
                    }
                } else {
                    $names = [is_string($roleNames) ? $roleNames : (is_numeric($roleNames) ? $roleNames : $roleNames->name)];
                }

                setPermissionsTeamId($teamId);
                return $this->roles()
                    ->where('roles.status', 1)
                    ->whereIn('roles.name', $names)
                    ->exists();
            };

            // Check in current context
            if ($hasActiveRole($roles, $originalTeamId)) {
                return true;
            }

            // Check in global context (Only for Super Admin bypass)
            if ($originalTeamId !== null && $hasActiveRole(['Super Admin'], null)) {
                return true;
            }

            return false;
        } finally {
            // ALWAYS restore the original team context to avoid side-effects in other parts of the app
            setPermissionsTeamId($originalTeamId);
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'company_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'status',
        'has_set_password',
    ];

    public function getDisplayNameAttribute(): string
    {
        $first = trim((string) ($this->first_name ?? ''));
        $middle = trim((string) ($this->middle_name ?? ''));
        $last = trim((string) ($this->last_name ?? ''));

        return trim(implode(' ', array_values(array_filter([$first, $middle, $last], fn($v) => $v !== ''))));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_set_password' => 'boolean',
        ];
    }

    public function personalInfo(): HasOne
    {
        return $this->hasOne(UserPersonalInfo::class);
    }

    /** @return HasMany<UserFamilyInfo, User> */
    public function familyInfos(): HasMany
    {
        return $this->hasMany(UserFamilyInfo::class);
    }

    public function userSetting(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Override Spatie's hasPermissionTo to ensure strict multi-tenant isolation.
     * This prevents "bleeding" from global role templates (company_id = null)
     * by forcing the check to only consider the current company context.
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        // 1. Super Admin bypass (Global Master Key)
        // Check cache first for performance
        $userId = $this->id;
        $isGlobalSuperAdmin = \Illuminate\Support\Facades\Cache::remember(
            "user_{$userId}_is_global_super_admin",
            now()->addMinutes(10),
            fn() => \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $userId)
                ->where('model_has_roles.model_type', self::class)
                ->where('roles.name', 'Super Admin')
                ->whereNull('model_has_roles.company_id')
                ->exists()
        );

        if ($isGlobalSuperAdmin) {
            return true;
        }

        // 2. Enforce Active Context (Multi-Tenant Awareness)
        // Use the active company context from TenantContext if set, fallback to user's home company
        $contextCompanyId = app(\App\Support\TenantContext::class)->companyId() ?? $this->company_id;

        // Ensure Spatie Registrar respects the current context
        setPermissionsTeamId($contextCompanyId);

        // Standard Spatie check, but now guarded by our context-aware logic
        return $this->traitHasPermissionTo($permission, $guardName);
    }
}
