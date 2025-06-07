<?php

namespace App\Filament\Admin\Resources\BoxerResource\Pages;

use App\Filament\Admin\Resources\BoxerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBoxer extends EditRecord
{
    protected static string $resource = BoxerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('fights')
                ->label('Fight Records')
                ->url(fn () => $this->getResource()::getUrl('fights', ['record' => $this->record]))
                ->icon('heroicon-o-clipboard-document-list'),
        ];
    }
} 