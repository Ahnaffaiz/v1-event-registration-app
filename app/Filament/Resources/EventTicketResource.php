<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventTicketResource\Pages;
use App\Filament\Resources\EventTicketResource\RelationManagers;
use App\Models\Activity;
use App\Models\ActivityTicket;
use App\Models\EventCheckin;
use App\Models\EventTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EventTicketResource extends Resource
{
    protected static ?string $model = EventTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ticket Event')
                    ->schema([
                        Forms\Components\Select::make('event_id')
                            ->required()
                            ->relationship('event', 'name')
                            ->reactive() // Make the select reactive
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Clear the activities when the event changes
                                $set('activityTicket', []);
                            }),
                        Forms\Components\CheckboxList::make('activityTickets')
                            ->options(function (callable $get, $record) {
                                $eventId = $get('event_id');
                                if ($eventId) {
                                    return Activity::where('event_id', $eventId)->pluck('name', 'id')->toArray();
                                }
                                return [];
                            })->afterStateHydrated(function ($component, $state, $record) {
                                if (! filled($state)) {
                                    $activityTicket = ActivityTicket::where('event_ticket_id', $record?->id)->get()->pluck('activity_id')->toArray();
                                    $component->state($activityTicket);
                                }
                            }),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('occupation')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp')
                            ->tel()
                            ->prefix('+62')
                            ->regex('/^8\d+$/')
                            ->helperText('WhatsApp number must start with 8 after the +62 prefix.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('qr_code')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('occupation')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('id')
                    ->label('Check In')
                    ->icon(function ($record) {
                        $checkinExists = EventCheckin::where('event_ticket_id', $record->id)->exists();
                        return $checkinExists ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                    })
                    ->color(function ($record) {
                        $checkinExists = EventCheckin::where('event_ticket_id', $record->id)->exists();
                        return $checkinExists ? 'success' : 'danger';
                    }),
                Tables\Columns\TextColumn::make('printed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('send_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('showCode')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->modalAlignment(Alignment::Center)
                    ->modalSubmitAction(false)
                    ->modalWidth(MaxWidth::Small)
                    ->modalContent(fn (EventTicket $record): View => view(
                        'modal.qr-code-modal',
                        ['record' => $record],
                    )),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('checkin')
                    ->label('Check In')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('status')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventTickets::route('/'),
            'create' => Pages\CreateEventTicket::route('/create'),
            'edit' => Pages\EditEventTicket::route('/{record}/edit'),
        ];
    }
}
