<?php

namespace App\Filament\Admin\Resources\BoxerResource\Pages;

use App\Filament\Admin\Resources\BoxerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBoxers extends ListRecords
{
    protected static string $resource = BoxerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 