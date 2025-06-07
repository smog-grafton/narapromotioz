<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BoxerResource\Pages;
use App\Models\Boxer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoxerResource extends Resource
{
    protected static ?string $model = Boxer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'Boxers';
    
    protected static ?string $modelLabel = 'Boxer';
    
    protected static ?string $pluralModelLabel = 'Boxers';
    
    protected static ?string $navigationGroup = 'Boxing Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nickname')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->required(),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('nationality')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('weight_class')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Profile')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_image')
                            ->image()
                            ->directory('boxers/profile-images')
                            ->maxSize(5120)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('bio')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Record')
                    ->schema([
                        Forms\Components\TextInput::make('wins')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('losses')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('draws')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('knockouts')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('manager_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('manager_contact')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Social Media')
                    ->schema([
                        Forms\Components\TextInput::make('instagram')
                            ->prefix('@')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('twitter')
                            ->prefix('@')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('facebook')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('youtube')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->circular(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('nickname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight_class')
                    ->searchable(),
                Tables\Columns\TextColumn::make('record')
                    ->label('Record (W-L-D)')
                    ->formatStateUsing(fn (Boxer $record): string => "{$record->wins}-{$record->losses}-{$record->draws}"),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
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
                Tables\Filters\SelectFilter::make('weight_class')
                    ->options(fn (): array => Boxer::query()
                        ->distinct()
                        ->pluck('weight_class', 'weight_class')
                        ->filter()
                        ->toArray()
                    ),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('fights')
                        ->label('Fight Records')
                        ->url(fn (Boxer $record): string => static::getUrl('fights', ['record' => $record]))
                        ->icon('heroicon-o-clipboard-document-list'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleFeatured')
                        ->label('Toggle Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn (Builder $query) => $query->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('Toggle Active')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListBoxers::route('/'),
            'create' => Pages\CreateBoxer::route('/create'),
            'view' => Pages\ViewBoxer::route('/{record}'),
            'edit' => Pages\EditBoxer::route('/{record}/edit'),
            'fights' => Pages\ListFightRecords::route('/{record}/fights'),
        ];
    }
} 