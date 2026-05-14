<?php

namespace App\Livewire\Grade;

use App\Models\Grade;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class GradeCreate extends Component
{
    public string $name = '';
    public string $description = '';
    public string $status = 'active';
    public ?int $department_id = null;
    public $departments = [];

    public ?int $companyId = null;

    public function mount(int $companyId)
    {
        $this->companyId = $companyId;
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

    public function save(TenantContext $tenantContext)
    {
        $validated = $this->validate();

        Grade::create([
            'company_id' => $this->companyId,
            'department_id' => $this->department_id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', "Grade '{$validated['name']}' created successfully.");
        return redirect()->route('grades.index', ['companyId' => $this->companyId]);
    }

    public function render()
    {
        return view('livewire.grade.grade-create');
    }
}
