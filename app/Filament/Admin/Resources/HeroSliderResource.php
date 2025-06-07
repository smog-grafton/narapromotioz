<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HeroSliderResource\Pages;
use App\Filament\Admin\Resources\HeroSliderResource\RelationManagers;
use App\Models\HeroSlider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HeroSliderResource extends Resource
{
    protected static ?string $model = HeroSlider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $pluralModelLabel = 'Hero Sliders';

    protected static ?string $navigationGroup = 'Website Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Slider Content')
                    ->description('Configure the hero slider content and appearance')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Background Image')
                            ->image()
                            ->directory('hero-slider')
                            ->required()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth(1920)
                            ->imageResizeTargetHeight(839)
                            ->helperText('Upload a background image for the slider. Recommended size: 1920x839 pixels'),

                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->placeholder('Get your <span>body fitness</span>')
                            ->helperText('You can use HTML tags like <span> for styling')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('subtitle')
                            ->label('Subtitle')
                            ->required()
                            ->placeholder('Achieve your health and fitness goals at your stage')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Call to Action')
                    ->schema([
                        Forms\Components\TextInput::make('cta_text')
                            ->label('Button Text')
                            ->placeholder('Discover Classes')
                            ->default('Discover Classes'),

                        Forms\Components\TextInput::make('cta_link')
                            ->label('Button Link')
                            ->url()
                            ->placeholder('https://example.com or #modal')
                            ->default('#'),
                    ])->columns(2),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers display first'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Whether this slider should be displayed'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->size(80),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->html()
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Subtitle')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\TextColumn::make('cta_text')
                    ->label('Button Text')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All sliders')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
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
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListHeroSliders::route('/'),
            'create' => Pages\CreateHeroSlider::route('/create'),
            'edit' => Pages\EditHeroSlider::route('/{record}/edit'),
        ];
    }
}
