<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    
    protected static ?string $navigationGroup = 'Website Settings';
    
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationBadge(): ?string
    {
        return 'Settings';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Site Name')
                                            ->required()
                                            ->maxLength(100),
                                            
                                        TextInput::make('contact_email')
                                            ->label('Contact Email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),
                                            
                                        TextInput::make('contact_phone')
                                            ->label('Contact Phone')
                                            ->tel()
                                            ->maxLength(20),
                                            
                                        TextInput::make('address')
                                            ->label('Business Address')
                                            ->maxLength(255),
                                            
                                        FileUpload::make('site_logo')
                                            ->label('Site Logo')
                                            ->image()
                                            ->directory('site-assets')
                                            ->maxSize(2048),
                                            
                                        FileUpload::make('site_favicon')
                                            ->label('Site Favicon')
                                            ->image()
                                            ->directory('site-assets')
                                            ->maxSize(512),
                                            
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(250)
                                            ->rows(2)
                                            ->helperText('Used for SEO, 150-250 characters recommended')
                                            ->columnSpanFull(),
                                        
                                        Textarea::make('footer_text')
                                            ->label('Footer Text')
                                            ->maxLength(500)
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Tab::make('Theme & Design')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('dark_mode_enabled')
                                            ->label('Enable Dark Mode Option')
                                            ->default(true)
                                            ->helperText('Allow users to switch between light and dark mode'),
                                            
                                        ColorPicker::make('primary_color')
                                            ->label('Primary Color')
                                            ->default('#00ADEF'), // Sky Blue
                                            
                                        ColorPicker::make('secondary_color')
                                            ->label('Secondary Color')
                                            ->default('#E63946'), // Action Red
                                            
                                        ColorPicker::make('dark_color')
                                            ->label('Dark Color')
                                            ->default('#212529'), // Dark Navy
                                            
                                        FileUpload::make('home_banner')
                                            ->label('Home Page Banner')
                                            ->image()
                                            ->directory('banners')
                                            ->maxSize(5120),
                                            
                                        Toggle::make('show_upcoming_events_banner')
                                            ->label('Show Upcoming Events Banner')
                                            ->default(true),
                                    ]),
                            ]),
                            
                        Tab::make('Social Media')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('facebook_url')
                                            ->label('Facebook URL')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        TextInput::make('twitter_url')
                                            ->label('Twitter URL')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        TextInput::make('instagram_url')
                                            ->label('Instagram URL')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        TextInput::make('youtube_url')
                                            ->label('YouTube URL')
                                            ->url()
                                            ->maxLength(255),
                                            
                                        Toggle::make('show_social_share_buttons')
                                            ->label('Show Social Sharing Buttons')
                                            ->default(true),
                                            
                                        Toggle::make('show_social_follow_buttons')
                                            ->label('Show Social Follow Buttons')
                                            ->default(true),
                                    ]),
                            ]),
                            
                        Tab::make('Payment & Tickets')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('enable_ticket_sales')
                                            ->label('Enable Ticket Sales')
                                            ->default(true),
                                            
                                        Toggle::make('enable_stream_sales')
                                            ->label('Enable Live Stream Sales')
                                            ->default(true),
                                            
                                        TextInput::make('currency')
                                            ->label('Default Currency')
                                            ->default('USD')
                                            ->maxLength(3),
                                            
                                        TextInput::make('currency_symbol')
                                            ->label('Currency Symbol')
                                            ->default('$')
                                            ->maxLength(5),
                                            
                                        Toggle::make('enable_pesapal')
                                            ->label('Enable PesaPal Payments')
                                            ->default(true),
                                            
                                        Toggle::make('enable_airtel_money')
                                            ->label('Enable Airtel Money Payments')
                                            ->default(false),
                                            
                                        Toggle::make('enable_mtn_money')
                                            ->label('Enable MTN Money Payments')
                                            ->default(false),
                                            
                                        TextInput::make('tax_rate')
                                            ->label('Tax Rate (%)')
                                            ->numeric()
                                            ->default(0)
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100),
                                    ]),
                            ]),
                            
                        Tab::make('Analytics & SEO')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('google_analytics_id')
                                            ->label('Google Analytics ID')
                                            ->maxLength(20)
                                            ->placeholder('UA-XXXXX-Y or G-XXXXXXXX'),
                                            
                                        Toggle::make('enable_cookie_notice')
                                            ->label('Show Cookie Notice')
                                            ->default(true),
                                    ]),
                                    
                                Textarea::make('google_analytics_code')
                                    ->label('Google Analytics Tracking Code')
                                    ->rows(3)
                                    ->placeholder('<!-- Paste your Google Analytics code here -->'),
                                    
                                Textarea::make('custom_header_code')
                                    ->label('Custom Header Code')
                                    ->rows(3)
                                    ->placeholder('<!-- Custom code to be added to the <head> section -->'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Setting Key')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Value')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('group')
                    ->label('Group')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y • H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'design' => 'Theme & Design',
                        'social' => 'Social Media',
                        'payment' => 'Payment & Tickets',
                        'analytics' => 'Analytics & SEO',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for settings
            ])
            ->defaultSort('key', 'asc');
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
            'index' => Pages\ManageSettings::route('/'),
        ];
    }    
}