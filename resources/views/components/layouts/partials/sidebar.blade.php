<aside id="sidebar" class="text-gray-800 bg-white w-18 lg:w-64">
    <div class="flex items-center justify-between h-16 pl-6 text-center bg-white">
        <img src="{{ asset('images/ema_dark.png') }}" alt="" srcset="" class="w-24">
        <button class="bg-transparent rounded-lg" id="sidebar-toggle">
            <x-fluentui-line-horizontal-3-20 class="block w-5 h-5 hover:text-emerald-500 md:hidden" />
        </button>
    </div>
    <nav class="px-4 mt-8">
        <ul class="space-y-3">
            <x-sidebar-item icon="heroicon-o-home" label="Dashboard" activeRoute="dashboard" />
            <x-sidebar-item icon="heroicon-o-calendar-days" label="Event Management" activeRoute="event" />
            <x-sidebar-item icon="heroicon-o-ticket" label="Single Checkin" activeRoute="single-checkin" />
            <x-sidebar-item icon="heroicon-o-puzzle-piece" label="Multiple Checkin" activeRoute="multiple-checkin" />
            <x-sidebar-item icon="heroicon-o-book-open" label="Activity Checkin" activeRoute="activity-checkin" />
        </ul>
    </nav>
</aside>
