<?php

namespace App\Models;

use App\Models\Concerns\ScopedToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use ScopedToCompany, SoftDeletes, HasFactory;
 
    protected $table = 'branches';

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'slug',
        'is_main',
        'status',
        'email',
        'phone',
        'phone_secondary',
        'fax',
        'whatsapp',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'settings',
        'notes',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'latitude' => 'decimal:9',
        'longitude' => 'decimal:9',
        'settings' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
