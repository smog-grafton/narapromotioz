<?php

namespace App\Filament\Admin\Resources\NewsTagResource\Pages;

use App\Filament\Admin\Resources\NewsTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsTag extends CreateRecord
{
    protected static string $resource = NewsTagResource::class;
}
