<?php

namespace App\Filament\Admin\Resources\NewsCommentResource\Pages;

use App\Filament\Admin\Resources\NewsCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsComment extends CreateRecord
{
    protected static string $resource = NewsCommentResource::class;
}
