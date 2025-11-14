<x-layouts.app :title="__('Appearance Settings')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Appearance')" :subheading="__('Toggle light, dark, or system mode')">
            <div class="alert alert-info">
                Appearance settings can be implemented based on your theme requirements.
            </div>
        </x-settings.layout>
    </section>
</x-layouts.app>
