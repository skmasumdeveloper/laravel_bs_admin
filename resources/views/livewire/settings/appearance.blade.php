<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <div class="btn-group" role="group" aria-label="Appearance options">
            <input type="radio" class="btn-check" name="appearance" id="appearance-light" autocomplete="off" checked>
            <label class="btn btn-outline-secondary" for="appearance-light">{{ __('Light') }}</label>

            <input type="radio" class="btn-check" name="appearance" id="appearance-dark" autocomplete="off">
            <label class="btn btn-outline-secondary" for="appearance-dark">{{ __('Dark') }}</label>

            <input type="radio" class="btn-check" name="appearance" id="appearance-system" autocomplete="off">
            <label class="btn btn-outline-secondary" for="appearance-system">{{ __('System') }}</label>
        </div>
    </x-settings.layout>
</section>
