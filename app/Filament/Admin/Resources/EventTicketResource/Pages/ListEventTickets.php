<?php

namespace App\Filament\Admin\Resources\EventTicketResource\Pages;

use App\Filament\Admin\Resources\EventTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventTickets extends ListRecords
{
    protected static string $resource = EventTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
