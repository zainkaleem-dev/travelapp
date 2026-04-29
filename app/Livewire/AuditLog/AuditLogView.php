<?php

namespace App\Livewire\AuditLog;

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

    public function url(): ?string
    {
        $activity = (array) ($this->activityLog->activity ?? []);
        return $activity['full_url'] 
            ?? $activity['url'] 
            ?? ($this->activityLog->page ? url($this->activityLog->page) : null);
    }

    public function breadcrumbPath(): string
    {
        $url = $this->url();
        if (!$url) return 'Unknown Path';

        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        
        $map = [
            'admin' => 'Dashboard',
            'countries-and-cities' => 'Countries & Cities',
            'trip-purpose' => 'Trip Purposes',
            'airports' => 'Airports',
            'create' => 'Add New',
            'edit' => 'Edit',
            'view' => 'View Details',
            'companies' => 'Companies',
            'branches' => 'Branches',
            'users' => 'Users',
            'roles' => 'Roles & Permissions',
            'audit-logs' => 'Audit Logs',
            'country' => 'Country',
            'city' => 'City'
        ];

        $formattedSegments = [];
        foreach ($segments as $segment) {
            if (is_numeric($segment) || $segment === 'livewire' || $segment === 'update') continue;
            
            $lower = strtolower($segment);
            if (isset($map[$lower])) {
                $formattedSegments[] = $map[$lower];
            } else {
                $formattedSegments[] = ucwords(str_replace(['-', '_'], ' ', $segment));
            }
        }

        return implode(' -> ', array_unique($formattedSegments));
    }

    public function render()
    {
        return view('livewire.audit-log.view');
    }

    public function beforeState(): mixed
    {
        return $this->activityLog->beforeState();
    }

    public function afterState(): mixed
    {
        return $this->activityLog->afterState();
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
