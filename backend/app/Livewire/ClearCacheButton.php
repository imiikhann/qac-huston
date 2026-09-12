<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class ClearCacheButton extends Component
{
    public bool $clearing = false;

    public function clear(): void
    {
        $this->clearing = true;

        $start = hrtime(true);
        $messages = collect();

        try {
            $code = Artisan::call('optimize:clear');
            $messages->push('config, route, view, event & app caches cleared');
        } catch (\Throwable $e) {
            $messages->push('optimize:clear failed: ' . $e->getMessage());
        }

        foreach (['config.php', 'routes-v7.php', 'events.php', 'packages.php', 'services.php', 'compiled.php'] as $file) {
            @unlink(base_path('bootstrap/cache/' . $file));
        }

        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }

        try {
            Artisan::call('octane:reload');
        } catch (\Throwable) {
            // Octane RPC may not be exposed (e.g. Laravel valet/serve); skip silently.
        }

        $ms = round((hrtime(true) - $start) / 1_000_000);

        $this->clearing = false;

        Notification::make()
            ->title('Cache cleared')
            ->body($messages->implode('. ') . ". Done in {$ms}ms. Reloading… (press Ctrl+F5 in the browser to bypass browser cache).")
            ->success()
            ->send();

        $this->dispatch('cache-cleared');
    }

    public function render()
    {
        return view('livewire.clear-cache-button');
    }
}