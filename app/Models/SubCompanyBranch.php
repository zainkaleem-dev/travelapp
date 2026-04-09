<?php

namespace App\Models;

use App\Models\Concerns\ScopedToSelectedCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCompanyBranch extends Model
{
    use ScopedToSelectedCompany;

    protected $fillable = [
        'company_id',
        'company_branch_id',
        'sub_company_id',
        'name',
        'code',
        'country',
        'city',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function companyBranch(): BelongsTo
    {
        return $this->belongsTo(CompanyBranch::class);
    }

    public function subCompany(): BelongsTo
    {
        return $this->belongsTo(SubCompany::class);
    }
}
