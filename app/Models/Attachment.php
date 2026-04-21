<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
        'uploaded_by' => 'integer',
    ];

    /** @return MorphTo<Model, Attachment> */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}

