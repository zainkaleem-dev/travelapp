<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'registration_number',
        'tax_number',
        'company_type',
        'founded_year',
        'description',
        'status',
        'settings',
        'notes',
    ];

    protected $casts = [
        'settings' => 'array',
        'founded_year' => 'integer',
    ];

    /** @return HasMany<User, Company> */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** @return HasMany<Branch, Company> */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
