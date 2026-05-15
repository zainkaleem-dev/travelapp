<?php

namespace App\Livewire\Mail;

use App\Models\MailTemplate;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class MailTemplateEdit extends Component
{
    public $templateId;
    public $name = '';
    public $subject = '';
    public $content = '';
    public $type = '';
    public $status = '';

    public function mount($id)
    {
        $template = MailTemplate::findOrFail($id);
        $this->templateId = $template->id;
        $this->name = $template->name;
        $this->subject = $template->subject;
        $this->content = $template->content;
        $this->type = $template->type;
        $this->status = $template->status;
    }

    protected function rules()
    {
        return [
            'name' => 'required|alpha_dash|unique:mail_templates,name,' . $this->templateId,
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:System,Booking,Marketing',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function update()
    {
        $this->validate();

        $template = MailTemplate::findOrFail($this->templateId);
        $template->update([
            'name' => $this->name,
            'subject' => $this->subject,
            'content' => $this->content,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Mail template updated successfully.');
        return redirect()->route('admin.mail.index');
    }

    public function render()
    {
        return view('livewire.mail.mail-template-edit');
    }
}
