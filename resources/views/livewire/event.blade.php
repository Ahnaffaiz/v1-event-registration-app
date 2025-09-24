@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

<div class="container px-6 mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <!-- Action Button -->
            <div class="flex justify-end mb-6">
                @if($event)
                    <button wire:click="openEditEventModal"
                            class="px-6 py-3 font-medium text-white transition-colors bg-blue-600 rounded-xl hover:bg-blue-700">
                        Edit Event
                    </button>
                @else
                    <button wire:click="openCreateEventModal"
                            class="px-6 py-3 font-medium text-white transition-colors bg-green-600 rounded-xl hover:bg-green-700">
                        Create Event
                    </button>
                @endif
            </div>

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-400 rounded-xl">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 mb-4 border bg-rose-100 border-rose-400 text-rose-700 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tab Navigation -->
            @if($event)
                <div class="mb-6">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <button wire:click="setActiveTab('event')"
                                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                {{ $activeTab === 'event' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Event Details
                        </button>
                        <button wire:click="setActiveTab('activity')"
                                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                {{ $activeTab === 'activity' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Activities
                        </button>
                        <button wire:click="setActiveTab('guest')"
                                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                {{ $activeTab === 'guest' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Guests
                        </button>
                    </nav>
                </div>
            @endif

            <!-- Tab Content -->
            @if($event)
                <div class="transition-all duration-300 ease-in-out">
                    <!-- Event Details Tab -->
                    @if($activeTab === 'event')
                        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg animate-fade-in">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <!-- Event Image -->
                                @if($event->image)
                                    <div>
                                        <img src="{{ Storage::url($event->image) }}"
                                             alt="{{ $event->name }}"
                                             class="object-cover w-full h-64 rounded-lg shadow-md">
                                    </div>
                                @endif

                                <!-- Event Details -->
                                <div class="space-y-4">
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h2>
                                        <div class="mt-2 prose-sm prose text-gray-600 max-w-none">
                                            {!! $event->desc !!}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div>
                                            <span class="font-semibold text-gray-700">Host:</span>
                                            <p class="text-gray-600">{{ $event->host->name }}</p>
                                        </div>

                                        <div>
                                            <span class="font-semibold text-gray-700">Status:</span>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                {{ $event->status === 'active' ? 'bg-green-100 text-green-800' :
                                                   ($event->status === 'inactive' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                        </div>

                                        <div>
                                            <span class="font-semibold text-gray-700">Registration:</span>
                                            <p class="text-sm text-gray-600">
                                                {{ $event->registration_start_date->format('M d, Y H:i') }} -
                                                {{ $event->registration_end_date->format('M d, Y H:i') }}
                                            </p>
                                        </div>

                                        <div>
                                            <span class="font-semibold text-gray-700">Event Date:</span>
                                            <p class="text-sm text-gray-600">
                                                {{ $event->start_date->format('M d, Y H:i') }} -
                                                {{ $event->end_date->format('M d, Y H:i') }}
                                            </p>
                                        </div>

                                        @if($event->ticket_price)
                                            <div>
                                                <span class="font-semibold text-gray-700">Ticket Price:</span>
                                                <p class="text-gray-600">Rp {{ number_format($event->ticket_price, 0, ',', '.') }}</p>
                                            </div>
                                        @endif

                                        @if($event->capacity)
                                            <div>
                                                <span class="font-semibold text-gray-700">Capacity:</span>
                                                <p class="text-gray-600">{{ $event->capacity }} people</p>
                                            </div>
                                        @endif

                                        @if($event->short_link)
                                            <div>
                                                <span class="font-semibold text-gray-700">Short Link:</span>
                                                <p class="text-blue-600">{{ $event->short_link }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Tags & Categories -->
                                    @if($event->tags->count() > 0 || $event->categories->count() > 0)
                                        <div class="pt-4 border-t border-gray-200">
                                            @if($event->tags->count() > 0)
                                                <div class="mb-3">
                                                    <span class="font-semibold text-gray-700">Tags:</span>
                                                    <div class="flex flex-wrap gap-2 mt-1">
                                                        @foreach($event->tags as $tag)
                                                            <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">{{ $tag->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($event->categories->count() > 0)
                                                <div>
                                                    <span class="font-semibold text-gray-700">Categories:</span>
                                                    <div class="flex flex-wrap gap-2 mt-1">
                                                        @foreach($event->categories as $category)
                                                            <span class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full">{{ $category->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Delete Button -->
                            <div class="pt-6 mt-6 border-t border-gray-200">
                                <button wire:click="delete"
                                        onclick="return confirm('Are you sure you want to delete this event? This action cannot be undone.')"
                                        class="px-6 py-2 text-white transition-colors bg-rose-600 rounded-xl hover:bg-rose-700">
                                    Delete Event
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Activities Tab -->
                    @if($activeTab === 'activity')
                        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg animate-fade-in">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-semibold text-gray-900">Event Activities</h3>
                                <button type="button" wire:click="openActivityModal"
                                        class="px-4 py-2 text-sm text-white transition-colors bg-green-600 rounded-xl hover:bg-green-700">
                                    Add Activity
                                </button>
                            </div>

                            @if(count($activities) > 0)
                                <div class="overflow-hidden bg-white border border-gray-200 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Capacity</th>
                                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Price</th>
                                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($activities as $index => $activity)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                        {{ $activity['name'] }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        {{ isset($activity['desc']) ? Str::limit($activity['desc'], 50) : '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        @if(isset($activity['start_date']) && isset($activity['end_date']))
                                                            {{ \Carbon\Carbon::parse($activity['start_date'])->format('M d, Y') }}
                                                            <br>
                                                            <span class="text-xs text-gray-400">
                                                                {{ \Carbon\Carbon::parse($activity['start_date'])->format('H:i') }} -
                                                                {{ \Carbon\Carbon::parse($activity['end_date'])->format('H:i') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        {{ $activity['capacity'] ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        @if(isset($activity['ticket_price']) && $activity['ticket_price'])
                                                            Rp {{ number_format($activity['ticket_price'], 0, ',', '.') }}
                                                        @else
                                                            Free
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        <div class="flex gap-2">
                                                            <button type="button" wire:click="editActivity({{ $index }})"
                                                                    class="text-blue-600 transition-colors hover:text-blue-800">
                                                                Edit
                                                            </button>
                                                            <button type="button" wire:click="removeActivity({{ $index }})"
                                                                    wire:confirm="Are you sure you want to remove this activity?"
                                                                    class="transition-colors text-rose-600 hover:text-rose-800">
                                                                Remove
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="py-12 text-center border border-gray-200 bg-gray-50 rounded-xl">
                                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-gray-300 rounded-full">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <h3 class="mb-2 text-lg font-medium text-gray-900">No activities yet</h3>
                                    <p class="mb-4 text-gray-500">Get started by adding your first activity.</p>
                                    <button type="button" wire:click="openActivityModal"
                                            class="px-4 py-2 text-sm text-white transition-colors bg-green-600 rounded-xl hover:bg-green-700">
                                        Add Activity
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Guests Tab -->
                    @if($activeTab === 'guest')
                        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg animate-fade-in">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-semibold text-gray-900">Event Guests</h3>
                                <button type="button" wire:click="openGuestModal"
                                        class="px-4 py-2 text-sm text-white transition-colors bg-blue-600 rounded-xl hover:bg-blue-700">
                                    Manage Guests
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <!-- Guest Statistics -->
                                <div class="p-4 bg-blue-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-blue-600">Total Registered</p>
                                            <p class="text-2xl font-bold text-blue-900">{{ $event->eventTickets->count() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 bg-green-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-green-100 rounded-lg">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-green-600">Checked In</p>
                                            <p class="text-2xl font-bold text-green-900">{{ $event->eventCheckins->count() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 bg-amber-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-lg bg-amber-100">
                                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-amber-600">Pending</p>
                                            <p class="text-2xl font-bold text-amber-900">{{ $event->eventTickets->count() - $event->eventCheckins->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Guests -->
                            @if($event->eventTickets->count() > 0)
                                <div class="mt-6">
                                    <h4 class="mb-4 text-lg font-medium text-gray-900">Recent Registrations</h4>
                                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                                                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Registration Date</th>
                                                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($event->eventTickets->take(5) as $ticket)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                            {{ $ticket->name }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            {{ $ticket->email }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            {{ $ticket->created_at->format('M d, Y H:i') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            @if($ticket->eventCheckin)
                                                                <span class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full">Checked In</span>
                                                            @else
                                                                <span class="px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800">Registered</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="py-12 mt-6 text-center border border-gray-200 bg-gray-50 rounded-xl">
                                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-gray-300 rounded-full">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="mb-2 text-lg font-medium text-gray-900">No guests yet</h3>
                                    <p class="text-gray-500">Guests will appear here once they register for your event.</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Event Form (Legacy - now using modals) -->
            @if($showForm && !$showEventModal)
                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Event Name</label>
                                <input type="text" id="name" wire:model="name"
                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                @error('name') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Event Image</label>
                                <input type="file" id="image" wire:model="image" accept="image/*"
                                       class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('image') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                @if ($image)
                                    <div class="mt-2">
                                        <img src="{{ $image->temporaryUrl() }}" class="object-cover w-32 h-32 rounded-xl">
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label for="desc" class="block text-sm font-medium text-gray-700">Description</label>
                                <div wire:ignore>
                                    <textarea id="desc" wire:model="desc" rows="6"
                                              class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                @error('desc') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Host and Settings -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Host & Settings</h3>

                            <div>
                                <div class="flex items-center justify-between">
                                    <label for="host_id" class="block text-sm font-medium text-gray-700">Host</label>
                                    <button type="button" wire:click="toggleCreateHost"
                                            class="text-sm text-blue-600 hover:text-blue-800">Create New Host</button>
                                </div>
                                <select id="host_id" wire:model="host_id"
                                        class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a host</option>
                                    @foreach($hosts as $host)
                                        <option value="{{ $host->id }}">{{ $host->name }}</option>
                                    @endforeach
                                </select>
                                @error('host_id') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Create Host Modal -->
                            @if($showCreateHost)
                                <div class="p-4 border bg-gray-50 rounded-xl">
                                    <h4 class="mb-3 font-medium text-gray-900">Create New Host</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Host Name</label>
                                            <input type="text" wire:model="host_name"
                                                   class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                            @error('host_name') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea wire:model="host_desc" rows="2"
                                                      class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500"></textarea>
                                            @error('host_desc') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Website</label>
                                            <input type="url" wire:model="host_web" placeholder="https://example.com"
                                                   class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                            @error('host_web') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="button" wire:click="createHost"
                                                    class="px-3 py-1 text-sm text-white bg-green-600 rounded-xl hover:bg-green-700">
                                                Create Host
                                            </button>
                                            <button type="button" wire:click="toggleCreateHost"
                                                    class="px-3 py-1 text-sm text-gray-700 bg-gray-300 rounded-xl hover:bg-gray-400">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="ticket_price" class="block text-sm font-medium text-gray-700">Ticket Price</label>
                                    <input type="number" id="ticket_price" wire:model="ticket_price" min="0" step="0.01"
                                           class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                    @error('ticket_price') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                    <input type="number" id="capacity" wire:model="capacity" min="1"
                                           class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                    @error('capacity') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" wire:model="status"
                                        class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="short_link" class="block text-sm font-medium text-gray-700">Short Link</label>
                                <input type="text" id="short_link" wire:model="short_link"
                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                @error('short_link') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="require_approval" class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Require Approval</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_public" class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Public Event</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Date Times -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Registration Period</h3>

                            <div>
                                <label for="registration_start_date" class="block text-sm font-medium text-gray-700">Registration Start</label>
                                <input type="datetime-local" id="registration_start_date" wire:model="registration_start_date"
                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                @error('registration_start_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="registration_end_date" class="block text-sm font-medium text-gray-700">Registration End</label>
                                <input type="datetime-local" id="registration_end_date" wire:model="registration_end_date"
                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                @error('registration_end_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Event Period</h3>

                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Event Start</label>
                                <input type="datetime-local" id="start_date" wire:model="start_date"
                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                @error('start_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Event End</label>
                                <input type="datetime-local" id="end_date" wire:model="end_date"
                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                @error('end_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tags and Categories -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                            <div class="mt-2 space-y-2 overflow-y-auto max-h-32">
                                @foreach($tags as $tag)
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}"
                                               class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
                            <div class="mt-2 space-y-2 overflow-y-auto max-h-32">
                                @foreach($categories as $category)
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
                                               class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Activities -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Activities</h3>
                            <button type="button" wire:click="openActivityModal"
                                    class="px-3 py-1 text-sm text-white bg-green-600 rounded-xl hover:bg-green-700">
                                Add Activity
                            </button>
                        </div>

                        @if(count($activities) > 0)
                            <div class="overflow-hidden bg-white border border-gray-200 rounded-xl">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Capacity</th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Price</th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($activities as $index => $activity)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                    {{ $activity['name'] ?? 'Unnamed Activity' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">
                                                    {{ Str::limit($activity['desc'] ?? '', 50) }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">
                                                    @if(isset($activity['start_date']) && isset($activity['end_date']))
                                                        {{ \Carbon\Carbon::parse($activity['start_date'])->format('M d, Y') }}
                                                        <br>
                                                        <span class="text-xs text-gray-400">
                                                            {{ \Carbon\Carbon::parse($activity['start_date'])->format('H:i') }} -
                                                            {{ \Carbon\Carbon::parse($activity['end_date'])->format('H:i') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">
                                                    {{ $activity['capacity'] ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">
                                                    @if(isset($activity['ticket_price']) && $activity['ticket_price'])
                                                        Rp {{ number_format($activity['ticket_price'], 0, ',', '.') }}
                                                    @else
                                                        Free
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">
                                                    <div class="flex gap-2">
                                                        <button type="button" wire:click="editActivity({{ $index }})"
                                                                class="text-blue-600 hover:text-blue-800">
                                                            Edit
                                                        </button>
                                                        <button type="button" wire:click="removeActivity({{ $index }})"
                                                                wire:confirm="Are you sure you want to remove this activity?"
                                                                class="text-rose-600 hover:text-rose-800">
                                                            Remove
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-8 text-center border border-gray-200 bg-gray-50 rounded-xl">
                                <p class="text-gray-500">No activities added yet. Click "Add Activity" to get started.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-xl hover:bg-blue-700">
                            {{ $isEditing ? 'Update Event' : 'Create Event' }}
                        </button>
                        <button type="button" wire:click="toggleForm"
                                class="px-6 py-2 text-gray-700 transition-colors bg-gray-300 rounded-xl hover:bg-gray-400">
                            Cancel
                        </button>
                    </div>
                </form>
            @endif

            <!-- Activity Modal -->
            @if($showActivityModal)
                <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" wire:click="closeActivityModal"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                        {{ $editingActivityIndex !== null ? 'Edit Activity' : 'Add Activity' }}
                                    </h3>
                                    <button wire:click="closeActivityModal" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Activity Name</label>
                                        <input type="text" wire:model="currentActivity.name"
                                               class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                        @error('currentActivity.name') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea wire:model="currentActivity.desc" rows="3"
                                                  class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500"></textarea>
                                        @error('currentActivity.desc') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                            <input type="datetime-local" wire:model="currentActivity.start_date"
                                                   class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                            @error('currentActivity.start_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                                            <input type="datetime-local" wire:model="currentActivity.end_date"
                                                   class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                            @error('currentActivity.end_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Capacity</label>
                                            <input type="number" wire:model="currentActivity.capacity" min="1"
                                                   class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                            @error('currentActivity.capacity') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Ticket Price</label>
                                            <input type="number" wire:model="currentActivity.ticket_price" min="0" step="0.01"
                                                   class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                            @error('currentActivity.ticket_price') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button wire:click="saveActivity"
                                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ $editingActivityIndex !== null ? 'Update' : 'Add' }} Activity
                                </button>
                                <button wire:click="closeActivityModal"
                                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Event Modal -->
            @if($showEventModal)
                <div class="fixed inset-0 z-50 overflow-y-auto animate-fade-in" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 animate-fade-in" aria-hidden="true" wire:click="closeEventModal"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl animate-scale-in sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                        {{ $isEditing ? 'Edit Event' : 'Create Event' }}
                                    </h3>
                                    <button wire:click="closeEventModal" class="text-gray-400 transition-colors hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <form wire:submit.prevent="save" class="space-y-6">
                                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                        <!-- Basic Information -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-gray-900 text-md">Basic Information</h4>

                                            <div>
                                                <label for="name" class="block text-sm font-medium text-gray-700">Event Name</label>
                                                <input type="text" id="name" wire:model="name"
                                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                @error('name') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="image" class="block text-sm font-medium text-gray-700">Event Image</label>
                                                <input type="file" id="image" wire:model="image" accept="image/*"
                                                       class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                @error('image') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                                @if ($image)
                                                    <div class="mt-2">
                                                        <img src="{{ $image->temporaryUrl() }}" class="object-cover w-32 h-32 rounded-xl">
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                <label for="desc" class="block text-sm font-medium text-gray-700">Description</label>
                                                <div wire:ignore>
                                                    <textarea id="desc-modal" wire:model="desc" rows="4"
                                                              class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500"></textarea>
                                                </div>
                                                @error('desc') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Host and Settings -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-gray-900 text-md">Host & Settings</h4>

                                            <div>
                                                <label for="host_id" class="block text-sm font-medium text-gray-700">Host</label>
                                                <select id="host_id" wire:model="host_id"
                                                        class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">Select a host</option>
                                                    @foreach($hosts as $host)
                                                        <option value="{{ $host->id }}">{{ $host->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('host_id') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label for="ticket_price" class="block text-sm font-medium text-gray-700">Ticket Price</label>
                                                    <input type="number" id="ticket_price" wire:model="ticket_price" min="0" step="0.01"
                                                           class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                    @error('ticket_price') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                                </div>

                                                <div>
                                                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                                    <input type="number" id="capacity" wire:model="capacity" min="1"
                                                           class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                    @error('capacity') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                                <select id="status" wire:model="status"
                                                        class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                                @error('status') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="short_link" class="block text-sm font-medium text-gray-700">Short Link</label>
                                                <input type="text" id="short_link" wire:model="short_link"
                                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                @error('short_link') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox" wire:model="require_approval" class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-gray-700">Require Approval</span>
                                                </label>

                                                <label class="flex items-center">
                                                    <input type="checkbox" wire:model="is_public" class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-gray-700">Public Event</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Date Times -->
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-gray-900 text-md">Registration Period</h4>

                                            <div>
                                                <label for="registration_start_date" class="block text-sm font-medium text-gray-700">Registration Start</label>
                                                <input type="datetime-local" id="registration_start_date" wire:model="registration_start_date"
                                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                @error('registration_start_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="registration_end_date" class="block text-sm font-medium text-gray-700">Registration End</label>
                                                <input type="datetime-local" id="registration_end_date" wire:model="registration_end_date"
                                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                @error('registration_end_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-gray-900 text-md">Event Period</h4>

                                            <div>
                                                <label for="start_date" class="block text-sm font-medium text-gray-700">Event Start</label>
                                                <input type="datetime-local" id="start_date" wire:model="start_date"
                                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                @error('start_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label for="end_date" class="block text-sm font-medium text-gray-700">Event End</label>
                                                <input type="datetime-local" id="end_date" wire:model="end_date"
                                                       class="block w-full mt-1 border-gray-300 shadow-sm rounded-xl focus:border-blue-500 focus:ring-blue-500">
                                                @error('end_date') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white transition-colors bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            {{ $isEditing ? 'Update Event' : 'Create Event' }}
                                        </button>
                                        <button type="button" wire:click="closeEventModal"
                                                class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Guest Modal -->
            @if($showGuestModal)
                <div class="fixed inset-0 z-50 overflow-y-auto animate-fade-in" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 animate-fade-in" aria-hidden="true" wire:click="closeGuestModal"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl animate-scale-in sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                        Manage Event Guests
                                    </h3>
                                    <button wire:click="closeGuestModal" class="text-gray-400 transition-colors hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-6">
                                    <!-- Guest Statistics -->
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                        <div class="p-4 bg-blue-50 rounded-xl">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-blue-100 rounded-lg">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-500">Total Guests</p>
                                                    <p class="text-lg font-semibold text-blue-600">{{ $event->eventTickets->count() }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-4 bg-green-50 rounded-xl">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-green-100 rounded-lg">
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-500">Checked In</p>
                                                    <p class="text-lg font-semibold text-green-600">{{ $event->eventCheckins->count() }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-4 bg-yellow-50 rounded-xl">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-yellow-100 rounded-lg">
                                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-500">Not Checked In</p>
                                                    <p class="text-lg font-semibold text-yellow-600">{{ $event->eventTickets->count() - $event->eventCheckins->count() }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-4 bg-blue-50 rounded-xl">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-blue-100 rounded-lg">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-500">Attendance Rate</p>
                                                    <p class="text-lg font-semibold text-blue-600">
                                                        {{ $event->eventTickets->count() > 0 ? round(($event->eventCheckins->count() / $event->eventTickets->count()) * 100) : 0 }}%
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guest List -->
                                    <div class="bg-white border border-gray-200 rounded-lg">
                                        <div class="px-4 py-5 sm:p-6">
                                            <h4 class="mb-4 text-lg font-medium text-gray-900">Guest List</h4>
                                            @if($event->eventTickets->count() > 0)
                                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                                    <table class="min-w-full divide-y divide-gray-300">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Registered</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($event->eventTickets as $ticket)
                                                                <tr>
                                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $ticket->name }}</td>
                                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $ticket->email }}</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        @php
                                                                            $isCheckedIn = $event->eventCheckins->where('event_ticket_id', $ticket->id)->first();
                                                                        @endphp
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                            {{ $isCheckedIn ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                            {{ $isCheckedIn ? 'Checked In' : 'Registered' }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $ticket->created_at->format('M d, Y') }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="py-8 text-center">
                                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No guests yet</h3>
                                                    <p class="mt-1 text-sm text-gray-500">Get started by inviting guests to your event.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="button" wire:click="closeGuestModal"
                                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- CSS Animations -->
    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .animate-scale-in {
            animation: scale-in 0.3s ease-out;
        }
    </style>

    <!-- TinyMCE Script -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TinyMCE
            function initTinyMCE() {
                tinymce.init({
                    selector: 'textarea[wire\\:model="desc"]',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    height: 200,
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                            @this.set('desc', editor.getContent());
                        });
                    },
                    init_instance_callback: function (editor) {
                        editor.setContent(@this.get('desc') || '');
                    }
                });
            }

            // Initialize on page load
            initTinyMCE();

            // Reinitialize when modals open
            window.addEventListener('modal-opened', function() {
                setTimeout(function() {
                    tinymce.remove();
                    initTinyMCE();
                }, 100);
            });

            // Clean up when modals close
            window.addEventListener('modal-closed', function() {
                tinymce.remove();
                initTinyMCE();
            });
        });
    </script>
</div>
