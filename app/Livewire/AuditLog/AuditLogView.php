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
        
        // Prioritize Referer for Livewire requests to get the actual page URL
        if (str_contains($activity['url'] ?? '', '/livewire/')) {
            $referer = request()->headers->get('referer') ?? $activity['input']['_referer'] ?? null;
            if ($referer) return $referer;
        }

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
            'admin' => 'Admin',
            'companies' => 'Organizations',
            'countries-and-cities' => 'Countries & Cities',
            'trip-purpose' => 'Trip Purposes',
            'airports' => 'Airports',
            'create' => 'Add New',
            'edit' => 'Edit',
            'view' => 'View Details',
            'branches' => 'Branches',
            'users' => 'Users',
            'roles' => 'Roles & Permissions',
            'audit-logs' => 'Audit Logs',
            'country' => 'Country',
            'city' => 'City',
            'listing' => 'Organizations'
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

        if (count($formattedSegments) === 1 && $formattedSegments[0] === 'Admin') {
            return 'Dashboard';
        }

        return implode(' -> ', array_unique($formattedSegments));
    }

    public function pageName(): string
    {
        $activity = (array) ($this->activityLog->activity ?? []);
        $rawPage = $this->activityLog->page ?: ($activity['route_name'] ?? '');
        
        // If it's a URL path, try to format it nicely
        if (str_starts_with($rawPage, '/')) {
            $path = $this->breadcrumbPath();
            if ($path !== 'Unknown Path' && $path !== 'Dashboard') {
                $segments = explode(' -> ', $path);
                $last = collect($segments)->last();
                
                // Special case for Add/Edit to include the entity name
                if (($last === 'Add New' || $last === 'Edit') && count($segments) > 1) {
                    $prev = $segments[count($segments) - 2];
                    if ($prev !== 'Admin') {
                        return "{$last} " . str($prev)->singular()->title()->toString();
                    }
                }
                return (string) $last;
            }
            
            // Fallback for paths
            $clean = str($rawPage)->replace(['/admin/', '/'], ' ')->squish()->title()->toString();
            return $clean ?: 'Dashboard';
        }

        $routeMap = [
            'companies.index' => 'Company',
            'companies.create' => 'Add Company',
            'companies.edit' => 'Edit Company',
            'companies.features' => 'Company Features',
            'companies.user-roles' => 'Company Users & Roles',
            'admin.companies' => 'Company',
            'admin.companies.index' => 'Company',
            'admin.dashboard' => 'Dashboard',
            'admin.audit-logs' => 'Audit Logs',
            'admin.audit-logs.view' => 'Audit Log Details',
            'livewire.update' => 'Dashboard', // Should be caught by path logic above if Referer was present
        ];

        if (isset($routeMap[$rawPage])) return $routeMap[$rawPage];

        if ($rawPage) {
            $clean = str($rawPage)->replace(['admin.', 'index', '.', '-', '_', 'livewire', 'update'], ' ')->squish()->title()->toString();
            return $clean ?: 'Dashboard';
        }

        return 'Dashboard';
    }

    public function render()
    {
        return view('livewire.audit-log.view');
    }

    public function beforeState(): array
    {
        return $this->cleanState($this->activityLog->beforeState());
    }

    public function afterState(): array
    {
        return $this->cleanState($this->activityLog->afterState());
    }

    private function cleanState(mixed $state): array
    {
        if (!is_array($state)) return [];

        $ignoredKeys = [
            'id', 'created_at', 'updated_at', 'deleted_at', 
            'password', 'remember_token', 'email_verified_at',
            'components', 'snapshot', 'updates', 'calls'
        ];

        $cleaned = [];
        foreach ($state as $key => $value) {
            if (in_array($key, $ignoredKeys) || is_array($value) || is_object($value)) {
                continue;
            }
            
            $label = str($key)->replace(['_', '-'], ' ')->title()->toString();
            $cleaned[$label] = $value ?? '-';
        }

        return $cleaned;
    }

    public function detailedMessage(): string
    {
        $activity = (array) ($this->activityLog->activity ?? []);
        $user = $this->actorLabel();
        $page = $this->pageName();
        $action = $this->activityLog->action_name ?: $this->inferActionNameFromActivity($activity);

        $before = $this->activityLog->beforeState();
        $after = $this->activityLog->afterState();
        $entityName = $this->inferEntityName($before, $after);
        $entityType = $this->inferEntityType();

        // Check for specific methods in Livewire calls to refine action name
        $input = $activity['input'] ?? null;
        if (is_array($input) && isset($input['components'][0])) {
            $component = $input['components'][0];
            if (!empty($component['calls'][0]['method'])) {
                $methodName = strtolower($component['calls'][0]['method']);
                if (str_contains($methodName, 'delete') || str_contains($methodName, 'destroy') || str_contains($methodName, 'remove')) {
                    $action = 'deleted';
                } elseif (str_contains($methodName, 'save') || str_contains($methodName, 'store')) {
                    $action = 'created';
                } elseif (str_contains($methodName, 'update')) {
                    $action = 'updated';
                } elseif (str_contains($methodName, 'toggle')) {
                    $action = 'toggled';
                }
            }
        }

        $parts = [];
        $actionPast = $this->formatActionPast($action);

        if ($entityName) {
            $parts[] = "{$user} made {$entityType} \"{$entityName}\" on {$page}.";
        } else {
            $parts[] = "{$user} made a {$entityType} record on {$page}.";
        }

        // Add context for updates
        if ($action === 'updated' || $action === 'toggled') {
            if (is_array($input) && isset($input['components'][0])) {
                $component = $input['components'][0];
                if (!empty($component['updates']) && is_array($component['updates'])) {
                    $changes = [];
                    foreach ($component['updates'] as $field => $value) {
                        if (is_array($value) || is_object($value)) continue;
                        $label = str($field)->replace(['_', '-'], ' ')->title()->toString();
                        $changes[] = "set {$label} to \"{$value}\"";
                    }
                    
                    if ($changes !== []) {
                        $parts[] = "Changes included: " . implode(', ', array_slice($changes, 0, 3)) . (count($changes) > 3 ? '...' : '') . ".";
                    }
                }
            }
        }

        return implode(' ', $parts);
    }

    private function formatActionPast(string $action): string
    {
        return match ($action) {
            'viewed' => 'viewed',
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
            'toggled' => 'toggled',
            'performed' => 'performed',
            default => str_ends_with($action, 'ed') ? $action : "{$action}ed",
        };
    }

    private function inferEntityType(): string
    {
        $url = strtolower($this->url() ?? '');
        $activity = (array) ($this->activityLog->activity ?? []);
        $rawPage = strtolower($this->activityLog->page ?: ($activity['route_name'] ?? ''));
        
        if (str_contains($url, 'companies') || str_contains($rawPage, 'companies')) return 'Organization';
        if (str_contains($url, 'branches') || str_contains($rawPage, 'branches')) return 'Branch';
        if (str_contains($url, 'users') || str_contains($rawPage, 'users')) return 'User';
        if (str_contains($url, 'airports') || str_contains($rawPage, 'airports')) return 'Airport';
        if (str_contains($url, 'country') || str_contains($rawPage, 'country')) return 'Country';
        if (str_contains($url, 'city') || str_contains($rawPage, 'city')) return 'City';
        if (str_contains($url, 'trip-purpose') || str_contains($rawPage, 'trip-purpose')) return 'Trip Purpose';
        if (str_contains($url, 'roles') || str_contains($rawPage, 'roles')) return 'Role';
        
        return 'record';
    }

    private function inferEntityName(?array $before, ?array $after): ?string
    {
        $state = $after ?: $before;
        if (!is_array($state)) return null;

        $keys = ['name', 'label', 'title', 'company_name', 'city_name', 'country_name', 'code'];
        foreach ($keys as $key) {
            if (!empty($state[$key]) && is_string($state[$key])) {
                return $state[$key];
            }
        }

        return null;
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
