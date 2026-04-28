<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class UserSetting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'trip_type',
    ];

    public static function tripTypeOptions(): array
    {
        if (!Schema::hasTable('trip_purposes')) {
            return [
                'business_trip' => 'Business trip',
                'personal_trip' => 'Personal trip',
                'annual_trip' => 'Annual trip',
                'guest' => 'Guest',
            ];
        }

        $options = TripPurpose::query()
            ->orderBy('id')
            ->pluck('label', 'key')
            ->toArray();

        // Safe fallback for environments where migration has not run yet.
        if ($options === []) {
            return [
                'business_trip' => 'Business trip',
                'personal_trip' => 'Personal trip',
                'annual_trip' => 'Annual trip',
                'guest' => 'Guest',
            ];
        }

        return $options;
    }

    public static function tripTypeLabel(?string $tripType): ?string
    {
        if ($tripType === null || $tripType === '') {
            return null;
        }

        $options = self::tripTypeOptions();

        return $options[$tripType] ?? null;
    }

}
