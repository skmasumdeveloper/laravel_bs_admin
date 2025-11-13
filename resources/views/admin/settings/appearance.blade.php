<x-layouts.app :title="__('Appearance Settings')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Appearance')" :subheading="__('Toggle light, dark, or system mode')">
            <livewire:settings.appearance-settings />
        </x-settings.layout>
    </section>
</x-layouts.app>
