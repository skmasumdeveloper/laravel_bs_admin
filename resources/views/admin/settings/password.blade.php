<x-layouts.app :title="__('Password Settings')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account stays secure with a strong password')">
            <div class="alert alert-info">
                Password change functionality is available through standard Laravel forms. This section can be enhanced based on your requirements.
            </div>
        </x-settings.layout>
    </section>
</x-layouts.app>
