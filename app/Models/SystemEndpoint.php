<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemEndpoint extends Model
{
    protected $fillable = [
        'company_id',
        'endpoint_name',
        'endpoint_link',
        'description',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Get the company that owns the endpoint.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
