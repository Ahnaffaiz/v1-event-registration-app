<?php

namespace App\Livewire\Display;

use App\Models\Event;
use App\Models\EventCheckin as ModelsEventCheckin;
use App\Models\EventTicket;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class EventCheckin extends Component
{

    public $activeTab = 'scanner', $checkinStatus, $participant, $event, $checkedIn;
    #[Title('Event Check In')]

    public function mount()
    {
        $this->event = Event::first();
    }
    public function render()
    {
        $this->checkedIn = ModelsEventCheckin::count();
        return view('livewire.display.event-checkin');
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('tabChanged', tabStatus: $tab);
    }

    #[On('processCheckin')]
    public function processCheckin($data)
    {
        $eventTicket = EventTicket::where('qr_code', $data)->first();
        $this->participant = $eventTicket;
        if(!$eventTicket) {
            $this->checkinStatus = 'failed';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
            return;
        }

        $existingCheckin = ModelsEventCheckin::where('event_ticket_id', $eventTicket->id)->first();
        if ($existingCheckin) {
            $this->checkinStatus = 'existing';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        } else {
            ModelsEventCheckin::create([
                'event_ticket_id' => $eventTicket->id,
                'checkin_time' => now(),
            ]);
            $this->checkinStatus = 'success';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        }
    }
}
