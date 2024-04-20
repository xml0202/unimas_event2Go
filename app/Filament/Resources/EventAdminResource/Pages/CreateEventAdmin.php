<?php

namespace App\Filament\Resources\EventAdminResource\Pages;

use App\Filament\Resources\EventAdminResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventAdmin extends CreateRecord
{
    protected static string $resource = EventAdminResource::class;
    
    // protected static ?string $breadcrumb = 'Create';
    protected static ?string $title = 'Create Event';
}
