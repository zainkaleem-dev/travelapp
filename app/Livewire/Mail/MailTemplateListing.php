<?php

namespace App\Livewire\Mail;

use App\Models\MailTemplate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class MailTemplateListing extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $status = '';

    protected $updatesQueryString = ['search', 'type', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        MailTemplate::findOrFail($id)->delete();
        session()->flash('success', 'Template deleted successfully.');
    }

    public function render()
    {
        $query = MailTemplate::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('subject', 'like', '%' . $this->search . '%');
            })
            ->when($this->type, function ($q) {
                $q->where('type', $this->type);
            })
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.mail.mail-template-listing', [
            'templates' => $query->paginate(10)
        ]);
    }
}
