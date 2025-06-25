<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VideoResource\Pages;
use App\Models\BoxingVideo;
use App\Models\Boxer;
use App\Models\BoxingEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Str;

class VideoResource extends Resource
{
    protected static ?string $model = BoxingVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Videos';

    protected static ?string $pluralModelLabel = 'Videos';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Video Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $context, $state, Forms\Set $set) {
                                        if ($context === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(BoxingVideo::class, 'slug', ignoreRecord: true)
                                    ->rules(['alpha_dash']),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('category')
                                    ->options([
                                        'fight' => 'Fight',
                                        'training' => 'Training',
                                        'interview' => 'Interview',
                                        'highlight' => 'Highlight',
                                        'documentary' => 'Documentary',
                                        'analysis' => 'Analysis',
                                        'press_conference' => 'Press Conference',
                                        'weigh_in' => 'Weigh In',
                                        'behind_scenes' => 'Behind Scenes',
                                    ])
                                    ->searchable(),

                                Forms\Components\Select::make('video_type')
                                    ->options([
                                        'youtube' => 'YouTube',
                                        'vimeo' => 'Vimeo',
                                        'upload' => 'Upload',
                                        'embed' => 'Embed',
                                    ])
                                    ->required()
                                    ->default('youtube')
                                    ->live(),

                                Forms\Components\Select::make('source_type')
                                    ->options([
                                        'youtube' => 'YouTube',
                                        'vimeo' => 'Vimeo',
                                        'dailymotion' => 'Dailymotion',
                                        'upload' => 'Upload',
                                        'external' => 'External',
                                    ])
                                    ->default('youtube'),
                            ]),
                    ]),

                Section::make('Video Content')
                    ->schema([
                        Forms\Components\TextInput::make('video_url')
                            ->url()
                            ->label('Video URL')
                            ->placeholder('https://youtube.com/watch?v=...')
                            ->visible(fn (Forms\Get $get) => in_array($get('video_type'), ['youtube', 'vimeo', 'embed']))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    // Extract video ID from URL
                                    $videoId = self::extractVideoId($state);
                                    $set('video_id', $videoId);
                                }
                            }),

                        Forms\Components\TextInput::make('video_id')
                            ->label('Video ID')
                            ->placeholder('dQw4w9WgXcQ')
                            ->visible(fn (Forms\Get $get) => in_array($get('video_type'), ['youtube', 'vimeo'])),

                        Forms\Components\FileUpload::make('video_file')
                            ->label('Video File')
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv'])
                            ->directory('videos')
                            ->visible(fn (Forms\Get $get) => $get('video_type') === 'upload'),

                        Forms\Components\Textarea::make('embed_code')
                            ->label('Embed Code')
                            ->rows(6)
                            ->visible(fn (Forms\Get $get) => $get('video_type') === 'embed'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('duration')
                                    ->placeholder('10:30'),

                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->directory('video-thumbnails')
                                    ->imageEditor(),
                            ]),
                    ]),

                Section::make('Associations')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('boxer_id')
                                    ->label('Primary Boxer')
                                    ->relationship('boxer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record?->name ?? 'Unknown Boxer'),

                                Forms\Components\Select::make('event_id')
                                    ->label('Event')
                                    ->relationship('event', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record?->title ?? 'Unknown Event'),
                            ]),

                        Forms\Components\Select::make('boxers')
                            ->label('Featured Boxers')
                            ->relationship('boxers', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record?->name ?? 'Unknown Boxer'),

                        Forms\Components\Select::make('events')
                            ->label('Related Events')
                            ->relationship('events', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record?->title ?? 'Unknown Event'),
                    ]),

                Section::make('Settings & Metadata')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\Toggle::make('is_premium')
                                    ->label('Premium Content'),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Video'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('premium')
                                    ->label('Premium (New)'),

                                Forms\Components\Toggle::make('featured')
                                    ->label('Featured (New)'),

                                Forms\Components\DateTimePicker::make('publish_date')
                                    ->label('Publish Date'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Published At'),

                                Forms\Components\TextInput::make('views_count')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add tags')
                            ->separator(','),

                        Forms\Components\KeyValue::make('metadata')
                            ->label('Additional Metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->size(80, 60)
                    ->getStateUsing(function ($record) {
                        $thumbnail = $record->thumbnail;
                        
                        if (!$thumbnail) {
                            return asset('assets/images/videos/default-thumbnail.jpg');
                        }
                        
                        // If it's already a full URL, return as is
                        if (str_starts_with($thumbnail, 'http://') || str_starts_with($thumbnail, 'https://')) {
                            return $thumbnail;
                        }
                        
                        // If it starts with assets/, return with asset() helper
                        if (str_starts_with($thumbnail, 'assets/')) {
                            return asset($thumbnail);
                        }
                        
                        // If it's a storage path
                        if (str_starts_with($thumbnail, 'storage/')) {
                            return asset($thumbnail);
                        }
                        
                        // Default case - assume it's in storage
                        return asset("storage/{$thumbnail}");
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fight' => 'danger',
                        'training' => 'info',
                        'interview' => 'warning',
                        'highlight' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('video_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'youtube' => 'danger',
                        'vimeo' => 'info',
                        'upload' => 'success',
                        'embed' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('boxer.name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Likes')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_premium')
                    ->label('Premium')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-fire')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),

                SelectFilter::make('category')
                    ->options([
                        'fight' => 'Fight',
                        'training' => 'Training',
                        'interview' => 'Interview',
                        'highlight' => 'Highlight',
                        'documentary' => 'Documentary',
                        'analysis' => 'Analysis',
                        'press_conference' => 'Press Conference',
                        'weigh_in' => 'Weigh In',
                        'behind_scenes' => 'Behind Scenes',
                    ]),

                SelectFilter::make('video_type')
                    ->options([
                        'youtube' => 'YouTube',
                        'vimeo' => 'Vimeo',
                        'upload' => 'Upload',
                        'embed' => 'Embed',
                    ]),

                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('Premium Content'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Videos'),

                SelectFilter::make('boxer')
                    ->relationship('boxer', 'name')
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()
                    ->url(fn (BoxingVideo $record): string => route('videos.show', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'published']);
                            });
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('feature')
                        ->label('Feature Selected')
                        ->icon('heroicon-o-star')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_featured' => true]);
                            });
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }

    private static function extractVideoId($url): ?string
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return $matches[1];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
} 