<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class SubscriptionView extends Component
{
    public Subscription $subscription;

    public array $definedFeatures = [
        'flights-module' => ['label' => 'Flights', 'type' => 'toggle'],
        'hotels-module' => ['label' => 'Hotels', 'type' => 'toggle'],
        'cars-module' => ['label' => 'Cars', 'type' => 'toggle'],
        'concierge-module' => ['label' => 'Concierge', 'type' => 'toggle'],
        'travel-hub-module' => ['label' => 'Travel Hub', 'type' => 'toggle'],
        'companies-module' => ['label' => 'Organizations', 'type' => 'toggle'],
        'branches-module' => ['label' => 'Branches', 'type' => 'toggle'],
        'users-module' => ['label' => 'Users', 'type' => 'toggle'],
        'roles-permissions-module' => ['label' => 'Roles & Permissions', 'type' => 'toggle'],
        'feature-management-module' => ['label' => 'Feature Management', 'type' => 'toggle'],
        'companies-quantity' => ['label' => 'Max Organizations', 'type' => 'quantity'],
        'branches-quantity' => ['label' => 'Max Branches', 'type' => 'quantity'],
        'users-quantity' => ['label' => 'Max Users', 'type' => 'quantity'],
    ];

    public function mount(Subscription $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function render()
    {
        return view('livewire.admin.subscriptions.subscription-view');
    }
}
