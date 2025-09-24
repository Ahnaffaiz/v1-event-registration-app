<?php

namespace App\Filament\Resources\EventTicketResource\Pages;

use App\Filament\Resources\EventTicketResource;
use App\Models\ActivityTicket;
use App\Models\EventTicket;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditEventTicket extends EditRecord
{
    protected static string $resource = EventTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function handleRecordUpdate(Model $record, array $data): Model
    {
        $newActvityData = $data['activityTickets'];
        // dd($newActvityData);
        unset($data['activityTickets']);
        $record->update($data);
        $existingActivityTickets = $record->activityTickets?->pluck('activity_id')->toArray();
        // dd($existingActivityTickets);
        if (isset($newActvityData) && is_array($newActvityData)) {
            $activitiesToCreate = array_diff($newActvityData, $existingActivityTickets);
            foreach ($activitiesToCreate as $activityId) {
                ActivityTicket::create([
                    'event_ticket_id' => $record->id,
                    'activity_id' => $activityId,
                ]);
            }

            $activitiesToDelete = array_diff($existingActivityTickets, $newActvityData);
            foreach ($activitiesToDelete as $activityId) {
                ActivityTicket::where('event_ticket_id', $record->id)
                    ->where('activity_id', $activityId)
                    ->delete();
            }
        }

        return $record;
    }
}