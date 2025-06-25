<?php

namespace App\Filament\Admin\Resources\EventTicketResource\Pages;

use App\Filament\Admin\Resources\EventTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventTicket extends CreateRecord
{
    protected static string $resource = EventTicketResource::class;
}
