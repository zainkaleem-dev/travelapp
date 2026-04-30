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
    public string $search = '';
    public string $actionFilter = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'actionFilter' => ['except' => ''],
        'perPage'      => ['except' => 20],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedActionFilter(): void
    {
        $this->resetPage();
    }

    public function deleteLog(int $logId): void
    {
        ActivityLog::query()->whereKey($logId)->delete();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Display helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Returns a human-readable label for the actor who performed the action.
     */
    public function actorLabel(ActivityLog $log): string
    {
        $activity  = (array) ($log->activity ?? []);
        $actorName = $activity['actor_name'] ?? null;
        $actorRole = $activity['actor_role'] ?? null;

        if ($actorRole === 'Super Admin') {
            return trim(($actorName ?: 'User') . ' (Super Admin)');
        }

        if ($log->relationLoaded('user') && $log->user) {
            return $log->user->name
                ?? $log->user->display_name
                ?? ('User #' . $log->user->id);
        }

        return $actorName ?: 'Unknown User';
    }

    /**
     * Returns a single-line summary for the audit log list view.
     *
     * Format examples:
     *   "Viewed Companies."
     *   "Created organization Acme Corp on Companies."
     *   "Updated Companies: Status from "active" to "inactive"."
     *   "Deleted a record from Trip Purposes."
     */
    public function activityMessage(ActivityLog $log): string
    {
        $activity = (array) ($log->activity ?? []);
        $action   = $log->action_name ?: 'performed';
        $before   = $log->beforeState();
        $after    = $log->afterState();

        $pageLabel  = $this->formatPageLabel($log->page ?: ($activity['route_name'] ?? ''));
        $entityName = $this->inferEntityName($before, $after);

        return match ($action) {
            'viewed'  => "Viewed {$pageLabel}.",

            'created' => $entityName !== null
                ? "Created {$entityName} on {$pageLabel}."
                : "Created a record on {$pageLabel}.",

            'deleted' => $entityName !== null
                ? "Deleted {$entityName} from {$pageLabel}."
                : "Deleted a record from {$pageLabel}.",

            'updated' => $this->buildUpdateMessage($pageLabel, $before, $after),

            default   => ucfirst($action) . " on {$pageLabel}.",
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Builds a concise update message, optionally listing the first few changes.
     */
    private function buildUpdateMessage(string $pageLabel, mixed $before, mixed $after): string
    {
        $changes = $this->summarizeChanges(
            is_array($before) ? $before : [],
            is_array($after)  ? $after  : []
        );

        return $changes !== ''
            ? "Updated {$pageLabel}: {$changes}."
            : "Updated data on {$pageLabel}.";
    }

    /**
     * Converts a raw route name or URL path into a readable label.
     *
     * Route-name examples:
     *   "companies.index"           → "Companies"
     *   "admin.trip-purpose.create" → "Trip Purpose"
     *   "admin.audit-logs"          → "Audit Logs"
     *
     * Path examples:
     *   "/companies/5/edit"         → "Companies"
     *   "/trip-purpose"             → "Trip Purpose"
     */
    private function formatPageLabel(string $page): string
    {
        if ($page === '') {
            return 'Dashboard';
        }

        // ── URL-path branch ───────────────────────────────────────────────────
        if (str_starts_with($page, '/')) {
            // Strip leading slash and split on "/"
            $segments = explode('/', ltrim($page, '/'));

            // Map well-known path segments to friendly names.
            $segmentMap = [
                'companies'           => 'Companies',
                'branches'            => 'Branches',
                'users'               => 'Users',
                'roles-permissions'   => 'Roles & Permissions',
                'audit-logs'          => 'Audit Logs',
                'trip-purpose'        => 'Trip Purposes',
                'airports'            => 'Airports',
                'countries-and-cities'=> 'Countries & Cities',
                'features'            => 'Features',
                'integrations-api'    => 'Integrations',
                'dashboard'           => 'Dashboard',
                'profile'             => 'Profile',
                'settings'            => 'Settings',
            ];

            foreach ($segments as $segment) {
                $lower = strtolower($segment);
                if (isset($segmentMap[$lower])) {
                    return $segmentMap[$lower];
                }
                // Skip numeric IDs and generic admin segment
                if ($lower === 'admin' || is_numeric($segment)) {
                    continue;
                }
                if ($segment !== '') {
                    // Convert slug to title case
                    return str($segment)->replace(['-', '_'], ' ')->title()->toString();
                }
            }

            return 'Dashboard';
        }

        // ── Route-name branch ─────────────────────────────────────────────────
        $routeMap = [
            'companies.index'          => 'Companies',
            'companies.create'         => 'Companies',
            'companies.edit'           => 'Companies',
            'companies.show'           => 'Companies',
            'companies.features'       => 'Features',
            'companies.user-roles'     => 'Users & Roles',
            'companies.branches'       => 'Branches',
            'branches.index'           => 'Branches',
            'branches.create'          => 'Branches',
            'branches.edit'            => 'Branches',
            'users.index'              => 'Users',
            'users.create'             => 'Users',
            'users.edit'               => 'Users',
            'roles.index'              => 'Roles & Permissions',
            'admin.audit-logs'         => 'Audit Logs',
            'admin.audit-logs.view'    => 'Audit Logs',
            'admin.trip-purpose'       => 'Trip Purposes',
            'admin.trip-purpose.create'=> 'Trip Purposes',
            'admin.trip-purpose.view'  => 'Trip Purposes',
            'admin.trip-purpose.edit'  => 'Trip Purposes',
            'admin.countries-and-cities'    => 'Countries & Cities',
            'admin.countries.create'        => 'Countries & Cities',
            'admin.countries.view'          => 'Countries & Cities',
            'admin.countries.edit'          => 'Countries & Cities',
            'admin.cities.create'           => 'Countries & Cities',
            'admin.cities.view'             => 'Countries & Cities',
            'admin.cities.edit'             => 'Countries & Cities',
            'admin.airports'           => 'Airports',
            'admin.airports.create'    => 'Airports',
            'admin.airports.view'      => 'Airports',
            'admin.airports.edit'      => 'Airports',
            'admin.integrations-api'   => 'Integrations',
            'dashboard'                => 'Dashboard',
            'profile'                  => 'Profile',
            'settings'                 => 'Settings',
            'livewire.update'          => 'Dashboard', // fallback; real page from Referer
        ];

        if (isset($routeMap[$page])) {
            return $routeMap[$page];
        }

        // Generic fallback: strip common prefixes and clean up
        return str($page)
            ->replace(['admin.', 'livewire.', 'livewire', 'update', '.index'], ' ')
            ->replace(['.', '-', '_'], ' ')
            ->squish()
            ->title()
            ->toString() ?: 'Dashboard';
    }

    /**
     * Tries to find a human-readable name for the affected entity by checking
     * common "name-like" keys in the before/after state.
     */
    private function inferEntityName(?array $before, ?array $after): ?string
    {
        $state = $after ?: $before;
        if (!is_array($state)) {
            return null;
        }

        foreach (['name', 'label', 'title', 'company_name', 'city_name', 'country_name'] as $key) {
            if (!empty($state[$key]) && is_string($state[$key])) {
                return $state[$key];
            }
        }

        return null;
    }

    /**
     * Generates a short human-readable diff between before and after state.
     * Returns at most 3 changed fields to keep messages scannable.
     */
    private function summarizeChanges(array $before, array $after): string
    {
        $ignoredKeys = [
            'updated_at', 'created_at', 'deleted_at',
            'remember_token', 'password',
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

        $visible = array_slice($changes, 0, 3);
        $suffix  = count($changes) > 3 ? ', and more' : '';

        return implode(', ', $visible) . $suffix;
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

    // ─────────────────────────────────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $query = ActivityLog::query()
            ->with('user')
            ->latest('id');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('page', 'like', '%' . $this->search . '%')
                  ->orWhere('action_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->actionFilter) {
            $query->where('action_name', $this->actionFilter);
        }

        return view('livewire.audit-log.index', [
            'logs' => $query->paginate($this->perPage),
        ]);
    }
}