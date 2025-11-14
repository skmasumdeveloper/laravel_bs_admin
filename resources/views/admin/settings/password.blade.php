<x-layouts.app :title="__('Password Settings')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account stays secure with a strong password')">
            <livewire:settings.password-form />
        </x-settings.layout>
    </section>
</x-layouts.app>
