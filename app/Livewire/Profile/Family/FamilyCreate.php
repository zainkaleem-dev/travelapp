<?php

namespace App\Livewire\Profile\Family;

use App\Livewire\Concerns\HasPersonalInfoFormFields;
use App\Models\UserFamilyInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class FamilyCreate extends Component
{
    use HasPersonalInfoFormFields;

    protected function rules(): array
    {
        return $this->personalInfoFormRules();
    }

    public function saveFamilyMember(): void
    {
        $this->validate();

        $user = Auth::user();
        if (!$user) {
            return;
        }

        UserFamilyInfo::query()->create(array_merge(
            ['user_id' => $user->id],
            $this->personalInfoFormToArray()
        ));

        session()->flash('status', 'Family traveler saved successfully.');
        $this->redirect(route('profile', ['tab' => 'family']), navigate: true);
    }

    public function render()
    {
        return view('livewire.profile.family.create');
    }
}
