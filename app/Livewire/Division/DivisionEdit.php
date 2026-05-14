<?php

namespace App\Livewire\Division;

use App\Models\Division;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class DivisionEdit extends Component
{
    public Division $division;
    public string $name = '';
    public string $description = '';
    public string $status = 'active';

    public function mount(Division $division)
    {
        $this->division = $division;
        $this->name = $division->name;
        $this->description = $division->description ?? '';
        $this->status = $division->status;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $this->division->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', "Division '{$validated['name']}' updated successfully.");
        return redirect()->route('divisions.index');
    }

    public function render()
    {
        return view('livewire.division.division-edit');
    }
}
