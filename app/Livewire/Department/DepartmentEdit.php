<?php

namespace App\Livewire\Department;

use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class DepartmentEdit extends Component
{
    public Department $department;
    public string $name = '';
    public string $description = '';
    public string $status = 'active';
    public ?int $division_id = null;
    public $divisions = [];

    public ?int $companyId = null;

    public function mount(int $companyId, int $id)
    {
        $this->companyId = $companyId;
        $this->department = Department::withoutGlobalScopes()->findOrFail($id);
        $this->name = $this->department->name ?? '';
        $this->description = $this->department->description ?? '';
        $this->status = $this->department->status ?? 'active';
        $this->division_id = $this->department->division_id;
        $this->divisions = \App\Models\Division::where('company_id', $this->companyId)->orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'division_id' => ['nullable', 'exists:divisions,id'],
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $this->department->update([
            'division_id' => $this->division_id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', "Department '{$validated['name']}' updated successfully.");
        return redirect()->route('departments.index', ['companyId' => $this->companyId]);
    }

    public function render()
    {
        return view('livewire.department.department-edit');
    }
}
