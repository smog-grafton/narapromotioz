<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StreamResource\Pages;
use App\Models\Stream;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class StreamResource extends Resource
{
    protected static ?string $model = Stream::class;

    protected static ?string $navigationIcon = 'heroicon-o-play';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Select::make('event_id')
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        
                        DateTimePicker::make('stream_date')
                            ->required()
                            ->label('Stream Date & Time'),
                        
                        TextInput::make('duration_minutes')
                            ->numeric()
                            ->minValue(1)
                            ->default(120)
                            ->required()
                            ->suffix('minutes')
                            ->helperText('Approximate duration in minutes'),
                        
                        TextInput::make('stream_url')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->helperText('Link to the stream (YouTube, Vimeo, etc.)'),
                        
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->helperText('Set to 0 for free streams'),
                    ]),
                
                Section::make('Stream Settings')
                    ->schema([
                        Toggle::make('is_live')
                            ->label('Currently Live')
                            ->helperText('Toggle if the stream is currently live'),
                        
                        Toggle::make('is_featured')
                            ->label('Featured Stream')
                            ->helperText('Feature this stream on the homepage'),
                        
                        Toggle::make('is_premium')
                            ->label('Premium Content')
                            ->helperText('Requires payment to access')
                            ->default(true),
                        
                        FileUpload::make('thumbnail')
                            ->image()
                            ->directory('stream-thumbnails')
                            ->maxSize(5120) // 5MB
                            ->helperText('Recommended size: 1280x720px'),
                        
                        Textarea::make('description')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('stream_date')
                    ->dateTime('M d, Y • H:i')
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn (int $state): string => "{$state} min"),
                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                IconColumn::make('is_premium')
                    ->boolean()
                    ->label('Premium'),
                ToggleColumn::make('is_live')
                    ->label('Live'),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_live')
                    ->options([
                        '1' => 'Live Now',
                        '0' => 'Not Live',
                    ])
                    ->label('Live Status'),
                SelectFilter::make('is_premium')
                    ->options([
                        '1' => 'Premium',
                        '0' => 'Free',
                    ])
                    ->label('Stream Type'),
                Filter::make('stream_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('stream_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('stream_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('stream_date', 'desc');
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
            'index' => Pages\ListStreams::route('/'),
            'create' => Pages\CreateStream::route('/create'),
            'edit' => Pages\EditStream::route('/{record}/edit'),
        ];
    }    
}