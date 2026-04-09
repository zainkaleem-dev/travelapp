<?php

namespace App\Models;

use App\Models\Concerns\ScopedToSelectedCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCompany extends Model
{
    use ScopedToSelectedCompany;

    protected $fillable = [
        'company_id',
        'company_branch_id',
        'name',
        'code',
        'country',
        'city',
        'address',
        'phone',
        'email',
        'logo_path',
        'is_active',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(CompanyBranch::class, 'company_branch_id');
    }

    /** @return HasMany<SubCompanyBranch, SubCompany> */
    public function branches(): HasMany
    {
        return $this->hasMany(SubCompanyBranch::class);
    }
}
