<?php

namespace App\Livewire;

use App\Models\Activity;
use App\Models\ActivityCheckin as ModelsActivityCheckin;
use App\Models\ActivityTicket;
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
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class ActivityCheckin extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $activeTab = 'scanner', $checkinStatus, $participant, $event, $checkedIn;
    public $activity_id, $activityTicket;

    #[Title('Check In Single Event')]

    public function mount()
    {
        $this->event = Event::first();
        $this->activityTicket = ActivityTicket::where('qr_code', '!=', null)->get()->pluck( 'id');

        if(Session::has('activity_id')) {
            $this->activity_id = Session::get('activity_id');
        } else {
            $this->activity_id = $this->event?->activities->first()->id;
        }
    }
    public function render()
    {
        $this->checkedIn = ModelsActivityCheckin::count();
        return view('livewire.activity-checkin');
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
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
            $this->activityTicket = ActivityTicket::where('qr_code', '!=', null)->get()->pluck( 'id');
            $this->dispatch('checkin-status', checkinStatus: $this->checkinStatus);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityTicket::query())
            ->defaultGroup('eventTicket.name')
            ->columns([
                TextColumn::make('qr_code')
                    ->label('Ticket Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('eventTicket.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('activity.name')
                    ->label('Activity')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('eventTicket.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('eventTicket.occupation')
                    ->label('Occupation')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('id')
                    ->label('Check In')
                    ->icon(function ($record) {
                        $checkinExists = ModelsActivityCheckin::where('activity_ticket_id', $record->id)->exists();
                        return $checkinExists ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                    })
                    ->color(function ($record) {
                        $checkinExists = ModelsActivityCheckin::where('activity_ticket_id', $record->id)->exists();
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
                                $checkinExists = ModelsActivityCheckin::where('activity_ticket_id', $record->id)->exists();
                                return $checkinExists ? 'checkin' : 'checkout';
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, ActivityTicket $record): void {
                        if($data['status'] === 'checkin') {
                            ModelsActivityCheckin::firstOrCreate([
                                'activity_ticket_id' => $record->id,
                                'checkin_time' => now(),
                            ]);
                        } else {
                            ModelsActivityCheckin::where('activity_ticket_id', $record->id)->delete();
                        }
                    })->modalWidth('sm'),
            ]);
    }

    public function updatedActivityId()
    {
        Session::put('activity_id', $this->activity_id);
        $this->activity_id = Session::get('activity_id');
    }
}
