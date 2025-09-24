<?php

namespace App\Livewire;

use App\Models\ActivityTicket;
use App\Models\Event;
use App\Models\EventCheckin;
use App\Models\EventTicket;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class MultipleCheckin extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $activeTab = 'scanner', $checkinStatus, $participant, $event, $checkedIn, $activityTicket, $scannerStatus = 'event';

    #[Title('Multi Check In Event')]
    public function mount()
    {
        $this->event = Event::first();
        $this->activityTicket = ActivityTicket::whereIn('activity_id', $this->event->activities->pluck('id'))->get();
    }
    public function render()
    {
        $this->checkedIn = EventCheckin::count();
        return view('livewire.multiple-checkin');
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function checkInEvent($data)
    {
        $eventTicket = EventTicket::where('qr_code', $data)->first();
        $this->participant = $eventTicket;
        if(!$eventTicket) {
            $this->checkinStatus = 'failed';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
            return;
        }

        $existingCheckin = EventCheckin::where('event_ticket_id', $eventTicket->id)->first();
        if ($existingCheckin) {
            if($this->participant->activityTickets->count() === 0) {
                $this->checkinStatus = 'activity not found';
                $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
            } else {
                if($this->participant->activityTickets->first()->qr_code != null) {
                    $this->checkinStatus = 'existing';
                    $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
                } else {
                    $this->checkinStatus = 'success';
                    $this->scannerStatus = 'activity';
                    $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
                }
            }
        } else {
            EventCheckin::create([
                'event_ticket_id' => $eventTicket->id,
                'checkin_time' => now(),
            ]);
            $this->checkinStatus = 'success';
            $this->scannerStatus = 'activity';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        }
    }

    public function generateActivityTicket($data)
    {
        if($this->participant->activityTickets->count() === 0) {
            $this->checkinStatus = 'activity not found';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        } else {
            foreach ($this->participant->activityTickets as $activityTicket) {
                $activityTicket->update([
                    'qr_code' => $data,
                ]);
            }
            $this->checkinStatus = 'ticket paired';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        }
        $this->activityTicket = ActivityTicket::whereIn('activity_id', $this->event->activities->pluck('id'))->get();
        $this->scannerStatus = 'event';

    }

    #[On('processCheckin')]
    public function processCheckin($data)
    {
        if($this->scannerStatus === 'event') {
            $this->checkinEvent($data);
        } else {
            $this->generateActivityTicket($data);
        }
    }

    public function resetPairedTicket()
    {
        $this->participant->activityTickets->each(function($activityTicket) {
            $activityTicket->update([
                'qr_code' => null,
            ]);
        });
        $this->scannerStatus = 'activity';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(EventTicket::query())
            ->columns([
                TextColumn::make('qr_code')
                    ->label('Ticket Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('occupation')
                    ->label('Occupation')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('id')
                    ->label('Check In')
                    ->icon(function ($record) {
                        $checkinExists = EventCheckin::where('event_ticket_id', $record->id)->exists();
                        return $checkinExists ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                    })
                    ->color(function ($record) {
                        $checkinExists = EventCheckin::where('event_ticket_id', $record->id)->exists();
                        return $checkinExists ? 'success' : 'danger';
                    }),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('checkin')
                    ->label('Check In')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->form([
                        Select::make('status')
                            ->label('Check In Status')
                            ->options([
                                'checkin' => 'Check In',
                                'checkout' => 'Check Out',
                            ])
                            ->default(function($record) {
                                $checkinExists = EventCheckin::where('event_ticket_id', $record->id)->exists();
                                return $checkinExists ? 'checkin' : 'checkout';
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, EventTicket $record): void {
                        if($data['status'] === 'checkin') {
                            EventCheckin::firstOrCreate([
                                'event_ticket_id' => $record->id,
                                'checkin_time' => now(),
                            ]);
                        } else {
                            EventCheckin::where('event_ticket_id', $record->id)->delete();
                        }
                    })->modalWidth('sm'),
            ]);
    }
}
