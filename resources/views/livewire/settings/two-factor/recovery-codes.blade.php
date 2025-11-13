<?php

use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new class extends Component {
    #[Locked]
    public array $recoveryCodes = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $generateNewRecoveryCodes(auth()->user());

        $this->loadRecoveryCodes();
    }

    /**
     * Load the recovery codes for the user.
     */
    private function loadRecoveryCodes(): void
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication() && $user->two_factor_recovery_codes) {
            try {
                $this->recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            } catch (Exception) {
                $this->addError('recoveryCodes', 'Failed to load recovery codes');

                $this->recoveryCodes = [];
            }
        }
    }
}; ?>

<div class="card mb-3" wire:cloak>
    <div class="card-body">
        <div class="d-flex align-items-center mb-2">
            <h3 class="h5 mb-0">{{ __('2FA Recovery Codes') }}</h3>
        </div>
        <p class="text-muted">{{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}</p>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-2">
            <div>
                <button id="btn-view-codes" type="button" class="btn btn-primary btn-sm" onclick="toggleRecoveryCodes(true)">{{ __('View Recovery Codes') }}</button>
                <button id="btn-hide-codes" type="button" class="btn btn-outline-primary btn-sm d-none" onclick="toggleRecoveryCodes(false)">{{ __('Hide Recovery Codes') }}</button>
            </div>

            @if (filled($recoveryCodes))
                <div class="mt-2 mt-sm-0">
                    <button type="button" class="btn btn-success btn-sm" wire:click="regenerateRecoveryCodes">{{ __('Regenerate Codes') }}</button>
                </div>
            @endif
        </div>

        <div id="recovery-codes-section" class="d-none">
            <div class="mt-3">
                @error('recoveryCodes')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                @if (filled($recoveryCodes))
                    <pre class="bg-light p-3 rounded" role="list" aria-label="Recovery codes">
@foreach($recoveryCodes as $code)
{{ $code }}
@endforeach
                    </pre>
                    <p class="small text-muted mt-2">{{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate Codes above.') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function toggleRecoveryCodes(show) {
        const section = document.getElementById('recovery-codes-section');
        const viewBtn = document.getElementById('btn-view-codes');
        const hideBtn = document.getElementById('btn-hide-codes');

        if (show) {
            section.classList.remove('d-none');
            viewBtn.classList.add('d-none');
            hideBtn.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
            viewBtn.classList.remove('d-none');
            hideBtn.classList.add('d-none');
        }
    }
</script>
