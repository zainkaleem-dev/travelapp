<?php

namespace App\Livewire\Company;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyEditBranches extends Component
{
    public int $companyId;
    public Company $company;

    // Branch Section (Dynamic Repeater)
    public bool $create_branch = true;
    public array $branches = [];

    public function mount(int $id): void
    {
        $this->companyId = $id;

        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        /** @var \App\Support\TenantContext $tenantContext */
        $tenantContext = app(\App\Support\TenantContext::class);
        $manageableHierarchy = $tenantContext->getManageableHierarchy($currentUser);

        $this->company = Company::query()
            ->withoutGlobalScopes()
            ->findOrFail($id);

        if (!$isSuperAdmin && !in_array($this->company->id, $manageableHierarchy, true)) {
            abort(403, 'You do not have permission to edit this organization (Access denied).');
        }

        $this->loadBranches();
    }

    private function loadBranches(): void
    {
        $this->branches = Branch::query()
            ->withoutGlobalScopes()
            ->where('company_id', $this->company->id)
            ->orderByDesc('is_main')
            ->orderBy('id')
            ->get()
            ->map(fn (Branch $branch) => [
                'id' => $branch->id,
                'name' => $branch->name ?? '',
                'code' => $branch->code ?? '',
                'slug' => $branch->slug ?? '',
                'email' => $branch->email ?? '',
                'phone' => $branch->phone ?? '',
                'address_line_1' => $branch->address_line_1 ?? '',
                'city' => $branch->city ?? '',
                'state' => $branch->state ?? '',
                'country' => $branch->country ?? '',
            ])
            ->all();

        if ($this->branches === []) {
            $this->addBranch();
        }
    }

    public function addBranch(): void
    {
        $this->branches[] = [
            'id' => null,
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
        if (!isset($this->branches[$index])) {
            return;
        }

        if (count($this->branches) <= 1) {
            return;
        }

        $branchId = $this->branches[$index]['id'] ?? null;
        if ($branchId) {
            Branch::query()
                ->withoutGlobalScopes()
                ->where('company_id', $this->company->id)
                ->whereKey((int) $branchId)
                ->delete();
        }

        unset($this->branches[$index]);
        $this->branches = array_values($this->branches);
    }

    public function updatedBranches($value, $key): void
    {
        $parts = explode('.', (string) $key);
        if (count($parts) < 2) {
            return;
        }

        $index = (int) $parts[0];
        $field = $parts[1];

        if ($field === 'name') {
            $this->updateBranchFields($index, (string) $value);
        }
    }

    private function updateBranchFields(int $index, string $name): void
    {
        if (!isset($this->branches[$index])) {
            return;
        }

        if (empty($this->branches[$index]['slug'])) {
            $this->branches[$index]['slug'] = str($name)->slug()->toString();
        }

        if (empty($this->branches[$index]['code'])) {
            $this->branches[$index]['code'] = strtoupper(substr(str($name)->slug('')->toString(), 0, 3)) . rand(100, 999);
        }
    }

    protected function rules(): array
    {
        return [
            'branches.*.name' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.code' => [Rule::requiredIf($this->create_branch), 'string', 'max:50'],
            'branches.*.slug' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.email' => [Rule::requiredIf($this->create_branch), 'email', 'max:255'],
            'branches.*.phone' => [Rule::requiredIf($this->create_branch), 'string', 'max:50'],
            'branches.*.address_line_1' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.city' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.state' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.country' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();
        $this->validateBranchUniqueness();

        DB::transaction(function () use ($validated) {
            foreach ($this->branches as $index => $branchData) {
                $branchId = $branchData['id'] ?? null;

                $payload = array_merge($branchData, [
                    'company_id' => $this->company->id,
                    'is_main' => ($index === 0),
                    'status' => 'active',
                ]);
                unset($payload['id']);

                if ($branchId) {
                    Branch::query()
                        ->withoutGlobalScopes()
                        ->where('company_id', $this->company->id)
                        ->whereKey((int) $branchId)
                        ->update($payload);
                } else {
                    $created = Branch::query()->create($payload);
                    $this->branches[$index]['id'] = $created->id;
                }
            }

            $mainId = $this->branches[0]['id'] ?? null;
            if ($mainId) {
                Branch::query()
                    ->withoutGlobalScopes()
                    ->where('company_id', $this->company->id)
                    ->whereKeyNot($mainId)
                    ->update(['is_main' => false]);
            }
        });

        session()->flash('status', 'Branches updated successfully.');
        return $this->redirect(route('companies.edit', $this->companyId));
    }

    private function validateBranchUniqueness(): void
    {
        foreach ($this->branches as $index => $branch) {
            $branchId = $branch['id'] ?? null;
            $code = (string) ($branch['code'] ?? '');
            $slug = (string) ($branch['slug'] ?? '');

            if ($code !== '') {
                $query = Branch::query()
                    ->withoutGlobalScopes()
                    ->where('code', $code);
                if ($branchId) {
                    $query->where('id', '!=', (int) $branchId);
                }
                if ($query->exists()) {
                    throw ValidationException::withMessages([
                        "branches.{$index}.code" => 'This branch code is already in use.',
                    ]);
                }
            }

            if ($slug !== '') {
                $query = Branch::query()
                    ->withoutGlobalScopes()
                    ->where('slug', $slug);
                if ($branchId) {
                    $query->where('id', '!=', (int) $branchId);
                }
                if ($query->exists()) {
                    throw ValidationException::withMessages([
                        "branches.{$index}.slug" => 'This branch slug is already in use.',
                    ]);
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.company.edit-branches');
    }
}
