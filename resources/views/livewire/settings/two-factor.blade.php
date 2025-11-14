<div>
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
</div>
