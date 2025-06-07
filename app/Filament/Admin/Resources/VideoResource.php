<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VideoResource\Pages;
use App\Models\BoxingVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = BoxingVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Video Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('duration')
                            ->maxLength(10)
                            ->placeholder('MM:SS'),
                        Forms\Components\Select::make('video_type')
                            ->options([
                                'highlight' => 'Highlight',
                                'full_fight' => 'Full Fight',
                                'interview' => 'Interview',
                                'promo' => 'Promotional Video',
                                'training' => 'Training',
                                'documentary' => 'Documentary',
                                'press_conference' => 'Press Conference',
                                'weigh_in' => 'Weigh-in',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_premium')
                            ->label('Premium Content')
                            ->default(false),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Video')
                            ->default(false),
                        Forms\Components\DatePicker::make('publish_date')
                            ->default(now()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Video Source')
                    ->schema([
                        Forms\Components\Select::make('source_type')
                            ->label('Video Source')
                            ->options([
                                'youtube' => 'YouTube',
                                'vimeo' => 'Vimeo',
                                'uploaded' => 'Uploaded File',
                                'external' => 'External URL',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('video_url', null)),
                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => in_array($get('source_type'), ['youtube', 'vimeo', 'external']))
                            ->required(fn (callable $get) => in_array($get('source_type'), ['youtube', 'vimeo', 'external'])),
                        Forms\Components\FileUpload::make('video_file')
                            ->label('Upload Video File')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->directory('videos/uploads')
                            ->maxSize(512000) // 500MB
                            ->visible(fn (callable $get) => $get('source_type') === 'uploaded')
                            ->required(fn (callable $get) => $get('source_type') === 'uploaded'),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('videos/thumbnails')
                            ->maxSize(5120),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Related Content')
                    ->schema([
                        Forms\Components\Select::make('boxer_id')
                            ->relationship('boxer', 'first_name', function (Builder $query) {
                                return $query->select('id', 'first_name', 'last_name')
                                    ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
                                    ->orderBy('full_name');
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable(['first_name', 'last_name']),
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'name'),
                        Forms\Components\TagsInput::make('tags')
                            ->separator(','),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('video_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('source_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('boxer.full_name')
                    ->label('Boxer')
                    ->searchable(['boxers.first_name', 'boxers.last_name']),
                Tables\Columns\TextColumn::make('publish_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_premium')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('video_type')
                    ->options([
                        'highlight' => 'Highlight',
                        'full_fight' => 'Full Fight',
                        'interview' => 'Interview',
                        'promo' => 'Promotional Video',
                        'training' => 'Training',
                        'documentary' => 'Documentary',
                        'press_conference' => 'Press Conference',
                        'weigh_in' => 'Weigh-in',
                        'other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('source_type')
                    ->options([
                        'youtube' => 'YouTube',
                        'vimeo' => 'Vimeo',
                        'uploaded' => 'Uploaded File',
                        'external' => 'External URL',
                    ]),
                Tables\Filters\SelectFilter::make('boxer_id')
                    ->relationship('boxer', 'first_name', function (Builder $query) {
                        return $query->select('id', 'first_name', 'last_name')
                            ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
                            ->orderBy('full_name');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                    ->searchable()
                    ->label('Boxer'),
                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('Premium Content'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('togglePremium')
                        ->label('Toggle Premium Status')
                        ->action(function (Builder $query, array $data) {
                            $query->update([
                                'is_premium' => $data['is_premium'] ?? false,
                            ]);
                        })
                        ->form([
                            Forms\Components\Toggle::make('is_premium')
                                ->label('Premium Content')
                                ->default(true),
                        ])
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('toggleFeatured')
                        ->label('Toggle Featured Status')
                        ->action(function (Builder $query, array $data) {
                            $query->update([
                                'is_featured' => $data['is_featured'] ?? false,
                            ]);
                        })
                        ->form([
                            Forms\Components\Toggle::make('is_featured')
                                ->label('Featured Video')
                                ->default(true),
                        ])
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('publish_date', 'desc');
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'view' => Pages\ViewVideo::route('/{record}'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
} 