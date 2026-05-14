<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'policy_type',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'travel_policy_grade')->withTimestamps();
    }
}
