<?php

namespace App\Livewire;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Event as EventModel;
use App\Models\Host;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Event extends Component
{
    use WithFileUploads;

    #[Title('Event Management')]

    // Form Properties
    public $eventId;
    public $name = '';
    public $image;
    public $desc = '';
    public $registration_start_date = '';
    public $registration_end_date = '';
    public $start_date = '';
    public $end_date = '';
    public $ticket_price = 0;
    public $require_approval = false;
    public $is_public = true;
    public $short_link = '';
    public $capacity;
    public $status = 'active';
    public $host_id;

    // Host form properties
    public $host_name = '';
    public $host_desc = '';
    public $host_web = '';

    // Selected tags and categories
    public $selectedTags = [];
    public $selectedCategories = [];

    // Activities
    public $activities = [];

    // Activity Modal
    public $showActivityModal = false;
    public $currentActivity = [
        'name' => '',
        'desc' => '',
        'start_date' => '',
        'end_date' => '',
        'capacity' => '',
        'ticket_price' => 0
    ];
    public $editingActivityIndex = null;

    // UI States
    public $showForm = false;
    public $isEditing = false;
    public $showCreateHost = false;
    public $activeTab = 'event';
    public $showEventModal = false;
    public $showGuestModal = false;

    // Collections
    public $hosts;
    public $tags;
    public $categories;
    public $event;

    #[Title('Event Management')]

    public function mount()
    {
        $this->loadCollections();
        $this->loadEvent();
        $this->initializeActivities();
    }

    public function loadCollections()
    {
        $this->hosts = Host::all();
        $this->tags = Tag::all();
        $this->categories = Category::all();
    }

    public function loadEvent()
    {
        $this->event = EventModel::with(['activities', 'tags', 'categories', 'host', 'eventTickets', 'eventCheckins'])->first();

        if ($this->event) {
            $this->eventId = $this->event->id;
            $this->name = $this->event->name;
            $this->desc = $this->event->desc;
            $this->registration_start_date = $this->event->registration_start_date?->format('Y-m-d\TH:i');
            $this->registration_end_date = $this->event->registration_end_date?->format('Y-m-d\TH:i');
            $this->start_date = $this->event->start_date?->format('Y-m-d\TH:i');
            $this->end_date = $this->event->end_date?->format('Y-m-d\TH:i');
            $this->ticket_price = $this->event->ticket_price;
            $this->require_approval = $this->event->require_approval;
            $this->is_public = $this->event->is_public;
            $this->short_link = $this->event->short_link;
            $this->capacity = $this->event->capacity;
            $this->status = $this->event->status;
            $this->host_id = $this->event->host_id;

            // Load selected tags and categories
            $this->selectedTags = $this->event->tags->pluck('id')->toArray();
            $this->selectedCategories = $this->event->categories->pluck('id')->toArray();

            // Load activities
            $this->activities = $this->event->activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'desc' => $activity->desc,
                    'start_date' => $activity->start_date ? $activity->start_date->format('Y-m-d\TH:i') : '',
                    'end_date' => $activity->end_date ? $activity->end_date->format('Y-m-d\TH:i') : '',
                    'capacity' => $activity->capacity,
                    'ticket_price' => $activity->ticket_price,
                ];
            })->toArray();
        }
    }

    public function initializeActivities()
    {
        if (empty($this->activities)) {
            $this->activities = [];
        }
    }

    public function addActivity()
    {
        $this->activities[] = [
            'id' => null,
            'name' => '',
            'desc' => '',
            'start_date' => '',
            'end_date' => '',
            'capacity' => null,
            'ticket_price' => 0,
        ];
    }

    public function removeActivity($index)
    {
        unset($this->activities[$index]);
        $this->activities = array_values($this->activities);
    }

    // New modal-based activity methods
    public function openActivityModal()
    {
        $this->resetCurrentActivity();
        $this->editingActivityIndex = null;
        $this->showActivityModal = true;
    }

    public function editActivity($index)
    {
        $this->currentActivity = $this->activities[$index];
        $this->editingActivityIndex = $index;
        $this->showActivityModal = true;
    }

    public function closeActivityModal()
    {
        $this->showActivityModal = false;
        $this->resetCurrentActivity();
        $this->editingActivityIndex = null;
    }

    public function saveActivity()
    {
        $this->validate([
            'currentActivity.name' => 'required|string|max:255',
            'currentActivity.desc' => 'nullable|string',
            'currentActivity.start_date' => 'required|date',
            'currentActivity.end_date' => 'required|date|after:currentActivity.start_date',
            'currentActivity.capacity' => 'nullable|integer|min:1',
            'currentActivity.ticket_price' => 'nullable|numeric|min:0',
        ]);

        if ($this->editingActivityIndex !== null) {
            // Update existing activity
            $this->activities[$this->editingActivityIndex] = $this->currentActivity;
        } else {
            // Add new activity
            $this->activities[] = $this->currentActivity;
        }

        $this->closeActivityModal();
        session()->flash('message', 'Activity ' . ($this->editingActivityIndex !== null ? 'updated' : 'added') . ' successfully!');
    }

    private function resetCurrentActivity()
    {
        $this->currentActivity = [
            'name' => '',
            'desc' => '',
            'start_date' => '',
            'end_date' => '',
            'capacity' => '',
            'ticket_price' => 0
        ];
    }

    // Tab Management
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Event Modal Methods
    public function openCreateEventModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showEventModal = true;
        $this->dispatch('modal-opened');
    }

    public function openEditEventModal()
    {
        $this->isEditing = true;
        $this->showEventModal = true;
        $this->dispatch('modal-opened');
    }

    public function closeEventModal()
    {
        $this->showEventModal = false;
        $this->dispatch('modal-closed');
    }

    // Guest Modal Methods
    public function openGuestModal()
    {
        $this->showGuestModal = true;
        $this->dispatch('modal-opened');
    }

    public function closeGuestModal()
    {
        $this->showGuestModal = false;
        $this->dispatch('modal-closed');
    }

    public function render()
    {
        return view('livewire.event');
    }
}
