<x-layouts.app :title="__('Two-factor authentication')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Two-factor authentication')" :subheading="__('Protect your account with a second factor')">
            <livewire:settings.two-factor-settings />
        </x-settings.layout>
    </section>
</x-layouts.app>
