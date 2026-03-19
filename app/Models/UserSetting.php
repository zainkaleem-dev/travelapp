<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSetting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    /** DB keys → labels (Settings dropdown + flight search) */
    public const TRIP_TYPE_LABELS = [
        'business_trip' => 'Business trip',
        'personal_trip' => 'Personal trip',
        'annual_trip' => 'Annual trip',
        'guest' => 'Guest',
    ];

    protected $fillable = [
        'user_id',
        'trip_type',
    ];

    public static function tripTypeLabel(?string $tripType): ?string
    {
        if ($tripType === null || $tripType === '') {
            return null;
        }

        return self::TRIP_TYPE_LABELS[$tripType] ?? null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
