<?php

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Symfony\Component\HttpFoundation\Response;

new class extends Component {
    #[Locked]
    public bool $twoFactorEnabled;

    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showModal = false;

    public bool $showVerificationStep = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        abort_unless(Features::enabled(Features::twoFactorAuthentication()), Response::HTTP_FORBIDDEN);

        if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
            $disableTwoFactorAuthentication(auth()->user());
        }

        $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enable(EnableTwoFactorAuthentication $enableTwoFactorAuthentication): void
    {
        $enableTwoFactorAuthentication(auth()->user());

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }

        $this->loadSetupData();

        $this->showModal = true;
    }

    /**
     * Load the two-factor authentication setup data for the user.
     */
    private function loadSetupData(): void
    {
        $user = auth()->user();

        try {
            $this->qrCodeSvg = $user?->twoFactorQrCodeSvg();
            $this->manualSetupKey = decrypt($user->two_factor_secret);
        } catch (Exception) {
            $this->addError('setupData', 'Failed to fetch setup data.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    /**
     * Show the two-factor verification step if necessary.
     */
    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;

            $this->resetErrorBag();

            return;
        }

        $this->closeModal();
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        $confirmTwoFactorAuthentication(auth()->user(), $this->code);

        $this->closeModal();

        $this->twoFactorEnabled = true;
    }

    /**
     * Reset two-factor verification state.
     */
    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');

        $this->resetErrorBag();
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }

    /**
     * Close the two-factor authentication modal.
     */
    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showModal',
            'showVerificationStep',
        );

        $this->resetErrorBag();

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }
    }

    /**
     * Get the current modal configuration state.
     */
    public function getModalConfigProperty(): array
    {
        if ($this->twoFactorEnabled) {
            return [
                'title' => __('Two-Factor Authentication Enabled'),
                'description' => __('Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.'),
                'buttonText' => __('Close'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verify Authentication Code'),
                'description' => __('Enter the 6-digit code from your authenticator app.'),
                'buttonText' => __('Continue'),
            ];
        }

        return [
            'title' => __('Enable Two-Factor Authentication'),
            'description' => __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.'),
            'buttonText' => __('Continue'),
        ];
    }
} ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout
        :heading="__('Two Factor Authentication')"
        :subheading="__('Manage your two-factor authentication settings')"
    >
        <div class="card" wire:cloak>
            <div class="card-body">
                @if ($twoFactorEnabled)
                    <div class="mb-3">
                        <span class="badge bg-success">{{ __('Enabled') }}</span>
                    </div>

                    <p class="mb-3">{{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}</p>

                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation/>

                    <div class="mt-3">
                        <button type="button" class="btn btn-danger btn-sm" wire:click="disable">{{ __('Disable 2FA') }}</button>
                    </div>
                @else
                    <div class="mb-3">
                        <span class="badge bg-danger">{{ __('Disabled') }}</span>
                    </div>

                    <p class="text-muted mb-3">{{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}</p>

                    <button type="button" class="btn btn-primary" wire:click="enable">{{ __('Enable 2FA') }}</button>
                @endif
            </div>
        </div>
    </x-settings.layout>

    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $this->modalConfig['title'] }}</h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">{{ $this->modalConfig['description'] }}</p>

                        @if ($showVerificationStep)
                            <div class="text-center">
                                <x-input-otp :digits="6" name="code" wire:model="code" autocomplete="one-time-code" />
                                @error('code')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn btn-outline-secondary flex-fill" wire:click="resetVerification">{{ __('Back') }}</button>
                                <button type="button" class="btn btn-primary flex-fill" wire:click="confirmTwoFactor" @disabled($errors->has('code'))>{{ __('Confirm') }}</button>
                            </div>
                        @else
                            @error('setupData')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="d-flex justify-content-center mb-3">
                                <div class="border rounded p-3 bg-white">
                                    @empty($qrCodeSvg)
                                        <div class="d-flex align-items-center justify-content-center" style="width:160px;height:160px;">
                                            <div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>
                                        </div>
                                    @else
                                        {!! $qrCodeSvg !!}
                                    @endempty
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-primary w-100" @if($errors->has('setupData')) disabled @endif wire:click="showVerificationIfNecessary">{{ $this->modalConfig['buttonText'] }}</button>
                            </div>

                            <div class="text-center small text-muted mb-2">{{ __('or, enter the code manually') }}</div>

                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group">
                                    @empty($manualSetupKey)
                                        <div class="input-group-text">
                                            <div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>
                                        </div>
                                    @else
                                        <input type="text" readonly class="form-control" value="{{ $manualSetupKey }}" id="manual-setup-key" />
                                        <button class="btn btn-outline-secondary" type="button" id="copy-manual-key">{{ __('Copy') }}</button>
                                    @endempty
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>

        <script>
            document.addEventListener('click', function (e) {
                if (e.target && e.target.id === 'copy-manual-key') {
                    const el = document.getElementById('manual-setup-key');
                    if (!el) return;
                    navigator.clipboard.writeText(el.value).then(() => {
                        const btn = document.getElementById('copy-manual-key');
                        const original = btn.innerText;
                        btn.innerText = 'Copied';
                        setTimeout(() => btn.innerText = original, 1500);
                    }).catch(() => console.warn('Could not copy to clipboard'));
                }
            });
        </script>
    @endif
</section>
