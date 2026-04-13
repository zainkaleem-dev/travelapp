<?php

namespace App\Models;

use App\Models\Concerns\ScopedToSelectedCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBranch extends Model
{
    use ScopedToSelectedCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'slug',
        'is_main',
        'status',
        'email',
        'phone',
        'phone_secondary',
        'fax',
        'whatsapp',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'settings',
        'notes',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'settings' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return HasMany<SubCompany, CompanyBranch> */
    public function subCompanies(): HasMany
    {
        return $this->hasMany(SubCompany::class, 'company_branch_id');
    }
}
