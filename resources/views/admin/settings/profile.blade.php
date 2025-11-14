<x-layouts.app :title="__('Profile Settings')">
    <section class="w-100">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Profile')" :subheading="__('Update your personal details')">
            <livewire:settings.profile-form />

            <div class="mt-5">
                <livewire:settings.delete-user-form />
            </div>
        </x-settings.layout>
    </section>
</x-layouts.app>
