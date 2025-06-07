<?php

namespace App\Filament\Admin\Resources\HeroSliderResource\Pages;

use App\Filament\Admin\Resources\HeroSliderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeroSliders extends ListRecords
{
    protected static string $resource = HeroSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
