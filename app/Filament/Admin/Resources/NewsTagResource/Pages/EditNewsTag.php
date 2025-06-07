<?php

namespace App\Filament\Admin\Resources\NewsTagResource\Pages;

use App\Filament\Admin\Resources\NewsTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsTag extends EditRecord
{
    protected static string $resource = NewsTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
