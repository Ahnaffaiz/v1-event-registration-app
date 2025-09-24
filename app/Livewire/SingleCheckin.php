<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventCheckin;
use App\Models\EventTicket;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class SingleCheckin extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $activeTab = 'scanner', $checkinStatus, $participant, $event, $checkedIn;
    #[Title('Check In Single Event')]

    public function mount()
    {
        $this->event = Event::first();
    }
    public function render()
    {
        $this->checkedIn = EventCheckin::count();
        return view('livewire.single-checkin');
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
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

        $existingCheckin = EventCheckin::where('event_ticket_id', $eventTicket->id)->first();
        if ($existingCheckin) {
            $this->checkinStatus = 'existing';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        } else {
            EventCheckin::create([
                'event_ticket_id' => $eventTicket->id,
                'checkin_time' => now(),
            ]);
            $this->checkinStatus = 'success';
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        }
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
                    ->color('info')
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
