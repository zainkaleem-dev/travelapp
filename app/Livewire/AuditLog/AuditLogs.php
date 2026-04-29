<?php

namespace App\Livewire\AuditLog;

use App\Models\ActivityLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.flight')]
class AuditLogs extends Component
{
    use WithPagination;

    public int $perPage = 20;
    public bool $selectionMode = false;
    public array $selectedLogIds = [];

    public function deleteLog(int $logId): void
    {
        ActivityLog::query()->whereKey($logId)->delete();
    }

    public function startBulkDeleteMode(int $logId): void
    {
        $this->selectionMode = true;

        if (!in_array($logId, $this->selectedLogIds, true)) {
            $this->selectedLogIds[] = $logId;
        }
    }

    public function toggleSelected(int $logId): void
    {
        if (in_array($logId, $this->selectedLogIds, true)) {
            $this->selectedLogIds = array_values(array_filter(
                $this->selectedLogIds,
                fn ($id) => (int) $id !== $logId
            ));

            return;
        }

        $this->selectedLogIds[] = $logId;
    }

    public function selectAllVisible(): void
    {
        $ids = ActivityLog::query()
            ->latest('id')
            ->forPage($this->getPage(), $this->perPage)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $this->selectedLogIds = $ids;
    }

    public function clearSelection(): void
    {
        $this->selectedLogIds = [];
        $this->selectionMode = false;
    }

    public function deleteSelectedLogs(): void
    {
        if ($this->selectedLogIds === []) {
            return;
        }

        ActivityLog::query()
            ->whereIn('id', $this->selectedLogIds)
            ->delete();

        $this->clearSelection();
    }

    public function actorLabel(ActivityLog $log): string
    {
        $activity = (array) ($log->activity ?? []);
        $actorName = $activity['actor_name'] ?? null;
        $actorRole = $activity['actor_role'] ?? null;

        if ($actorRole === 'Super Admin') {
            return trim(($actorName ?: 'User') . ' (Super Admin)');
        }

        if ($log->relationLoaded('user') && $log->user) {
            return $log->user->name ?? $log->user->display_name ?? ('User #' . $log->user->id);
        }

        if ($actorName) {
            return $actorName;
        }

        return 'Unknown User';
    }

    public function activityMessage(ActivityLog $log): string
    {
        $page = $log->page ?: ((array) ($log->activity ?? []))['route_name'] ?? 'unknown page';
        $action = $log->action_name ?: 'performed';
        $before = $log->before_state;
        $after = $log->after_state;

        $entityName = $this->inferEntityName($before, $after);
        $pageLabel = $this->formatPageLabel($page);
        $changes = $this->summarizeChanges($before, $after);

        if ($action === 'viewed') {
            return "Viewed {$pageLabel}.";
        }

        if ($action === 'created') {
            if ($entityName !== null) {
                return "Created {$entityName} on {$pageLabel}.";
            }

            return "Created a record on {$pageLabel}.";
        }

        if ($action === 'deleted') {
            if ($entityName !== null) {
                return "Deleted {$entityName} from {$pageLabel}.";
            }

            return "Deleted a record from {$pageLabel}.";
        }

        if ($action === 'updated') {
            if ($changes !== '') {
                return "Updated {$pageLabel}: {$changes}.";
            }

            return "Updated data on {$pageLabel}.";
        }

        if ($changes !== '') {
            return "Performed an action on {$pageLabel}: {$changes}.";
        }

        return "Performed an action on {$pageLabel}.";
    }

    private function formatPageLabel(string $page): string
    {
        return str($page)
            ->replace(['.', '-', '_'], ' ')
            ->squish()
            ->title()
            ->toString();
    }

    private function inferEntityName(?array $before, ?array $after): ?string
    {
        $state = $after ?: $before;
        if (!is_array($state)) {
            return null;
        }

        foreach (['name', 'label', 'title', 'company_name'] as $key) {
            if (!empty($state[$key]) && is_string($state[$key])) {
                return $state[$key];
            }
        }

        return null;
    }

    private function summarizeChanges(?array $before, ?array $after): string
    {
        if (!is_array($before) || !is_array($after)) {
            return '';
        }

        $ignoredKeys = [
            'updated_at',
            'created_at',
            'deleted_at',
            'remember_token',
            'password',
        ];

        $changes = [];
        foreach ($after as $key => $afterValue) {
            if (in_array($key, $ignoredKeys, true)) {
                continue;
            }

            $beforeValue = $before[$key] ?? null;
            if ($beforeValue === $afterValue) {
                continue;
            }

            $changes[] = sprintf(
                '%s from "%s" to "%s"',
                str($key)->replace('_', ' ')->title()->toString(),
                $this->stringifyValue($beforeValue),
                $this->stringifyValue($afterValue)
            );
        }

        return implode(', ', array_slice($changes, 0, 3));
    }

    private function stringifyValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '[complex]';
    }

    public function render()
    {
        return view('livewire.audit-log.index', [
            'logs' => ActivityLog::query()->with('user')->latest('id')->paginate($this->perPage),
        ]);
    }
}

