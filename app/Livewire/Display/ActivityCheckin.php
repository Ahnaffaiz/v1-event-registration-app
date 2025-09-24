<?php

namespace App\Livewire\Display;

use App\Models\Activity;
use App\Models\ActivityTicket;
use App\Models\Event;
use App\Models\ActivityCheckin as ModelsActivityCheckin;
use App\Models\EventTicket;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class ActivityCheckin extends Component
{

    public $activeTab = 'scanner', $checkinStatus, $participant;
    public $activity, $activity_id;

    #[Title('Event Check In')]

    public function mount()
    {
        $this->activity_id = request()->activity;
        $this->activity = Activity::where('id', $this->activity_id)->first();
    }
    public function render()
    {
        return view('livewire.display.activity-checkin');
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('tabChanged', tabStatus: $tab);
    }

    #[On('processCheckin')]
    public function processCheckin($data)
    {
        $activityTicket = ActivityTicket::where('qr_code', $data)->where('activity_id', $this->activity_id)->first();
        $this->participant = $activityTicket?->eventTicket;
        if(!$activityTicket) {
            $this->checkinStatus = 'failed';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
            return;
        }

        $existingCheckin = ModelsActivityCheckin::where('activity_ticket_id', $activityTicket->id)->first();
        if ($existingCheckin) {
            $this->checkinStatus = 'existing';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        } else {
            ModelsActivityCheckin::create([
                'activity_ticket_id' => $activityTicket->id,
                'checkin_time' => now(),
            ]);
            $this->checkinStatus = 'success';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        }
    }
}
