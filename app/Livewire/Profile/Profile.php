<?php

namespace App\Livewire\Profile;

use App\Livewire\Concerns\HasPersonalInfoFormFields;
use App\Models\UserFamilyInfo;
use App\Models\UserPersonalInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Profile extends Component
{
    use HasPersonalInfoFormFields;

    public ?string $saveSuccess = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $this->email = $user->email ?? '';

        // Split full name into first/last (best-effort).
        $name = trim((string) ($user->name ?? ''));
        if ($name !== '') {
            $parts = preg_split('/\s+/', $name);
            $this->first_name = $parts[0] ?? null;
            $this->last_name = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : null;
        }

        $pi = UserPersonalInfo::query()->where('user_id', $user->id)->first();
        if ($pi) {
            $this->first_name = $pi->first_name ?? $this->first_name;
            $this->last_name = $pi->last_name ?? $this->last_name;
            $this->email = $pi->email ?? $this->email;
            $this->phone = $pi->phone;
            $this->dob = $pi->dob?->format('Y-m-d') ?? $this->dob;
            $this->gender = $pi->gender;
            $this->nationality = $pi->nationality;

            $this->passport_number = $pi->passport_number;
            $this->expiry_date = $pi->expiry_date?->format('Y-m-d') ?? $this->expiry_date;
            $this->issuing_country = $pi->issuing_country;

            $this->purpose_of_travel = $pi->purpose_of_travel;
            $this->seat_preference = $pi->seat_preference;
            $this->meal_preference = $pi->meal_preference;
            $this->preferred_cabin = $pi->preferred_cabin;
            $this->preferred_airline = $pi->preferred_airline;
        }
    }

    protected function rules(): array
    {
        return $this->personalInfoFormRules();
    }

    public function savePersonalInfo(): void
    {
        $this->saveSuccess = null;
        $this->validate();

        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Update users table (name/email) as requested.
        $fullName = trim(sprintf('%s %s', (string) ($this->first_name ?? ''), (string) ($this->last_name ?? '')));
        $user->name = $fullName !== '' ? $fullName : ($user->name ?? '');
        $user->email = $this->email;
        $user->save();

        // Update personal info table.
        $pi = UserPersonalInfo::query()->firstOrNew(['user_id' => $user->id]);
        $attributes = $this->personalInfoFormToArray();
        $pi->email = $attributes['email'];
        $pi->first_name = $attributes['first_name'];
        $pi->last_name = $attributes['last_name'];
        $pi->phone = $attributes['phone'];
        $pi->dob = $attributes['dob'];
        $pi->gender = $attributes['gender'];
        $pi->nationality = $attributes['nationality'];
        $pi->passport_number = $attributes['passport_number'];
        $pi->expiry_date = $attributes['expiry_date'];
        $pi->issuing_country = $attributes['issuing_country'];
        $pi->purpose_of_travel = $attributes['purpose_of_travel'];
        $pi->seat_preference = $attributes['seat_preference'];
        $pi->meal_preference = $attributes['meal_preference'];
        $pi->preferred_cabin = $attributes['preferred_cabin'];
        $pi->preferred_airline = $attributes['preferred_airline'];

        $pi->save();

        $this->saveSuccess = 'Personal info saved successfully.';
    }

    public function deleteFamilyMember(int $id): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $member = UserFamilyInfo::query()
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $member->delete();

        session()->flash('status', 'Family traveler removed.');
        $this->redirect(route('profile', ['tab' => 'family']), navigate: true);
    }

    public function render()
    {
        $user = Auth::user();

        $familyMembers = collect();
        if ($user) {
            $familyMembers = UserFamilyInfo::query()
                ->where('user_id', $user->id)
                ->orderByDesc('updated_at')
                ->orderBy('id')
                ->get();
        }

        return view('livewire.profile.profile', [
            'familyMembers' => $familyMembers,
        ]);
    }
}
