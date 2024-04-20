<?php

namespace App\Filament\Resources\AgencyUserResource\Pages;

use App\Filament\Resources\AgencyUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgencyUsers extends ListRecords
{
    protected static string $resource = AgencyUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
