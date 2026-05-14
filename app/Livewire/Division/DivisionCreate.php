<?php

namespace App\Livewire\Division;

use App\Models\Division;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class DivisionCreate extends Component
{
    public string $name = '';
    public string $description = '';
    public string $status = 'active';

    public ?int $companyId = null;

    public function mount(int $companyId)
    {
        $this->companyId = $companyId;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function save(TenantContext $tenantContext)
    {
        $validated = $this->validate();

        Division::create([
            'company_id' => $this->companyId,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', "Division '{$validated['name']}' created successfully.");
        return redirect()->route('divisions.index', ['companyId' => $this->companyId]);
    }

    public function render()
    {
        return view('livewire.division.division-create');
    }
}
