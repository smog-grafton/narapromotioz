<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Repeater;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationGroup = 'Boxing Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Event Management')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255),
                                        DateTimePicker::make('event_date')
                                            ->required()
                                            ->label('Event Date & Time'),
                                        TextInput::make('location')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('venue')
                                            ->maxLength(255),
                                        TextInput::make('promoter')
                                            ->maxLength(255),
                                        TextInput::make('available_tickets')
                                            ->numeric()
                                            ->minValue(0)
                                            ->default(1000),
                                        TextInput::make('ticket_price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->minValue(0)
                                            ->default(50.00),
                                        Toggle::make('is_featured')
                                            ->label('Featured Event')
                                            ->default(false),
                                    ]),
                                
                                RichEditor::make('description')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('events/description')
                                    ->columnSpanFull(),
                                
                                FileUpload::make('event_banner')
                                    ->image()
                                    ->label('Event Banner Image')
                                    ->directory('events/banners')
                                    ->visibility('public')
                                    ->maxSize(5 * 1024)
                                    ->columnSpanFull(),
                                
                                FileUpload::make('images')
                                    ->multiple()
                                    ->image()
                                    ->label('Event Gallery Images')
                                    ->directory('events/gallery')
                                    ->visibility('public')
                                    ->maxSize(5 * 1024)
                                    ->columnSpanFull(),
                            ]),
                            
                        Tabs\Tab::make('Fight Card')
                            ->schema([
                                Repeater::make('fights')
                                    ->relationship()
                                    ->schema([
                                        Grid::make()
                                            ->columns(3)
                                            ->schema([
                                                Select::make('fighter_one_id')
                                                    ->relationship('fighterOne', 'full_name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->label('Fighter 1'),
                                                
                                                Select::make('fighter_two_id')
                                                    ->relationship('fighterTwo', 'full_name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->label('Fighter 2'),
                                                
                                                TextInput::make('fight_order')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->minValue(1)
                                                    ->label('Bout Order'),
                                                
                                                TextInput::make('rounds')
                                                    ->numeric()
                                                    ->default(12)
                                                    ->minValue(1)
                                                    ->maxValue(12),
                                                
                                                Select::make('weight_class')
                                                    ->options([
                                                        'Heavyweight' => 'Heavyweight',
                                                        'Cruiserweight' => 'Cruiserweight',
                                                        'Light Heavyweight' => 'Light Heavyweight',
                                                        'Super Middleweight' => 'Super Middleweight',
                                                        'Middleweight' => 'Middleweight',
                                                        'Welterweight' => 'Welterweight',
                                                        'Lightweight' => 'Lightweight',
                                                        'Featherweight' => 'Featherweight',
                                                        'Bantamweight' => 'Bantamweight',
                                                        'Flyweight' => 'Flyweight',
                                                    ]),
                                                
                                                Toggle::make('is_main_event')
                                                    ->label('Main Event')
                                                    ->default(false),
                                                
                                                TextInput::make('championship_title')
                                                    ->maxLength(255)
                                                    ->label('Championship Title (if any)'),
                                                
                                                Select::make('status')
                                                    ->options([
                                                        'upcoming' => 'Upcoming',
                                                        'in_progress' => 'In Progress',
                                                        'completed' => 'Completed',
                                                        'cancelled' => 'Cancelled',
                                                    ])
                                                    ->default('upcoming'),
                                                
                                                Select::make('winner_id')
                                                    ->relationship('winner', 'full_name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->label('Winner (if fight completed)'),
                                                        
                                                Select::make('result_method')
                                                    ->options([
                                                        'KO' => 'Knockout (KO)',
                                                        'TKO' => 'Technical Knockout (TKO)',
                                                        'UD' => 'Unanimous Decision (UD)',
                                                        'SD' => 'Split Decision (SD)',
                                                        'MD' => 'Majority Decision (MD)',
                                                        'Draw' => 'Draw',
                                                        'NC' => 'No Contest (NC)',
                                                        'DQ' => 'Disqualification (DQ)',
                                                    ])
                                                    ->label('Result Method'),
                                                
                                                TextInput::make('result_round')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->maxValue(12)
                                                    ->label('Result Round'),
                                            ]),
                                    ])
                                    ->label('Fights')
                                    ->defaultItems(1)
                                    ->reorderable()
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ]),
                            
                        Tabs\Tab::make('Live Streaming')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('is_streamed')
                                            ->label('Will be Live Streamed')
                                            ->default(false),
                                        Toggle::make('is_live')
                                            ->label('Currently Live')
                                            ->default(false),
                                        TextInput::make('stream_price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->minValue(0)
                                            ->default(19.99)
                                            ->label('Streaming Price'),
                                        TextInput::make('stream_url')
                                            ->url()
                                            ->label('Streaming URL (for embedding)'),
                                        Textarea::make('streaming_notes')
                                            ->rows(3)
                                            ->placeholder('Special instructions or details about the stream')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('SEO & Meta')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->maxLength(70)
                                    ->label('Meta Title'),
                                Textarea::make('meta_description')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->label('Meta Description'),
                                TextInput::make('seo_keywords')
                                    ->placeholder('Comma separated keywords')
                                    ->label('SEO Keywords'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('event_banner')
                    ->label('Banner')
                    ->square(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('ticket_price')
                    ->money('USD')
                    ->sortable(),
                BooleanColumn::make('is_featured')
                    ->label('Featured')
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-x-mark'),
                BooleanColumn::make('is_live')
                    ->label('Live Now')
                    ->trueIcon('heroicon-o-signal')
                    ->falseIcon('heroicon-o-x-mark'),
                TextColumn::make('fight_count')
                    ->label('Fights')
                    ->getStateUsing(fn (Event $record): int => $record->fights()->count()),
            ])
            ->filters([
                Filter::make('is_featured')
                    ->label('Featured Events')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->toggle(),
                Filter::make('is_live')
                    ->label('Live Now')
                    ->query(fn (Builder $query): Builder => $query->where('is_live', true))
                    ->toggle(),
                Filter::make('upcoming')
                    ->label('Upcoming Events')
                    ->query(fn (Builder $query): Builder => $query->where('event_date', '>', now()))
                    ->toggle(),
                Filter::make('past')
                    ->label('Past Events')
                    ->query(fn (Builder $query): Builder => $query->where('event_date', '<', now()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }    
}