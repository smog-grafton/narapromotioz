<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventResource\Pages;
use App\Models\BoxingEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = BoxingEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Boxing Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tagline')
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('event_date')
                            ->required(),
                        Forms\Components\TextInput::make('venue')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'upcoming' => 'Upcoming',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'postponed' => 'Postponed',
                            ])
                            ->required()
                            ->default('upcoming'),
                        Forms\Components\Select::make('event_type')
                            ->options([
                                'championship' => 'Championship',
                                'title_defense' => 'Title Defense',
                                'exhibition' => 'Exhibition',
                                'tournament' => 'Tournament',
                                'regular' => 'Regular Fight Card',
                            ])
                            ->required()
                            ->default('regular'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('poster_image')
                            ->image()
                            ->directory('events/posters')
                            ->maxSize(5120)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('promo_images')
                            ->multiple()
                            ->image()
                            ->directory('events/promo-images')
                            ->maxSize(5120)
                            ->maxFiles(5)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('weigh_in_photos')
                            ->multiple()
                            ->image()
                            ->directory('events/weigh-in')
                            ->maxSize(5120)
                            ->maxFiles(10)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('press_conference_photos')
                            ->multiple()
                            ->image()
                            ->directory('events/press-conference')
                            ->maxSize(5120)
                            ->maxFiles(10)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('behind_scenes_photos')
                            ->multiple()
                            ->image()
                            ->directory('events/behind-scenes')
                            ->maxSize(5120)
                            ->maxFiles(15)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('promo_video_url')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Streaming Configuration')
                    ->schema([
                        Forms\Components\Toggle::make('has_stream')
                            ->label('Enable Live Streaming')
                            ->default(false)
                            ->reactive()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('stream_url')
                            ->label('Primary Stream URL (M3U8/RTMP)')
                            ->url()
                            ->maxLength(500)
                            ->visible(fn (callable $get) => $get('has_stream'))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('stream_backup_url')
                            ->label('Backup Stream URL')
                            ->url()
                            ->maxLength(500)
                            ->visible(fn (callable $get) => $get('has_stream'))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('youtube_stream_id')
                            ->label('YouTube Stream ID')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => $get('has_stream'))
                            ->helperText('For YouTube live streams, enter the video ID'),
                        Forms\Components\TextInput::make('stream_password')
                            ->label('Stream Access Password')
                            ->password()
                            ->maxLength(255)
                            ->visible(fn (callable $get) => $get('has_stream'))
                            ->helperText('Optional password for additional stream security'),
                        Forms\Components\DateTimePicker::make('stream_starts_at')
                            ->label('Stream Start Time')
                            ->visible(fn (callable $get) => $get('has_stream'))
                            ->helperText('Leave empty to use event date/time'),
                        Forms\Components\DateTimePicker::make('stream_ends_at')
                            ->label('Stream End Time')
                            ->visible(fn (callable $get) => $get('has_stream'))
                            ->helperText('Leave empty for no end time'),
                        Forms\Components\TextInput::make('stream_price')
                            ->label('Stream Price')
                            ->numeric()
                            ->prefix('$')
                            ->visible(fn (callable $get) => $get('has_stream')),
                        Forms\Components\Toggle::make('require_ticket_for_stream')
                            ->label('Require Ticket Purchase for Stream Access')
                            ->default(true)
                            ->visible(fn (callable $get) => $get('has_stream')),
                        Forms\Components\Toggle::make('early_access_stream')
                            ->label('Early Access Stream for VIP Ticket Holders')
                            ->default(false)
                            ->visible(fn (callable $get) => $get('has_stream')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Video Content')
                    ->schema([
                        Forms\Components\Repeater::make('highlight_videos')
                            ->label('Event Highlight Videos')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('url')
                                    ->label('Video URL (YouTube, Vimeo, etc.)')
                                    ->url()
                                    ->required(),
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->directory('events/video-thumbnails')
                                    ->maxSize(2048),
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(500),
                                Forms\Components\TextInput::make('duration')
                                    ->label('Duration (e.g., 2:30)')
                                    ->maxLength(10),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('gallery_videos')
                            ->label('Gallery Videos')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('url')
                                    ->label('Video URL')
                                    ->url()
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'interview' => 'Interview',
                                        'training' => 'Training Footage',
                                        'weigh_in' => 'Weigh-in',
                                        'press_conference' => 'Press Conference',
                                        'behind_scenes' => 'Behind the Scenes',
                                        'arrival' => 'Fighter Arrival',
                                        'other' => 'Other',
                                    ])
                                    ->required(),
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->directory('events/video-thumbnails')
                                    ->maxSize(2048),
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(500),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Event Details')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('broadcast_network')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ppv_price')
                            ->label('PPV Price (if applicable)')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('promoter')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Event')
                            ->default(false),
                        Forms\Components\Toggle::make('is_ppv')
                            ->label('Pay-per-view Event')
                            ->default(false),
                        Forms\Components\Toggle::make('tickets_available')
                            ->label('Tickets Available')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Main Event')
                    ->schema([
                        Forms\Components\Select::make('main_event_boxer_1_id')
                            ->relationship('mainEventBoxer1', 'first_name', function (Builder $query) {
                                return $query->select('id', 'first_name', 'last_name')
                                    ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
                                    ->orderBy('full_name');
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable(['first_name', 'last_name']),
                        Forms\Components\Select::make('main_event_boxer_2_id')
                            ->relationship('mainEventBoxer2', 'first_name', function (Builder $query) {
                                return $query->select('id', 'first_name', 'last_name')
                                    ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
                                    ->orderBy('full_name');
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable(['first_name', 'last_name']),
                        Forms\Components\TextInput::make('weight_class')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->label('Championship Title (if applicable)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rounds')
                            ->numeric()
                            ->default(12),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('poster_image')
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'postponed' => 'Postponed',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_ppv')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'postponed' => 'Postponed',
                    ]),
                Tables\Filters\SelectFilter::make('event_type')
                    ->options([
                        'championship' => 'Championship',
                        'title_defense' => 'Title Defense',
                        'exhibition' => 'Exhibition',
                        'tournament' => 'Tournament',
                        'regular' => 'Regular Fight Card',
                    ]),
                Tables\Filters\Filter::make('upcoming')
                    ->query(fn (Builder $query): Builder => $query->where('event_date', '>=', now()))
                    ->label('Upcoming Events'),
                Tables\Filters\Filter::make('past')
                    ->query(fn (Builder $query): Builder => $query->where('event_date', '<', now()))
                    ->label('Past Events'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsFeatured')
                        ->label('Mark as Featured')
                        ->action(fn (Builder $query) => $query->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-star'),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'upcoming' => 'Upcoming',
                                    'ongoing' => 'Ongoing',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                    'postponed' => 'Postponed',
                                ])
                                ->required(),
                        ])
                        ->action(function (Builder $query, array $data): void {
                            $query->update(['status' => $data['status']]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('event_date', 'desc');
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
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
} 