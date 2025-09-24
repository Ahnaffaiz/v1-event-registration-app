<nav class="text-black bg-white shadow-md">
    <div class="container px-4 mx-auto">
        <div class="flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="{{ '/' }}" class="text-xl font-bold">
                    Logo
                </a>
            </div>
            <div class="flex-1 hidden md:flex md:items-center md:justify-center">
                <div class="flex items-baseline space-x-4">
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-100  transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                        <x-heroicon-o-home class="inline-block w-5 h-5 mr-1" />
                        Dashboard
                    </a>
                    <a href="{{ route('single-checkin') }}" wire:navigate
                        class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-100  transition duration-200 {{ request()->routeIs('single-checkin') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                        <x-heroicon-o-qr-code class="inline-block w-5 h-5 mr-1" />
                        Single Check In
                    </a>
                    <a href="{{ route('multiple-checkin') }}" wire:navigate
                        class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-100  transition duration-200 {{ request()->routeIs('multiple-checkin') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                        <x-heroicon-o-puzzle-piece class="inline-block w-5 h-5 mr-1" />
                        Multiple Check In
                    </a>
                    <a href="{{ route('activity-checkin') }}" wire:navigate
                        class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-100  transition duration-200 {{ request()->routeIs('activity-checkin') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                        <x-heroicon-o-ticket class="inline-block w-5 h-5 mr-1 rotate-45" />
                        Activity Check In
                    </a>
                </div>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button"
                    class="inline-flex items-center justify-center p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="hidden transition-all duration-300 md:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 text-base font-medium rounded-md hover:bg-gray-100 transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                <x-heroicon-o-home class="inline-block w-5 h-5 mr-1" />
                Home
            </a>
            <a href="{{ route('single-checkin') }}"
                class="block px-3 py-2 text-base font-medium rounded-md hover:bg-gray-100  transition duration-200 {{ request()->routeIs('single-checkin') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                <x-heroicon-o-qr-code class="inline-block w-5 h-5 mr-1" />
                Single Check In
            </a>
            <a href="{{ route('multiple-checkin') }}"
                class="block px-3 py-2 text-base font-medium rounded-md hover:bg-gray-100   transition duration-200 {{ request()->routeIs('multiple-checkin') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                <x-heroicon-o-puzzle-piece class="inline-block w-5 h-5 mr-1" />
                Multiple Check In
            </a>
            <a href="{{ route('activity-checkin') }}"
                class="block px-3 py-2 text-base font-medium rounded-md hover:bg-gray-100   transition duration-200 {{ request()->routeIs('activity-checkin') ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                <x-heroicon-o-ticket class="inline-block w-5 h-5 mr-1" />
                Activity Check In
            </a>
        </div>
    </div>
</nav>
