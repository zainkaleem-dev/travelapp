<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'type',
        'logo_path',
        'is_active',
        'email',
        'phone',
        'country',
        'subscription_plan',
        'company_limit',
    ];

    /** @return HasMany<User, Company> */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** @return HasMany<CompanyBranch, Company> */
    public function branches(): HasMany
    {
        return $this->hasMany(CompanyBranch::class);
    }
}
