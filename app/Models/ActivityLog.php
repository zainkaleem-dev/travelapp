<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'user_id',
        'activity',
        'page',
        'action_name',
    ];

    protected $casts = [
        'activity' => 'array',
    ];

    public function beforeState(): mixed
    {
        if ($this->action_name === 'created') {
            return null;
        }

        $activity = (array) ($this->activity ?? []);

        // Prefer explicitly stored before_state
        if (array_key_exists('before_state', $activity)) {
            return $activity['before_state'];
        }

        // Try to extract from nested Livewire snapshot if available (legacy or direct Livewire logs)
        $nested = $this->extractLivewireSnapshotData($activity);
        if ($nested !== null) {
            return $nested;
        }

        return $activity['previous_state'] ?? null;
    }

    public function afterState(): mixed
    {
        if ($this->action_name === 'deleted') {
            return null;
        }

        $activity = (array) ($this->activity ?? []);

        // Prefer explicitly stored after_state
        if (array_key_exists('after_state', $activity)) {
            return $activity['after_state'];
        }

        // Try to build after state from Livewire updates
        $livewireAfter = $this->buildLivewireAfterState($activity);
        if ($livewireAfter !== null) {
            return $livewireAfter;
        }

        return $activity['new_state'] ?? null;
    }

    private function extractLivewireSnapshotData(array $activity): ?array
    {
        $component = $activity['input']['components'][0] ?? null;
        if (!is_array($component)) {
            return null;
        }

        $snapshotRaw = $component['snapshot'] ?? null;
        if (!is_string($snapshotRaw) || $snapshotRaw === '') {
            return null;
        }

        $snapshot = json_decode($snapshotRaw, true);
        if (!is_array($snapshot)) {
            return null;
        }

        return isset($snapshot['data']) && is_array($snapshot['data'])
            ? $snapshot['data']
            : null;
    }

    private function buildLivewireAfterState(array $activity): ?array
    {
        $before = $this->extractLivewireSnapshotData($activity);
        if (!is_array($before)) {
            return null;
        }

        $component = $activity['input']['components'][0] ?? null;
        if (!is_array($component)) {
            return null;
        }

        $updates = $component['updates'] ?? null;
        if (!is_array($updates) || $updates === []) {
            return $before;
        }

        $after = $before;
        foreach ($updates as $field => $value) {
            $after[$field] = $value;
        }

        return $after;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

