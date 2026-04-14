<?php

namespace App\Models; 
 
use App\Models\Branch;
  use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable; 
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\HasOne; 
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
 
class User extends Authenticatable implements MustVerifyEmail 
{ 
    use HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 
        'company_id', 
        'branch_id',
          'first_name', 
        'middle_name', 
        'last_name', 
        'email', 
        'password', 
    ];

    public function getDisplayNameAttribute(): string
    {
        $first = trim((string) ($this->first_name ?? ''));
        $middle = trim((string) ($this->middle_name ?? ''));
        $last = trim((string) ($this->last_name ?? ''));

        return trim(implode(' ', array_values(array_filter([$first, $middle, $last], fn ($v) => $v !== ''))));
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

    public function company(): BelongsTo 
    { 
        return $this->belongsTo(Company::class); 
    } 

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
 
  }
