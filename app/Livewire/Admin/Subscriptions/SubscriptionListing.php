<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.flight')]
class SubscriptionListing extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public ?string $crudMessage = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteSubscription(int $id): void
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();
        $this->crudMessage = 'Subscription deleted successfully.';
    }

    public function render()
    {
        $subscriptions = Subscription::query()
            ->with('company')
            ->where('plan_name', 'like', '%' . $this->search . '%')
            ->orWhereHas('company', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.subscriptions.subscription-listing', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
