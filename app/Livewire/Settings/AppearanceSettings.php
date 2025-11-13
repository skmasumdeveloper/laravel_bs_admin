<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class AppearanceSettings extends Component
{
    public string $mode = 'system';

    public function mount(): void
    {
        $this->mode = session('appearance.mode', 'system');
    }

    public function updatedMode(string $value): void
    {
        session(['appearance.mode' => $value]);

        $this->dispatch('appearance-updated', mode: $value);
    }

    public function render()
    {
        return view('livewire.settings.appearance');
    }
}
