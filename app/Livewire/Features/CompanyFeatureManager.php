<?php

namespace App\Livewire\Features;

use App\Models\Company;
use Livewire\Component;
use Laravel\Pennant\Feature;
use Illuminate\Support\Collection;

class CompanyFeatureManager extends Component
{
    public Company $company;
    
    /** @var array<string, bool> */
    public array $featureStates = [];

    /** @var array<string, array{label: string, icon: string, description: string}> */
    public array $definedFeatures = [
        'flights-module' => [
            'label' => 'Flight Search',
            'icon' => 'plane',
            'description' => 'Enable flight search and booking capabilities.'
        ],
        'hotels-module' => [
            'label' => 'Hotel Booking',
            'icon' => 'building',
            'description' => 'Enable hotel search and reservation system.'
        ],
        'cars-module' => [
            'label' => 'Car Rental',
            'icon' => 'car',
            'description' => 'Enable car rental booking services.'
        ],
        'concierge-module' => [
            'label' => 'Concierge Services',
            'icon' => 'bell',
            'description' => 'Enable specialized concierge and VIP services.'
        ],
        'travel-hub-module' => [
            'label' => 'Travel Hub',
            'icon' => 'hub',
            'description' => 'Enable the central travel hub for document management and itinerary sync.'
        ],
    ];

    public function mount(Company $company): void
    {
        $this->company = $company;
        
        foreach (array_keys($this->definedFeatures) as $feature) {
            $this->featureStates[$feature] = Feature::for($this->company)->active($feature);
        }
    }

    public function toggleFeature(string $feature): void
    {
        if (!isset($this->featureStates[$feature])) {
            return;
        }

        if ($this->featureStates[$feature]) {
            Feature::for($this->company)->activate($feature);
        } else {
            Feature::for($this->company)->deactivate($feature);
        }

        session()->flash('message', "Feature '{$this->definedFeatures[$feature]['label']}' updated successfully.");
    }

    public function render()
    {
        return view('livewire.features.company-feature-manager')
            ->layout('layouts.app');
    }
}
