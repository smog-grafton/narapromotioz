<?php

namespace App\Filament\Admin\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Boxer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class LatestBoxers extends BaseWidget
{
    protected static ?string $heading = 'Recently Added Boxers';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 6;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Boxer::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(asset('assets/images/boxers/default.jpg'))
                    ->size(40),
                    
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Boxer $record): string => $record->nickname ? '"' . $record->nickname . '"' : ''),
                    
                TextColumn::make('weight_class')
                    ->label('Weight Class')
                    ->badge()
                    ->color('info'),
                    
                TextColumn::make('record')
                    ->label('Record')
                    ->getStateUsing(fn (Boxer $record): string => "{$record->wins}-{$record->losses}-{$record->draws}")
                    ->description(fn (Boxer $record): string => "{$record->knockouts} KOs"),
                    
                TextColumn::make('win_rate')
                    ->label('Win Rate')
                    ->suffix('%')
                    ->color(fn (Boxer $record): string => $record->win_rate >= 80 ? 'success' : ($record->win_rate >= 60 ? 'warning' : 'danger'))
                    ->weight('bold'),
                    
                TextColumn::make('knockout_rate')
                    ->label('KO Rate')
                    ->suffix('%')
                    ->color(fn (Boxer $record): string => $record->knockout_rate >= 70 ? 'danger' : ($record->knockout_rate >= 50 ? 'warning' : 'info')),
                    
                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->getStateUsing(fn (Boxer $record): string => $record->is_active ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Inactive',
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => 'Active',
                        'heroicon-m-x-circle' => 'Inactive',
                    ]),
                    
                BadgeColumn::make('is_featured')
                    ->label('Featured')
                    ->getStateUsing(fn (Boxer $record): string => $record->is_featured ? 'Yes' : 'No')
                    ->colors([
                        'warning' => 'Yes',
                        'gray' => 'No',
                    ])
                    ->icons([
                        'heroicon-m-star' => 'Yes',
                        'heroicon-m-minus' => 'No',
                    ]),
                    
                TextColumn::make('hometown')
                    ->label('Hometown')
                    ->limit(30)
                    ->tooltip(function (Boxer $record): ?string {
                        return $record->hometown . ', ' . $record->country;
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(function (Boxer $record): string {
                        return $record->created_at->format('F j, Y \a\t g:i A');
                    }),
            ])
            ->actions([
                Action::make('view_profile')
                    ->label('View Profile')
                    ->icon('heroicon-m-user')
                    ->color('info')
                    ->url(fn (Boxer $record): string => route('boxers.show', $record->slug))
                    ->openUrlInNewTab(),
                    
                Action::make('toggle_featured')
                    ->label(fn (Boxer $record): string => $record->is_featured ? 'Remove from Featured' : 'Add to Featured')
                    ->icon(fn (Boxer $record): string => $record->is_featured ? 'heroicon-m-star-slash' : 'heroicon-m-star')
                    ->color(fn (Boxer $record): string => $record->is_featured ? 'warning' : 'success')
                    ->action(function (Boxer $record) {
                        $record->update(['is_featured' => !$record->is_featured]);
                        $this->dispatch('$refresh');
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn (Boxer $record): string => $record->is_featured ? 'Remove from Featured' : 'Add to Featured')
                    ->modalDescription(fn (Boxer $record): string => $record->is_featured 
                        ? 'Are you sure you want to remove this boxer from featured?' 
                        : 'Are you sure you want to add this boxer to featured?'),
                        
                Action::make('toggle_status')
                    ->label(fn (Boxer $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn (Boxer $record): string => $record->is_active ? 'heroicon-m-x-circle' : 'heroicon-m-check-circle')
                    ->color(fn (Boxer $record): string => $record->is_active ? 'danger' : 'success')
                    ->action(function (Boxer $record) {
                        $record->update(['is_active' => !$record->is_active]);
                        $this->dispatch('$refresh');
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn (Boxer $record): string => $record->is_active ? 'Deactivate Boxer' : 'Activate Boxer')
                    ->modalDescription(fn (Boxer $record): string => $record->is_active 
                        ? 'Are you sure you want to deactivate this boxer?' 
                        : 'Are you sure you want to activate this boxer?'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No boxers found')
            ->emptyStateDescription('No boxers have been added yet.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
