<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-m-calendar-days';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view-any User');
    }


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('Event Information')
            ->schema([
                // Event Name
                Forms\Components\TextInput::make('name')
                ->required()
                ->label('Event Name')
                ->maxLength(255),

                // Event Image
                Forms\Components\FileUpload::make('image')
                    ->imageCropAspectRatio('3:4')
                    ->image()
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpg', 'image/jpeg'])
                    ->directory('event-image')
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file, Get $get): string => (string) str($file->getClientOriginalName())
                            ->prepend('event-' . $get('name') . '-' . Carbon::now()),
                    )
                    ->maxSize('1024')
                    ->imageEditorAspectRatios([
                        '4:3',
                    ])
                    ->downloadable()
                    ->required(),

                // Event Description
                Forms\Components\RichEditor::make('desc')
                    ->label('Description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema(([
                        // Start Date
                        Forms\Components\DateTimePicker::make('registration_start_date')
                        ->required()
                        ->label('Registration Start Date')
                        ->before('registration_end_date')
                        ->seconds(false),

                        // End Date
                        Forms\Components\DateTimePicker::make('registration_end_date')
                            ->required()
                            ->label('Registration End Date')
                            ->after('registration_start_date')
                            ->seconds(false)
                            ->helperText('Registration End date must be later than the start date.'),
                    ])) ,
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema(([
                        // Start Date
                        Forms\Components\DateTimePicker::make('start_date')
                        ->required()
                        ->label('Start Date')
                        ->before('end_date')
                        ->seconds(false),

                        // End Date
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->label('End Date')
                            ->after('start_date')
                            ->seconds(false)
                            ->helperText('End date must be later than the start date.'),
                    ])) ,
                    // Ticket Price
                    Forms\Components\TextInput::make('ticket_price')
                    ->label('Ticket Price')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp.')
                    ->nullable(),

                    // Require Approval
                    Forms\Components\Toggle::make('require_approval')
                        ->label('Require Approval')
                        ->default(false),

                    // Is Public
                    Forms\Components\Toggle::make('is_public')
                        ->label('Is Public')
                        ->default(true),

                    // Short Link
                    Forms\Components\TextInput::make('short_link')
                        ->label('Short Link')
                        ->prefix(env('APP_URL') . '/')
                        ->maxLength(255)
                        ->nullable()
                        ->unique(ignorable: fn ($record) => $record)
                        ->regex('/^\S*$/')
                        ->helperText('Please enter a short link without spaces.'),

                    // Capacity
                    Forms\Components\TextInput::make('capacity')
                        ->label('Capacity')
                        ->suffix('participants')
                        ->numeric()
                        ->minValue(0)
                        ->nullable(),
                    Forms\Components\Select::make('host_id')
                        ->label('Host')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required(),
                            Forms\Components\TextArea::make('desc')
                                ->required(),
                            Forms\Components\TextInput::make('web')
                                ->placeholder('www.namaweb.com'),
                        ])
                        ->relationship('host', 'name')
                        ->required(),

                    // Status
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('active')
                        ->required(),
            ]),
            Forms\Components\Section::make('Activities')
                ->schema([
                    Forms\Components\Repeater::make('activities') // This assumes 'activities' is the name of the relationship
                        ->label('Activities')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Activity Name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('ticket_price')
                                ->label('Ticket Price')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('Rp.'),
                            Forms\Components\Grid::make()
                                ->columns(2)
                                ->schema([
                                    Forms\Components\DateTimePicker::make('start_date')
                                        ->label('Start Date')
                                        ->before('end_date')
                                        ->required()
                                        ->seconds(false),
                                    Forms\Components\DateTimePicker::make('end_date')
                                        ->label('End Date')
                                        ->after('start_date')
                                        ->helperText('End date must be later than the start date.')
                                        ->required()
                                        ->seconds(false),
                                ]),
                            Forms\Components\TextArea::make('desc')
                                ->label('Description')
                                ->maxLength(65535),
                        ])
                        ->relationship('activities')
                        ->columns(1) // Adjust the number of columns as needed
                        ->defaultItems(1) // Optional: Start with one empty item
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Poster')
                    ->sortable()
                    ->searchable(false),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->sortable()
                    ->searchable()
                    ->dateTime('Y-m-d H:i:s'),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->sortable()
                    ->searchable()
                    ->dateTime('Y-m-d H:i:s'),

                Tables\Columns\TextColumn::make('host.name')
                    ->label('Host')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'active' => 'heroicon-o-check-circle',
                        'inactive' => 'heroicon-o-minus-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-information-circle'
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('start_date', 'asc');
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
