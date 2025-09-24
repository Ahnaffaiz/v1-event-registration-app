<?php

namespace App\Livewire;

use App\Models\EventCheckin;
use App\Models\EventTicket;
use Livewire\Component;

class QRCodeCheckin extends Component
{
    public function render()
    {
        return view('livewire.qr-code-checkin');
    }

    public function processCheckin($data)
    {
        $eventTicket = EventTicket::where('qr_code', $data)->first();
        if(!$eventTicket) {
            session()->flash('error', 'Tiket tidak ditemukan.');
            return;
        }

        $existingCheckin = EventCheckin::where('event_ticket_id', $eventTicket->id)->first();
        if ($existingCheckin) {
            session()->flash('info', 'Anda sudah melakukan check-in untuk tiket ini.');
        } else {
            EventCheckin::create([
                'event_ticket_id' => $eventTicket->id,
                'checkin_time' => now(),
            ]);
            session()->flash('success', 'Check-in berhasil.');
        }
    }
}