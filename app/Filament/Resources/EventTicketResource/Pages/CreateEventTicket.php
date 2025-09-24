<?php

namespace App\Filament\Resources\EventTicketResource\Pages;

use App\Filament\Resources\EventTicketResource;
use App\Models\ActivityTicket;
use App\Models\Event;
use App\Models\EventTicket;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateEventTicket extends CreateRecord
{
    protected static string $resource = EventTicketResource::class;

    public function handleRecordCreation(array $data): Model
    {
        $data['created_by'] = Auth::user()->id;
        $uniqueString = Str::random(16);
        $data['qr_code'] = $uniqueString;
        $qrCode = new QrCode($uniqueString, new Encoding('UTF-8'),
            ErrorCorrectionLevel::Low, 300, 10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Save the QR code image to the storage
        $fileName = 'qrcode/' . $data['event_id'] . '-' .$data['name'] . '.png';
        Storage::disk('public')->put($fileName, $result->getString());
        $data['qr_code_path'] = 'storage/' . $fileName;

        $activities = $data['activityTickets'];
        unset($data['activityTickets']);
        $eventTicket = EventTicket::create($data);
        if (isset($activities) && is_array($activities)) {
            foreach ($activities as $activityId) {
                ActivityTicket::create([
                    'event_ticket_id' => $eventTicket->id,
                    'activity_id' => $activityId,
                ]);
            }
        }

        return $eventTicket;
    }
}