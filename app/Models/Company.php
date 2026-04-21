<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'legal_name',
        'registration_number',
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


    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company, Company> */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<Company, Company> */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    /** @return HasMany<Branch, Company> */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /** @return MorphMany<Attachment, Company> */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Recursively fetch all IDs of descendant companies.
     * 
     * @return array<int>
     */
    public function getAllDescendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }

        return array_unique($ids);
    }
}
