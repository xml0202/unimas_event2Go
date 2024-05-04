<?php

namespace App\Filament\Resources\AgencyUserResource\Pages;

use App\Filament\Resources\AgencyUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreateAgencyUser extends CreateRecord
{
    protected static string $resource = AgencyUserResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Agency User Created';
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['name']))
        {
            $data['user_id'] = 1;
        }
        $data['admin_id'] = auth()->user()->id;
        
        return $data;
    }
    
    protected function handleRecordCreation(array $data): Model
    {
        /** @var \App\Models\User $user */
        $agency_user = parent::handleRecordCreation($data);
        // if (auth()->user()->hasRole('Super Admin'))
        // {
        //     $user->assignRole('Agency');
        // }
        // $user->assignRole('admin');
        
        if (isset($data['name']))
        {
            $anotherModelData = [
                'name' => $data['name'], 
                'email' => $data['email'], 
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ];
        
            $user = User::create($anotherModelData);
            $user->assignRole('Agency');
            $agency_user->user_id = $user->id;
            $agency_user->save();
        }
        
        

        return $agency_user;
    }
}
