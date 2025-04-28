<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsArticleResource\Pages;
use App\Models\NewsArticle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Str;

class NewsArticleResource extends Resource
{
    protected static ?string $model = NewsArticle::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('News Management')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(NewsArticle::class, 'slug', ignoreRecord: true),
                                        
                                        Select::make('category')
                                            ->options([
                                                'boxing-news' => 'Boxing News',
                                                'fight-announcements' => 'Fight Announcements',
                                                'post-fight-analysis' => 'Post-Fight Analysis',
                                                'fighter-spotlight' => 'Fighter Spotlight',
                                                'rankings-update' => 'Rankings Update',
                                                'opinion' => 'Opinion & Editorial',
                                                'interviews' => 'Interviews',
                                                'press-releases' => 'Press Releases',
                                                'events' => 'Events Coverage',
                                            ])
                                            ->required(),
                                        
                                        Select::make('author_id')
                                            ->relationship('author', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        
                                        DateTimePicker::make('published_at')
                                            ->label('Publication Date')
                                            ->default(now()),
                                            
                                        TagsInput::make('tags')
                                            ->placeholder('Add a tag')
                                            ->helperText('Press Enter to add a tag'),
                                            
                                        Toggle::make('is_featured')
                                            ->label('Featured Article')
                                            ->helperText('Featured articles appear prominently on the homepage'),
                                            
                                        Toggle::make('is_breaking')
                                            ->label('Breaking News')
                                            ->helperText('Breaking news receives special styling and priority'),
                                            
                                        Textarea::make('excerpt')
                                            ->required()
                                            ->maxLength(500)
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        
                        Tab::make('Content')
                            ->schema([
                                RichEditor::make('content')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('news-article-attachments')
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ]),
                            ]),
                        
                        Tab::make('Media & SEO')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('featured_image')
                                            ->image()
                                            ->required()
                                            ->directory('news-featured-images')
                                            ->maxSize(5120)
                                            ->columnSpanFull(),
                                            
                                        FileUpload::make('gallery')
                                            ->multiple()
                                            ->image()
                                            ->directory('news-galleries')
                                            ->maxSize(5120)
                                            ->columnSpanFull(),
                                            
                                        TextInput::make('video_url')
                                            ->label('YouTube or Embedded Video URL')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        TextInput::make('meta_title')
                                            ->label('SEO Meta Title')
                                            ->maxLength(100)
                                            ->placeholder('Leave empty to use article title'),
                                            
                                        Textarea::make('meta_description')
                                            ->label('SEO Meta Description')
                                            ->maxLength(160)
                                            ->rows(2)
                                            ->placeholder('Leave empty to use article excerpt'),
                                            
                                        TagsInput::make('meta_keywords')
                                            ->label('SEO Meta Keywords')
                                            ->placeholder('Add a keyword')
                                            ->helperText('Press Enter to add a keyword'),
                                            
                                        Select::make('related_articles')
                                            ->label('Related Articles')
                                            ->multiple()
                                            ->relationship('relatedArticles', 'title')
                                            ->searchable()
                                            ->preload()
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        
                        Tab::make('Social Media')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('social_title')
                                            ->label('Social Media Title')
                                            ->maxLength(100)
                                            ->placeholder('Leave empty to use article title'),
                                            
                                        Textarea::make('social_description')
                                            ->label('Social Media Description')
                                            ->maxLength(280)
                                            ->rows(2)
                                            ->placeholder('Leave empty to use article excerpt'),
                                            
                                        FileUpload::make('social_image')
                                            ->label('Social Media Image')
                                            ->image()
                                            ->directory('news-social-images')
                                            ->maxSize(2048)
                                            ->helperText('Recommended size: 1200x630px'),
                                            
                                        Toggle::make('auto_post_social')
                                            ->label('Auto-post to Social Media')
                                            ->helperText('Automatically share this article on connected social media accounts'),
                                            
                                        Select::make('social_platforms')
                                            ->label('Social Media Platforms')
                                            ->options([
                                                'facebook' => 'Facebook',
                                                'twitter' => 'Twitter',
                                                'instagram' => 'Instagram',
                                                'linkedin' => 'LinkedIn',
                                            ])
                                            ->multiple()
                                            ->visible(fn (callable $get): bool => $get('auto_post_social')),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(fn () => asset('images/placeholder.jpg'))
                    ->size(60),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('category')
                    ->badge()
                    ->colors([
                        'primary' => 'boxing-news',
                        'success' => 'fighter-spotlight',
                        'warning' => 'fight-announcements',
                        'danger' => 'post-fight-analysis',
                        'sky' => 'rankings-update',
                        'purple' => 'interviews',
                        'gray' => fn ($state): bool => in_array($state, [
                            'opinion',
                            'press-releases',
                            'events',
                        ]),
                    ]),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                IconColumn::make('is_breaking')
                    ->boolean()
                    ->label('Breaking'),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime('M d, Y • H:i')
                    ->sortable(),
                TextColumn::make('views')
                    ->numeric()
                    ->label('Views')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'boxing-news' => 'Boxing News',
                        'fight-announcements' => 'Fight Announcements',
                        'post-fight-analysis' => 'Post-Fight Analysis',
                        'fighter-spotlight' => 'Fighter Spotlight',
                        'rankings-update' => 'Rankings Update',
                        'opinion' => 'Opinion & Editorial',
                        'interviews' => 'Interviews',
                        'press-releases' => 'Press Releases',
                        'events' => 'Events Coverage',
                    ]),
                SelectFilter::make('is_featured')
                    ->options([
                        '1' => 'Featured Articles',
                        '0' => 'Regular Articles',
                    ])
                    ->label('Featured Status'),
                SelectFilter::make('is_breaking')
                    ->options([
                        '1' => 'Breaking News',
                        '0' => 'Regular News',
                    ])
                    ->label('Breaking Status'),
                SelectFilter::make('author_id')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Author'),
                Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (NewsArticle $record) {
                        $duplicate = $record->replicate();
                        $duplicate->title = "Copy of " . $duplicate->title;
                        $duplicate->slug = Str::slug($duplicate->title);
                        $duplicate->published_at = now();
                        $duplicate->views = 0;
                        $duplicate->save();
                        
                        return redirect(static::getUrl('edit', ['record' => $duplicate]));
                    }),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (NewsArticle $record): string => route('news.show', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('feature')
                        ->label('Mark as Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true])),
                    Tables\Actions\BulkAction::make('unfeature')
                        ->label('Remove Featured')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => false])),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
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
            'index' => Pages\ListNewsArticles::route('/'),
            'create' => Pages\CreateNewsArticle::route('/create'),
            'edit' => Pages\EditNewsArticle::route('/{record}/edit'),
        ];
    }    
}