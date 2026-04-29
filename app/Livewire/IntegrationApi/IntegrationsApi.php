<?php

namespace App\Livewire\IntegrationApi;

use App\Support\EnvEditor;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

#[Layout('layouts.flight')]
class IntegrationsApi extends Component
{
    /** @var array<string, string> */
    public array $values = [];

    /** @var array<int, array{title:string, keys:array<int, string>}> */
    public array $sections = [];

    public string $statusMessage = '';
    public string $errorMessage = '';

    public function mount(): void
    {
        abort_unless(Auth::check() && Auth::user()->can('Manage Global System'), 403);

        $envPath = base_path('.env');
        $map = EnvEditor::toKeyValueMap(EnvEditor::read($envPath));

        $this->sections = [
            ['title' => 'Amadeus API', 'keys' => [
                'AMADEUS_URL',
                'AMADEUS_CLIENT_ID',
                'AMADEUS_CLIENT_SECRET',
                'AMADEUS_GRANT_TYPE',
            ]],
            ['title' => 'Mail (SMTP)', 'keys' => [
                'MAIL_MAILER',
                'MAIL_HOST',
                'MAIL_PORT',
                'MAIL_USERNAME',
                'MAIL_PASSWORD',
                'MAIL_ENCRYPTION',
                'MAIL_FROM_ADDRESS',
                'MAIL_FROM_NAME',
            ]],
            ['title' => 'AWS / Storage', 'keys' => [
                'FILESYSTEM_DISK',
                'AWS_ACCESS_KEY_ID',
                'AWS_SECRET_ACCESS_KEY',
                'AWS_DEFAULT_REGION',
                'AWS_BUCKET',
                'AWS_USE_PATH_STYLE_ENDPOINT',
            ]],
            ['title' => 'App', 'keys' => [
                'APP_NAME',
                'APP_ENV',
                'APP_DEBUG',
                'APP_URL',
                'APP_TIMEZONE',
                'APP_LOCALE',
                'APP_FALLBACK_LOCALE',
            ]],
            ['title' => 'Database', 'keys' => [
                'DB_CONNECTION',
                'DB_HOST',
                'DB_PORT',
                'DB_DATABASE',
                'DB_USERNAME',
                'DB_PASSWORD',
            ]],
            ['title' => 'Cache / Queue', 'keys' => [
                'CACHE_STORE',
                'CACHE_PREFIX',
                'QUEUE_CONNECTION',
                'SESSION_DRIVER',
                'REDIS_HOST',
                'REDIS_PORT',
                'REDIS_PASSWORD',
            ]],
        ];

        $keys = [];
        foreach ($this->sections as $section) {
            foreach ($section['keys'] as $key) {
                $keys[$key] = true;
            }
        }

        foreach (array_keys($keys) as $key) {
            $this->values[$key] = $map[$key] ?? '';
        }
    }

    public function save(): void
    {
        if (!Auth::check() || !Auth::user()->can('Manage Global System')) {
            $this->statusMessage = '';
            $this->errorMessage = 'Not allowed (permission check failed).';
            return;
        }

        try {
            $this->statusMessage = '';
            $this->errorMessage = '';

            $envPath = base_path('.env');

            $current = EnvEditor::read($envPath);
            $updated = EnvEditor::applyUpdates($current, $this->values);
            EnvEditor::write($envPath, $updated);

            // Don't clear config cache inside the Livewire request: on some local stacks
            // this can reset the connection and the UI won't receive the response.
            $this->statusMessage = 'Saved.';
        } catch (Throwable $e) {
            $this->statusMessage = '';
            $this->errorMessage = 'Save failed: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.integrationapi.index');
    }
}
