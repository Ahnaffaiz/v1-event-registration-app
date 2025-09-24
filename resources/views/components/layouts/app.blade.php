<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    @include('components.include.styles')

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    @if (request()->is('display/*'))
        <div id="fullscreen-container">
            {{ $slot }}
        </div>
    @else
        <div class="flex h-screen bg-slate-100">
            @include('components.layouts.partials.sidebar')
            <div class="flex flex-col flex-1 overflow-hidden">
                @include('components.layouts.partials.topbar')
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100">
                    <div class="p-6 mx-auto">
                        <h1 class="font-sans text-2xl font-medium text-gray-900">{{ $title ?? env('APP_NAME') }}</h1>
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    @endif


    @livewire('notifications')

    @filamentScripts
    @vite('resources/js/app.js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    @include('components.layouts.partials.scripts')
    @stack('scripts')
</body>

</html>
