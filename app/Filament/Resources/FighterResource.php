<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FighterResource\Pages;
use App\Filament\Resources\FighterResource\RelationManagers;
use App\Models\Fighter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class FighterResource extends Resource
{
    protected static ?string $model = Fighter::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Boxing Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Fighter Management')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('first_name')
                                            ->required()
                                            ->maxLength(255),
                                            
                                        TextInput::make('last_name')
                                            ->required()
                                            ->maxLength(255),
                                            
                                        TextInput::make('nickname')
                                            ->maxLength(255),
                                            
                                        DatePicker::make('date_of_birth')
                                            ->required()
                                            ->maxDate(now()->subYears(16))
                                            ->displayFormat('M d, Y'),
                                            
                                        Select::make('gender')
                                            ->options([
                                                'male' => 'Male',
                                                'female' => 'Female',
                                            ])
                                            ->required(),
                                            
                                        Select::make('country')
                                            ->options([
                                                'USA' => 'USA',
                                                'UK' => 'UK',
                                                'Mexico' => 'Mexico',
                                                'Canada' => 'Canada',
                                                'Japan' => 'Japan',
                                                'Russia' => 'Russia',
                                                'Brazil' => 'Brazil',
                                                'Philippines' => 'Philippines',
                                                'Ukraine' => 'Ukraine',
                                                'Kazakhstan' => 'Kazakhstan',
                                                'France' => 'France',
                                                'Germany' => 'Germany',
                                                'Italy' => 'Italy',
                                                'Cuba' => 'Cuba',
                                                'Puerto Rico' => 'Puerto Rico',
                                                'Ghana' => 'Ghana',
                                                'Nigeria' => 'Nigeria',
                                                'South Africa' => 'South Africa',
                                                'Kenya' => 'Kenya',
                                                'Tanzania' => 'Tanzania',
                                                'Uganda' => 'Uganda',
                                                'Zambia' => 'Zambia',
                                                'Zimbabwe' => 'Zimbabwe',
                                                'Egypt' => 'Egypt',
                                                'Morocco' => 'Morocco',
                                                'Tunisia' => 'Tunisia',
                                                'Algeria' => 'Algeria',
                                                'Australia' => 'Australia',
                                                'New Zealand' => 'New Zealand',
                                                'Other' => 'Other Country',
                                            ])
                                            ->searchable()
                                            ->required(),
                                            
                                        Select::make('weight_class')
                                            ->options([
                                                'Heavyweight' => 'Heavyweight',
                                                'Cruiserweight' => 'Cruiserweight',
                                                'Light Heavyweight' => 'Light Heavyweight',
                                                'Super Middleweight' => 'Super Middleweight',
                                                'Middleweight' => 'Middleweight',
                                                'Super Welterweight' => 'Super Welterweight',
                                                'Welterweight' => 'Welterweight',
                                                'Super Lightweight' => 'Super Lightweight',
                                                'Lightweight' => 'Lightweight',
                                                'Super Featherweight' => 'Super Featherweight',
                                                'Featherweight' => 'Featherweight',
                                                'Super Bantamweight' => 'Super Bantamweight',
                                                'Bantamweight' => 'Bantamweight',
                                                'Super Flyweight' => 'Super Flyweight',
                                                'Flyweight' => 'Flyweight',
                                                'Light Flyweight' => 'Light Flyweight',
                                                'Minimumweight' => 'Minimumweight',
                                            ])
                                            ->required(),
                                            
                                        TextInput::make('height')
                                            ->label('Height (cm)')
                                            ->numeric()
                                            ->required(),
                                            
                                        TextInput::make('reach')
                                            ->label('Reach (cm)')
                                            ->numeric()
                                            ->required(),
                                            
                                        TextInput::make('stance')
                                            ->default('Orthodox')
                                            ->maxLength(20),
                                            
                                        FileUpload::make('profile_image')
                                            ->image()
                                            ->directory('fighter-images')
                                            ->maxSize(5120)
                                            ->columnSpanFull(),
                                            
                                        FileUpload::make('banner_image')
                                            ->image()
                                            ->directory('fighter-banners')
                                            ->maxSize(5120)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Tab::make('Record & Stats')
                            ->schema([
                                Grid::make()
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('wins')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->required(),
                                            
                                        TextInput::make('losses')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->required(),
                                            
                                        TextInput::make('draws')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->required(),
                                            
                                        TextInput::make('ko_wins')
                                            ->label('KO/TKO Wins')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->required(),
                                            
                                        TextInput::make('decision_wins')
                                            ->label('Decision Wins')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->required(),

                                        TextInput::make('total_rounds')
                                            ->label('Total Rounds Fought')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->required(),
                                            
                                        TextInput::make('knockout_percentage')
                                            ->label('KO Percentage (%)')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%')
                                            ->required(),
                                            
                                        Toggle::make('is_champion')
                                            ->label('Current Champion')
                                            ->helperText('Fighter currently holds a major title'),
                                            
                                        TextInput::make('championship_title')
                                            ->label('Championship Title')
                                            ->placeholder('e.g. WBC Heavyweight Champion')
                                            ->visible(fn (callable $get): bool => $get('is_champion')),
                                    ]),
                                    
                                Section::make('Title History')
                                    ->schema([
                                        Repeater::make('titles')
                                            ->schema([
                                                TextInput::make('title_name')
                                                    ->label('Title')
                                                    ->required()
                                                    ->maxLength(255),
                                                    
                                                Select::make('organization')
                                                    ->options([
                                                        'WBC' => 'World Boxing Council (WBC)',
                                                        'WBA' => 'World Boxing Association (WBA)',
                                                        'IBF' => 'International Boxing Federation (IBF)',
                                                        'WBO' => 'World Boxing Organization (WBO)',
                                                        'IBO' => 'International Boxing Organization (IBO)',
                                                        'Ring' => 'The Ring Magazine',
                                                        'Other' => 'Other Organization',
                                                    ])
                                                    ->required(),
                                                    
                                                DatePicker::make('won_date')
                                                    ->label('Date Won')
                                                    ->required(),
                                                    
                                                DatePicker::make('lost_date')
                                                    ->label('Date Lost')
                                                    ->helperText('Leave empty if still held'),
                                                    
                                                TextInput::make('defenses')
                                                    ->label('Number of Defenses')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0),
                                            ])
                                            ->columns(5)
                                            ->columnSpanFull()
                                            ->defaultItems(0),
                                    ]),
                            ]),
                            
                        Tab::make('Biography & Content')
                            ->schema([
                                Grid::make()
                                    ->columns(1)
                                    ->schema([
                                        Textarea::make('short_bio')
                                            ->label('Short Biography')
                                            ->helperText('Brief description for cards and listings (150-200 characters recommended)')
                                            ->maxLength(200)
                                            ->rows(2),
                                            
                                        RichEditor::make('biography')
                                            ->label('Full Biography')
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('fighter-content-attachments')
                                            ->required(),
                                            
                                        TextInput::make('video_highlight_url')
                                            ->label('Highlight Video URL')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        FileUpload::make('gallery')
                                            ->label('Photo Gallery')
                                            ->multiple()
                                            ->image()
                                            ->directory('fighter-galleries')
                                            ->maxSize(5120),
                                    ]),
                            ]),
                            
                        Tab::make('Professional Details')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('manager')
                                            ->maxLength(255),
                                            
                                        TextInput::make('promoter')
                                            ->maxLength(255),
                                            
                                        TextInput::make('trainer')
                                            ->maxLength(255),
                                            
                                        TextInput::make('gym')
                                            ->maxLength(255),
                                            
                                        DatePicker::make('pro_debut_date')
                                            ->label('Pro Debut Date'),
                                            
                                        TextInput::make('amateur_record')
                                            ->placeholder('e.g. 85-10'),
                                            
                                        TextInput::make('olympic_medals')
                                            ->placeholder('e.g. Gold - 2016'),
                                            
                                        TextInput::make('contract_status')
                                            ->placeholder('e.g. Under contract until 2025'),
                                    ]),
                            ]),
                            
                        Tab::make('Social Media')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('instagram_handle')
                                            ->prefix('@')
                                            ->maxLength(30),
                                            
                                        TextInput::make('twitter_handle')
                                            ->prefix('@')
                                            ->maxLength(30),
                                            
                                        TextInput::make('facebook_url')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        TextInput::make('youtube_channel')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        TextInput::make('website')
                                            ->url()
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_image')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(fn () => asset('images/placeholder.jpg'))
                    ->size(60),
                TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),
                TextColumn::make('nickname')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('weight_class')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('record')
                    ->label('Record')
                    ->formatStateUsing(fn ($record): string => "{$record->wins}-{$record->losses}-{$record->draws}")
                    ->sortable('wins'),
                TextColumn::make('age')
                    ->sortable(),
                TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('championship_title')
                    ->label('Title')
                    ->visible(fn ($record): bool => $record->is_champion)
                    ->limit(30),
            ])
            ->filters([
                SelectFilter::make('is_champion')
                    ->options([
                        '1' => 'Champions Only',
                        '0' => 'Non-Champions',
                    ])
                    ->label('Championship Status'),
                SelectFilter::make('weight_class')
                    ->options([
                        'Heavyweight' => 'Heavyweight',
                        'Cruiserweight' => 'Cruiserweight',
                        'Light Heavyweight' => 'Light Heavyweight',
                        'Super Middleweight' => 'Super Middleweight',
                        'Middleweight' => 'Middleweight',
                        'Super Welterweight' => 'Super Welterweight',
                        'Welterweight' => 'Welterweight',
                        'Super Lightweight' => 'Super Lightweight',
                        'Lightweight' => 'Lightweight',
                        'Super Featherweight' => 'Super Featherweight',
                        'Featherweight' => 'Featherweight',
                        'Super Bantamweight' => 'Super Bantamweight',
                        'Bantamweight' => 'Bantamweight',
                        'Super Flyweight' => 'Super Flyweight',
                        'Flyweight' => 'Flyweight',
                        'Light Flyweight' => 'Light Flyweight',
                        'Minimumweight' => 'Minimumweight',
                    ]),
                SelectFilter::make('country')
                    ->options([
                        'USA' => 'USA',
                        'UK' => 'UK',
                        'Mexico' => 'Mexico',
                        'Canada' => 'Canada',
                        'Japan' => 'Japan',
                        'Russia' => 'Russia',
                        'Brazil' => 'Brazil',
                        'Philippines' => 'Philippines',
                        'Ukraine' => 'Ukraine',
                        'Kazakhstan' => 'Kazakhstan',
                        'Ghana' => 'Ghana',
                        'Nigeria' => 'Nigeria',
                        'South Africa' => 'South Africa',
                        'Kenya' => 'Kenya',
                        'Tanzania' => 'Tanzania',
                        'Uganda' => 'Uganda',
                        'Zambia' => 'Zambia',
                        'Zimbabwe' => 'Zimbabwe',
                        'Egypt' => 'Egypt',
                        'Morocco' => 'Morocco',
                        'Tunisia' => 'Tunisia',
                        'Algeria' => 'Algeria',
                    ])
                    ->searchable(),
                Filter::make('undefeated')
                    ->query(fn (Builder $query): Builder => $query->where('losses', 0))
                    ->label('Undefeated Fighters'),
                Filter::make('top_prospects')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('wins', '>=', 10)
                        ->where('losses', '<=', 1)
                        ->whereNull('championship_title')
                    )
                    ->label('Top Prospects'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_profile')
                    ->label('View Profile')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Fighter $record): string => route('fighters.show', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('weight_class');
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\FightsRelationManager::class,
            RelationManagers\RankingsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFighters::route('/'),
            'create' => Pages\CreateFighter::route('/create'),
            'edit' => Pages\EditFighter::route('/{record}/edit'),
        ];
    }    
}