<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserFamilyInfo extends Model
{
    use HasFactory;

    protected $table = 'user_family_infos';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'nationality',
        'passport_number',
        'expiry_date',
        'issuing_country',
        'purpose_of_travel',
        'seat_preference',
        'meal_preference',
        'preferred_cabin',
        'preferred_airline',
    ];

    protected $casts = [
        'dob' => 'date',
        'expiry_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
