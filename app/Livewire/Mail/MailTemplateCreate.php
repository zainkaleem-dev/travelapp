<?php

namespace App\Livewire\Mail;

use App\Models\MailTemplate;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class MailTemplateCreate extends Component
{
    public $name = '';
    public $subject = '';
    public $content = '';
    public $type = 'System';
    public $status = 'active';

    protected $rules = [
        'name' => 'required|unique:mail_templates,name|alpha_dash',
        'subject' => 'required|string|max:255',
        'content' => 'required|string',
        'type' => 'required|in:System,Booking,Marketing',
        'status' => 'required|in:active,inactive',
    ];

    public function save()
    {
        $this->validate();

        MailTemplate::create([
            'name' => $this->name,
            'subject' => $this->subject,
            'content' => $this->content,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Mail template created successfully.');
        return redirect()->route('admin.mail.index');
    }

    public function render()
    {
        return view('livewire.mail.mail-template-create');
    }
}
