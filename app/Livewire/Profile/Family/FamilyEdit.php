<?php

namespace App\Livewire\Profile\Family;

use App\Livewire\Concerns\HasPersonalInfoFormFields;
use App\Models\UserFamilyInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class FamilyEdit extends Component
{
    use HasPersonalInfoFormFields;

    public int $id;

    public function mount(int $id): void
    {
        $this->id = $id;

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $member = UserFamilyInfo::query()
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $this->first_name = $member->first_name;
        $this->last_name = $member->last_name;
        $this->email = $member->email ?? '';
        $this->phone = $member->phone;
        $this->dob = $member->dob?->format('Y-m-d');
        $this->gender = $member->gender;
        $this->nationality = $member->nationality;

        $this->passport_number = $member->passport_number;
        $this->expiry_date = $member->expiry_date?->format('Y-m-d');
        $this->issuing_country = $member->issuing_country;

        $this->purpose_of_travel = $member->purpose_of_travel;
        $this->seat_preference = $member->seat_preference;
        $this->meal_preference = $member->meal_preference;
        $this->preferred_cabin = $member->preferred_cabin;
        $this->preferred_airline = $member->preferred_airline;
    }

    protected function rules(): array
    {
        return $this->personalInfoFormRules();
    }

    public function updateFamilyMember(): void
    {
        $this->validate();

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $member = UserFamilyInfo::query()
            ->where('user_id', $user->id)
            ->findOrFail($this->id);

        $member->fill($this->personalInfoFormToArray());
        $member->save();

        session()->flash('status', 'Family traveler updated successfully.');
        $this->redirect(route('profile', ['tab' => 'family']), navigate: true);
    }

    public function render()
    {
        return view('livewire.profile.family.edit');
    }
}
