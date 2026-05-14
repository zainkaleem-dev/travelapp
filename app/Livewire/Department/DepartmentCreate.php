<?php

namespace App\Livewire\Department;

use App\Models\Department;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class DepartmentCreate extends Component
{
    public string $name = '';
    public string $description = '';
    public string $status = 'active';
    public ?int $division_id = null;
    public $divisions = [];

    public ?int $companyId = null;

    public function mount(int $companyId)
    {
        $this->companyId = $companyId;
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

    public function save(TenantContext $tenantContext)
    {
        $validated = $this->validate();

        Department::create([
            'company_id' => $this->companyId,
            'division_id' => $this->division_id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', "Department '{$validated['name']}' created successfully.");
        return redirect()->route('departments.index', ['companyId' => $this->companyId]);
    }

    public function render()
    {
        return view('livewire.department.department-create');
    }
}
