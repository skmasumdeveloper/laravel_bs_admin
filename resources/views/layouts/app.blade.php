@php
    // Provide a simple adapter so views that use @extends('layouts.app')
    // work with the component-based layout used in this project.
    $title = View::getSection('title') ?? ($title ?? null);
@endphp

<x-layouts.app.sidebar :title="$title">
    @yield('content')
</x-layouts.app.sidebar>
