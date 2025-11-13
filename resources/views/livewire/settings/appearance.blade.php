<div>
    <div class="btn-group" role="group" aria-label="Appearance options">
        <input type="radio" class="btn-check" name="appearance" id="appearance-light" value="light" wire:model.live="mode">
        <label class="btn btn-outline-secondary" for="appearance-light">{{ __('Light') }}</label>

        <input type="radio" class="btn-check" name="appearance" id="appearance-dark" value="dark" wire:model.live="mode">
        <label class="btn btn-outline-secondary" for="appearance-dark">{{ __('Dark') }}</label>

        <input type="radio" class="btn-check" name="appearance" id="appearance-system" value="system" wire:model.live="mode">
        <label class="btn btn-outline-secondary" for="appearance-system">{{ __('System') }}</label>
    </div>

    <p class="small text-muted mt-3">
        {{ __('Preference saved to your current session. Hook into the appearance-updated event from JS to persist it elsewhere.') }}
    </p>
</div>
