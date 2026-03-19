<?php

namespace App\Livewire\Concerns;

trait HasPersonalInfoFormFields
{
    public ?string $first_name = null;
    public ?string $last_name = null;
    public string $email = '';
    public ?string $phone = null;
    public ?string $dob = null;
    public ?string $gender = null;
    public ?string $nationality = null;

    public ?string $passport_number = null;
    public ?string $expiry_date = null;
    public ?string $issuing_country = null;

    public ?string $purpose_of_travel = null;
    public ?string $seat_preference = null;
    public ?string $meal_preference = null;
    public ?string $preferred_cabin = null;
    public ?string $preferred_airline = null;

    protected function personalInfoFormRules(): array
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',

            'passport_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'issuing_country' => 'nullable|string|max:255',

            'purpose_of_travel' => 'nullable|string|max:255',
            'seat_preference' => 'nullable|string|max:255',
            'meal_preference' => 'nullable|string|max:255',
            'preferred_cabin' => 'nullable|string|max:255',
            'preferred_airline' => 'nullable|string|max:255',
        ];
    }

    /** @return array<string, mixed> */
    protected function personalInfoFormToArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'dob' => $this->dob ? date('Y-m-d', strtotime($this->dob)) : null,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'passport_number' => $this->passport_number,
            'expiry_date' => $this->expiry_date ? date('Y-m-d', strtotime($this->expiry_date)) : null,
            'issuing_country' => $this->issuing_country,
            'purpose_of_travel' => $this->purpose_of_travel,
            'seat_preference' => $this->seat_preference,
            'meal_preference' => $this->meal_preference,
            'preferred_cabin' => $this->preferred_cabin,
            'preferred_airline' => $this->preferred_airline,
        ];
    }
}
