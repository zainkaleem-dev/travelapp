<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'iata_code',
        'icao_code',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
