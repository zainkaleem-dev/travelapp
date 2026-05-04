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

    // ─────────────────────────────────────────────────────────────────────────
    // Public computed properties used in the Blade view
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Returns the actor's display label, including role for super-admins.
     */
    public function actorLabel(): string
    {
        $activity  = (array) ($this->activityLog->activity ?? []);
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

    /**
     * Resolves the canonical URL of the page where the action occurred.
     * For Livewire requests the "real" page is the Referer, not the XHR URL.
     */
    public function url(): ?string
    {
        $activity = (array) ($this->activityLog->activity ?? []);

        // Livewire XHR — use the stored referer or the page path
        if (str_contains($activity['url'] ?? '', '/livewire/')) {
            $referer = $activity['input']['_referer'] ?? null;
            if ($referer) {
                return $referer;
            }
            // Fall back to page field if it looks like a real path
            if (!empty($this->activityLog->page) && str_starts_with($this->activityLog->page, '/')) {
                return url($this->activityLog->page);
            }
        }

        return $activity['full_url']
            ?? $activity['url']
            ?? ($this->activityLog->page ? url($this->activityLog->page) : null);
    }

    /**
     * Returns a breadcrumb-style representation of the action page path.
     * Example: "Admin -> Companies -> Edit"
     */
    public function breadcrumbPath(): string
    {
        $url = $this->url();
        if (!$url) {
            return 'Unknown Path';
        }

        $path     = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim((string) $path, '/'));

        $map = [
            'admin'                => 'Admin',
            'companies'            => 'Companies',
            'countries-and-cities' => 'Countries & Cities',
            'trip-purpose'         => 'Trip Purposes',
            'airports'             => 'Airports',
            'create'               => 'Add New',
            'edit'                 => 'Edit',
            'view'                 => 'View Details',
            'branches'             => 'Branches',
            'users'                => 'Users',
            'roles-permissions'    => 'Roles & Permissions',
            'audit-logs'           => 'Audit Logs',
            'country'              => 'Country',
            'city'                 => 'City',
            'listing'              => 'Companies',
            'features'             => 'Features',
            'integrations-api'     => 'Integrations',
            'settings'             => 'Settings',
            'profile'              => 'Profile',
        ];

        $formattedSegments = [];
        foreach ($segments as $segment) {
            // Skip IDs, "livewire", "update", "action"
            if ($segment === '' || is_numeric($segment) || in_array($segment, ['livewire', 'update', 'action'], true)) {
                continue;
            }

            $lower = strtolower($segment);
            $formattedSegments[] = $map[$lower]
                ?? ucwords(str_replace(['-', '_'], ' ', $segment));
        }

        if (count($formattedSegments) === 0 || $formattedSegments === ['Admin']) {
            return 'Dashboard';
        }

        return implode(' → ', array_unique($formattedSegments));
    }

    /**
     * Returns a short friendly name for the page where the action occurred.
     * Used in the "Action Page" field of the detail view.
     */
    public function pageName(): string
    {
        $activity = (array) ($this->activityLog->activity ?? []);
        $rawPage  = $this->activityLog->page ?: ($activity['route_name'] ?? '');

        // ── URL-path branch ───────────────────────────────────────────────────
        if (str_starts_with($rawPage, '/')) {
            $crumb = $this->breadcrumbPath();
            if ($crumb !== 'Unknown Path' && $crumb !== 'Dashboard') {
                $segments = explode(' → ', $crumb);
                $last     = collect($segments)->last();
                $count    = count($segments);

                // "Add New" or "Edit" → prepend the entity type for clarity
                if (in_array($last, ['Add New', 'Edit'], true) && $count > 1) {
                    $entity = $segments[$count - 2];
                    if ($entity !== 'Admin') {
                        return "{$last} " . str($entity)->singular()->title()->toString();
                    }
                }
                return (string) $last;
            }

            $clean = str($rawPage)->replace(['/admin/', '/'], ' ')->squish()->title()->toString();
            return $clean ?: 'Dashboard';
        }

        // ── Route-name branch ─────────────────────────────────────────────────
        $routeMap = [
            'companies.index'           => 'Companies',
            'companies.create'          => 'Add Company',
            'companies.edit'            => 'Edit Company',
            'companies.show'            => 'Company Details',
            'companies.features'        => 'Company Features',
            'companies.user-roles'      => 'Company Users & Roles',
            'companies.branches'        => 'Company Branches',
            'branches.index'            => 'Branches',
            'branches.create'           => 'Add Branch',
            'branches.edit'             => 'Edit Branch',
            'users.index'               => 'Users',
            'users.create'              => 'Add User',
            'users.edit'                => 'Edit User',
            'roles.index'               => 'Roles & Permissions',
            'admin.audit-logs'          => 'Audit Logs',
            'admin.audit-logs.view'     => 'Audit Log Details',
            'admin.trip-purpose'        => 'Trip Purposes',
            'admin.trip-purpose.create' => 'Add Trip Purpose',
            'admin.trip-purpose.view'   => 'Trip Purpose Details',
            'admin.trip-purpose.edit'   => 'Edit Trip Purpose',
            'admin.countries-and-cities'     => 'Countries & Cities',
            'admin.countries.create'         => 'Add Country',
            'admin.countries.view'           => 'Country Details',
            'admin.countries.edit'           => 'Edit Country',
            'admin.cities.create'            => 'Add City',
            'admin.cities.view'              => 'City Details',
            'admin.cities.edit'              => 'Edit City',
            'admin.airports'            => 'Airports',
            'admin.airports.create'     => 'Add Airport',
            'admin.airports.view'       => 'Airport Details',
            'admin.airports.edit'       => 'Edit Airport',
            'admin.integrations-api'    => 'Integrations',
            'dashboard'                 => 'Dashboard',
            'profile'                   => 'Profile',
            'settings'                  => 'Settings',
            'livewire.update'           => 'Action',
        ];

        if (isset($routeMap[$rawPage])) {
            return $routeMap[$rawPage];
        }

        if ($rawPage !== '') {
            $clean = str($rawPage)
                ->replace(['admin.', 'livewire.', 'livewire', 'update', '.index'], ' ')
                ->replace(['.', '-', '_'], ' ')
                ->squish()
                ->title()
                ->toString();
            return $clean ?: 'Action';
        }

        return 'Dashboard';
    }

    /**
     * Returns the cleaned before-state for display, excluding noisy/irrelevant keys.
     */
    public function beforeState(): array
    {
        return $this->cleanState($this->activityLog->beforeState());
    }

    /**
     * Returns the cleaned after-state for display, excluding noisy/irrelevant keys.
     */
    public function afterState(): array
    {
        return $this->cleanState($this->activityLog->afterState());
    }

    /**
     * Builds a detailed, prose-style description of what happened.
     *
     * Example outputs:
     *   "John Doe (Super Admin) created organization "Acme Corp" on Companies at 2025-01-15 10:30:00."
     *   "Jane Smith updated a Branch record on Branches. Changes: Status from "active" to "inactive"."
     *   "John Doe deleted organization "Old Corp" from Companies at 2025-01-15 09:00:00."
     */
    public function detailedMessage(): string
    {
        $activity    = (array) ($this->activityLog->activity ?? []);
        $user        = $this->actorLabel();
        $page        = $this->pageName();
        $timestamp   = $this->activityLog->created_at?->format('Y-m-d H:i:s') ?? '';
        $action      = $this->resolveDisplayAction($activity);

        $before      = $this->activityLog->beforeState();
        $after       = $this->activityLog->afterState();
        $entityName  = $this->inferEntityName($before, $after);
        $entityType  = $this->inferEntityType();

        // ── Build message ─────────────────────────────────────────────────────
        $entityLabel = $entityName !== null
            ? "{$entityType} \"{$entityName}\""
            : "a {$entityType} record";

        $parts = match ($action) {
            'created' => [
                "{$user} created {$entityLabel} on {$page} at {$timestamp}.",
                !empty($after) ? "Initial state: " . $this->diffChanges([], $after) . "." : "",
            ],
            'deleted' => [
                "{$user} deleted {$entityLabel} from {$page} at {$timestamp}.",
                !empty($before) ? "Last known state: " . $this->diffChanges($before, []) . "." : "",
            ],
            'viewed' => [
                "{$user} viewed {$page} at {$timestamp}.",
            ],
            'updated', 'toggled' => $this->buildUpdateParts($user, $entityLabel, $page, $timestamp, $activity),
            default => $this->buildGenericParts($user, $page, $timestamp, $activity, $action),
        };

        return implode(' ', $parts);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Resolves the display action, checking Livewire call methods for
     * more precise signals when the stored action_name is generic.
     */
    private function resolveDisplayAction(array $activity): string
    {
        $action = $this->activityLog->action_name ?: 'performed';

        // Inspect Livewire call methods for a more accurate label
        $calls = $activity['input']['components'][0]['calls'] ?? [];
        if (is_array($calls) && !empty($calls)) {
            $method = strtolower((string) ($calls[0]['method'] ?? ''));
            if (str_contains($method, 'delete') || str_contains($method, 'destroy') || str_contains($method, 'remove')) {
                return 'deleted';
            }
            if (str_contains($method, 'save') || str_contains($method, 'store') || str_contains($method, 'create')) {
                return 'created';
            }
            if (str_contains($method, 'update') || str_contains($method, 'edit')) {
                return 'updated';
            }
            if (str_contains($method, 'toggle')) {
                return 'toggled';
            }
        }

        return $action;
    }

    /**
     * Builds generic message parts, attempting to include the method name.
     */
    private function buildGenericParts(string $user, string $page, string $timestamp, array $activity, string $action): array
    {
        $method = null;
        $calls  = $activity['input']['components'][0]['calls'] ?? [];
        if (is_array($calls) && !empty($calls)) {
            $method = $calls[0]['method'] ?? null;
        }

        if ($method) {
            $friendlyMethod = str($method)->replace(['-', '_'], ' ')->title()->toString();
            return ["{$user} performed action \"{$friendlyMethod}\" on {$page} at {$timestamp}."];
        }

        return ["{$user} performed a " . strtolower($action) . " action on {$page} at {$timestamp}."];
    }

    /**
     * Builds the message parts for an update/toggle action.
     */
    private function buildUpdateParts(
        string $user,
        string $entityLabel,
        string $page,
        string $timestamp,
        array $activity
    ): array {
        $parts = ["{$user} updated {$entityLabel} on {$page} at {$timestamp}."];

        // Prefer explicit Livewire updates for change detail
        $updates = $activity['input']['components'][0]['updates'] ?? [];
        if (is_array($updates) && !empty($updates)) {
            $changes = [];
            foreach ($updates as $field => $value) {
                if (is_array($value) || is_object($value)) {
                    continue;
                }
                $label     = str($field)->replace(['_', '-'], ' ')->title()->toString();
                $changes[] = "set {$label} to \"{$value}\"";
            }

            if (!empty($changes)) {
                $parts[] = 'Changes: ' . implode(', ', $changes) . '.';
            }
            return $parts;
        }

        // Fall back to before/after diff
        $before = $this->activityLog->beforeState();
        $after  = $this->activityLog->afterState();
        if (is_array($before) && is_array($after)) {
            $diff = $this->diffChanges($before, $after);
            if ($diff !== '') {
                $parts[] = "Changes: {$diff}.";
            }
        }

        return $parts;
    }

    /**
     * Produces a human-readable diff string between two state arrays.
     */
    private function diffChanges(array $before, array $after): string
    {
        $ignoredKeys = ['updated_at', 'created_at', 'deleted_at', 'remember_token', 'password', 'id'];
        $changes     = [];
        $source      = !empty($after) ? $after : $before;

        foreach ($source as $key => $value) {
            if (in_array($key, $ignoredKeys, true)) {
                continue;
            }

            $beforeValue = $before[$key] ?? null;
            $afterValue  = $after[$key] ?? null;

            if ($beforeValue === $afterValue && !empty($before) && !empty($after)) {
                continue;
            }

            $label = str($key)->replace('_', ' ')->title()->toString();

            if (empty($before)) {
                $changes[] = "{$label}: \"{$this->stringifyValue($afterValue)}\"";
            } elseif (empty($after)) {
                $changes[] = "{$label}: \"{$this->stringifyValue($beforeValue)}\"";
            } else {
                $changes[] = "{$label} from \"{$this->stringifyValue($beforeValue)}\" to \"{$this->stringifyValue($afterValue)}\"";
            }
        }

        return implode(', ', $changes);
    }

    /**
     * Strips noise (system fields, complex values) from a state array for display.
     */
    private function cleanState(mixed $state): array
    {
        if (!is_array($state)) {
            return [];
        }

        $ignoredKeys = [
            'id', 'created_at', 'updated_at', 'deleted_at',
            'password', 'remember_token', 'email_verified_at',
            'components', 'snapshot', 'updates', 'calls',
        ];

        $cleaned = [];
        foreach ($state as $key => $value) {
            if (in_array($key, $ignoredKeys, true) || is_array($value) || is_object($value)) {
                continue;
            }
            $label           = str($key)->replace(['_', '-'], ' ')->title()->toString();
            $cleaned[$label] = $value ?? '-';
        }

        return $cleaned;
    }

    /**
     * Infers a human-friendly entity type from the action page URL / route.
     */
    private function inferEntityType(): string
    {
        $url      = strtolower((string) ($this->url() ?? ''));
        $activity = (array) ($this->activityLog->activity ?? []);
        $rawPage  = strtolower($this->activityLog->page ?: ($activity['route_name'] ?? ''));

        $checks = [
            'companies'            => 'Organization',
            'branches'             => 'Branch',
            'users'                => 'User',
            'airports'             => 'Airport',
            'trip-purpose'         => 'Trip Purpose',
            'countries-and-cities' => 'Country / City',
            'country'              => 'Country',
            'city'                 => 'City',
            'roles'                => 'Role',
            'features'             => 'Feature',
            'integrations'         => 'Integration',
        ];

        foreach ($checks as $keyword => $label) {
            if (str_contains($url, $keyword) || str_contains($rawPage, $keyword)) {
                return $label;
            }
        }

        return 'record';
    }

    /**
     * Tries to find a human-readable entity name from the before/after state.
     */
    private function inferEntityName(?array $before, ?array $after): ?string
    {
        $state = $after ?: $before;
        if (!is_array($state)) {
            return null;
        }

        foreach (['name', 'label', 'title', 'company_name', 'city_name', 'country_name', 'code'] as $key) {
            if (!empty($state[$key]) && is_string($state[$key])) {
                return $state[$key];
            }
        }

        return null;
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
        return view('livewire.audit-log.view');
    }
}