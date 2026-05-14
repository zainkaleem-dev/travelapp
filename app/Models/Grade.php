<?php

namespace App\Models;

use App\Models\Concerns\ScopedToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use ScopedToCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'department_id',
        'name',
        'description',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function travelPolicies()
    {
        return $this->belongsToMany(TravelPolicy::class, 'travel_policy_grade')->withTimestamps();
    }
}
