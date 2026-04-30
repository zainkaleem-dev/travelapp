<?php

namespace App\Livewire\Company;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyCreateBranches extends Component
{
    public int $companyId;
    public Company $company;

    // Branch Section (Dynamic Repeater)
    public bool $create_branch = true;
    public array $branches = [];

    public function mount(int $id): void
    {
        $this->companyId = $id;
        $this->company = Company::findOrFail($id);

        $this->addBranch();
    }

    public function addBranch(): void
    {
        $this->branches[] = [
            'name' => '',
            'code' => '',
            'slug' => '',
            'email' => '',
            'phone' => '',
            'address_line_1' => '',
            'city' => '',
            'state' => '',
            'country' => '',
        ];
    }

    public function removeBranch(int $index): void
    {
        if (count($this->branches) > 1) {
            unset($this->branches[$index]);
            $this->branches = array_values($this->branches);
        }
    }

    public function updatedBranches($value, $key): void
    {
        $parts = explode('.', $key);
        if (count($parts) < 2) return;

        $index = (int) $parts[0];
        $field = $parts[1];

        if ($field === 'name') {
            $this->updateBranchFields($index, $value);
        }
    }

    private function updateBranchFields(int $index, string $name): void
    {
        if (isset($this->branches[$index])) {
            if (empty($this->branches[$index]['slug'])) {
                $this->branches[$index]['slug'] = str($name)->slug()->toString();
            }
            if (empty($this->branches[$index]['code'])) {
                $this->branches[$index]['code'] = strtoupper(substr(str($name)->slug('')->toString(), 0, 3)) . rand(100, 999);
            }
        }
    }

    protected function rules(): array
    {
        return [
            'branches.*.name' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.code' => [Rule::requiredIf($this->create_branch), 'string', 'max:50', 'unique:branches,code'],
            'branches.*.slug' => [Rule::requiredIf($this->create_branch), 'string', 'max:255', 'unique:branches,slug'],
            'branches.*.email' => [Rule::requiredIf($this->create_branch), 'email', 'max:255'],
            'branches.*.phone' => [Rule::requiredIf($this->create_branch), 'string', 'max:50'],
            'branches.*.address_line_1' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.city' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.state' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.country' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'branches.*.name.required' => 'Branch name is required.',
            'branches.*.code.required' => 'Branch code is required.',
            'branches.*.code.unique' => 'This branch code is already in use.',
            'branches.*.email.required' => 'Branch email is required.',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            if ($this->create_branch) {
                foreach ($this->branches as $index => $branchData) {
                    Branch::query()->create(array_merge($branchData, [
                        'company_id' => $this->companyId,
                        'is_main' => ($index === 0),
                        'status' => 'active',
                    ]));
                }
            }
        });

        session()->flash('status', 'Branches created successfully.');
        return $this->redirect(route('companies.index'));
    }

    public function render()
    {
        return view('livewire.company.create-branches');
    }
}
