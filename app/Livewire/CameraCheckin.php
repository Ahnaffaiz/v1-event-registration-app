<?php

namespace App\Livewire;

use App\Models\EventCheckin;
use App\Models\EventTicket;
use Livewire\Attributes\On;
use Livewire\Component;

class CameraCheckin extends Component
{

    public $checkinStatus, $participant;

    public function render()
    {
        return view('livewire.camera-checkin');
    }

    #[On('processCheckin')]
    public function processCheckin($data)
    {
        $eventTicket = EventTicket::where('qr_code', $data)->first();
        $this->participant = $eventTicket;
        if(!$eventTicket) {
            $this->checkinStatus = 'failed';
            return;
        }

        $existingCheckin = EventCheckin::where('event_ticket_id', $eventTicket->id)->first();
        if ($existingCheckin) {
            $this->checkinStatus = 'existing';
        } else {
            EventCheckin::create([
                'event_ticket_id' => $eventTicket->id,
                'checkin_time' => now(),
            ]);
            $this->checkinStatus = 'success';
        }
    }
}