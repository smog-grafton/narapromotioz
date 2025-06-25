<?php

namespace App\Filament\Admin\Resources\EventTicketResource\Pages;

use App\Filament\Admin\Resources\EventTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventTicket extends EditRecord
{
    protected static string $resource = EventTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
