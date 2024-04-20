<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;


class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $admin_id = DB::table('users')
                    ->join('agency_users', 'users.id', '=', 'agency_users.user_id')
                    ->where('user_id', auth()->id())
                    ->select('agency_users.admin_id')
                    ->first();
        
        $data['user_id'] = auth()->id();
        $data['admin_id'] = $admin_id ? $admin_id->admin_id : null;
        return $data;
    }
}
