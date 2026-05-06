<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Models\Company;
use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class SubscriptionEdit extends Component
{
    public Subscription $subscription;
    public int $company_id;
    public string $plan_name;
    public float $price;
    public array $selectedFeatures = [];

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
        $this->company_id = $subscription->company_id;
        $this->plan_name = $subscription->plan_name;
        $this->price = (float) $subscription->price;
        $this->selectedFeatures = $subscription->features;

        // Ensure all defined features are present in the array
        foreach ($this->definedFeatures as $key => $def) {
            if (!isset($this->selectedFeatures[$key])) {
                $this->selectedFeatures[$key] = ($def['type'] === 'toggle') ? false : 0;
            }
        }
    }

    protected function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'plan_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'selectedFeatures' => 'required|array',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->subscription->update([
            'company_id' => $this->company_id,
            'plan_name' => $this->plan_name,
            'price' => $this->price,
            'features' => $this->selectedFeatures,
        ]);

        session()->flash('status', 'Subscription updated successfully.');
        $this->redirect(route('admin.subscriptions.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.subscriptions.subscription-edit', [
            'companies' => Company::orderBy('name')->get(),
        ]);
    }
}
