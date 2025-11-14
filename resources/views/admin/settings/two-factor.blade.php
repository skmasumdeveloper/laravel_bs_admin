<x-layouts.app :title="__('Two-factor authentication')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Two-factor authentication')" :subheading="__('Protect your account with a second factor')">
            <div class="alert alert-info">
                Two-factor authentication is handled by Laravel Fortify. This section can be enhanced based on your requirements.
            </div>
        </x-settings.layout>
    </section>
</x-layouts.app>
