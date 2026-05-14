<?php

namespace App\Livewire\Grade;

use App\Models\Grade;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class GradeEdit extends Component
{
    public Grade $grade;
    public string $name = '';
    public string $description = '';
    public string $status = 'active';
    public ?int $department_id = null;
    public $departments = [];

    public ?int $companyId = null;

    public function mount(int $companyId, Grade $grade)
    {
        $this->companyId = $companyId;
        $this->grade = $grade;
        $this->name = $grade->name ?? '';
        $this->description = $grade->description ?? '';
        $this->status = $grade->status ?? 'active';
        $this->department_id = $grade->department_id;
        $this->departments = \App\Models\Department::where('company_id', $this->companyId)->orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $this->grade->update([
            'department_id' => $this->department_id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', "Grade '{$validated['name']}' updated successfully.");
        return redirect()->route('grades.index', ['companyId' => $this->companyId]);
    }

    public function render()
    {
        return view('livewire.grade.grade-edit');
    }
}
