<?php

namespace App\Filament\Admin\Resources\NewsCommentResource\Pages;

use App\Filament\Admin\Resources\NewsCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsComments extends ListRecords
{
    protected static string $resource = NewsCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
