<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class AuditLogView extends Component
{
    public ActivityLog $activityLog;

    public function mount(ActivityLog $activityLog): void
    {
        $this->activityLog = $activityLog->load('user');
    }

    public function actorLabel(): string
    {
        $activity = (array) ($this->activityLog->activity ?? []);
        $actorName = $activity['actor_name'] ?? null;
        $actorRole = $activity['actor_role'] ?? null;

        if ($actorRole === 'Super Admin') {
            return trim(($actorName ?: 'User') . ' (Super Admin)');
        }

        if ($this->activityLog->user) {
            return $this->activityLog->user->name
                ?? $this->activityLog->user->display_name
                ?? ('User #' . $this->activityLog->user->id);
        }

        return $actorName ?: 'Unknown User';
    }

    public function render()
    {
        return view('livewire.admin.audit-log-view');
    }

    public function beforeState(): mixed
    {
        if ($this->activityLog->before_state !== null) {
            return $this->activityLog->before_state;
        }

        $activity = (array) ($this->activityLog->activity ?? []);

        $nested = $this->extractLivewireSnapshotData($activity);
        if ($nested !== null) {
            return $nested;
        }

        return $activity['before_state']
            ?? $activity['previous_state']
            ?? null;
    }

    public function afterState(): mixed
    {
        if ($this->activityLog->after_state !== null) {
            return $this->activityLog->after_state;
        }

        $activity = (array) ($this->activityLog->activity ?? []);

        $livewireAfter = $this->buildLivewireAfterState($activity);
        if ($livewireAfter !== null) {
            return $livewireAfter;
        }

        return $activity['after_state']
            ?? $activity['new_state']
            ?? null;
    }

    public function detailedMessage(): string
    {
        $activity = (array) ($this->activityLog->activity ?? []);
        $user = $this->actorLabel();
        $page = $this->activityLog->page ?: ($activity['route_name'] ?? 'Unknown Page');
        $action = $this->activityLog->action_name ?: $this->inferActionNameFromActivity($activity);

        $parts = [];
        $parts[] = "{$user} {$action} on {$page}.";

        $input = $activity['input'] ?? null;
        if (is_array($input) && isset($input['components'][0])) {
            $component = $input['components'][0];
            $componentName = $this->extractComponentName($component);
            if ($componentName) {
                $parts[] = "Component: {$componentName}.";
            }

            if (!empty($component['calls'][0]['method'])) {
                $parts[] = "Triggered method: {$component['calls'][0]['method']}.";
            }

            if (!empty($component['updates']) && is_array($component['updates'])) {
                $updatedFields = implode(', ', array_keys($component['updates']));
                $parts[] = "Updated fields: {$updatedFields}.";
            }
        }

        return implode(' ', $parts);
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

    private function extractComponentName(array $component): ?string
    {
        $snapshotRaw = $component['snapshot'] ?? null;
        if (!is_string($snapshotRaw) || $snapshotRaw === '') {
            return null;
        }

        $snapshot = json_decode($snapshotRaw, true);
        if (!is_array($snapshot)) {
            return null;
        }

        return $snapshot['memo']['name'] ?? null;
    }

    private function inferActionNameFromActivity(array $activity): string
    {
        $method = strtoupper((string) ($activity['method'] ?? ''));

        return match ($method) {
            'GET' => 'viewed',
            'POST' => 'submitted changes',
            'PUT', 'PATCH' => 'updated data',
            'DELETE' => 'deleted data',
            default => 'performed an action',
        };
    }
}

