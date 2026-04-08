<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyBranch extends Model
{
    protected $fillable = [
        'company_id',
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
}

