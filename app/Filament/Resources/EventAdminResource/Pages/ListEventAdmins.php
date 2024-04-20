<?php

namespace App\Filament\Resources\EventAdminResource\Pages;

use App\Filament\Resources\EventAdminResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventAdmins extends ListRecords
{
    protected static string $resource = EventAdminResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("New event"),
        ];
    }
}
