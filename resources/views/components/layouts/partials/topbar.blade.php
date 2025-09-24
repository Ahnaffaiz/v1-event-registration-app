<header class=" bg-slate-100">
    <div class="flex content-center h-16 px-6 mx-auto">
        <div class="flex justify-end w-full">
            <div class="flex items-center justify-end flex-1">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center text-gray-500 hover:text-gray-600 focus:outline-none">
                        <span class="mr-2">{{ Auth::user()?->name }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white shadow-xl rounded-2xl">
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
