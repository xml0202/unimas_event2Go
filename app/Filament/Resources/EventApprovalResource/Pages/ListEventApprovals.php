<?php

namespace App\Filament\Resources\EventApprovalResource\Pages;

use App\Filament\Resources\EventApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventApprovals extends ListRecords
{
    protected static string $resource = EventApprovalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
