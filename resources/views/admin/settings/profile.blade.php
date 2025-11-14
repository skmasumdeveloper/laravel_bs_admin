<x-layouts.app :title="__('Profile Settings')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Profile')" :subheading="__('Update your personal details')">
            <div class="alert alert-info">
                Profile settings functionality is available through standard Laravel forms. This section can be enhanced based on your requirements.
            </div>
        </x-settings.layout>
    </section>
</x-layouts.app>
