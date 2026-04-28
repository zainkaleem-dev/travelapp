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

        return $activity['after_state']
            ?? $activity['new_state']
            ?? null;
    }
}

