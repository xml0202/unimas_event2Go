<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyUserResource\Pages;
use App\Filament\Resources\AgencyUserResource\RelationManagers;
use App\Models\AgencyUser;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Agency;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Closure;

class AgencyUserResource extends Resource
{
    protected static ?string $model = AgencyUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    // protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $userId = auth()->id();
        
        $isCreateAgencyUser = function () {
            return Str::contains(request()->url(), 'agency-users/create');
        };
        
    //     $userOptions = $isCreateAgencyUser()
    // ? User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
    //     ->whereNotIn('id', AgencyUser::pluck('user_id'))
    //     ->where('role_id', 3)
    //     ->pluck('name', 'id')
    // : User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
    //     ->where('role_id', 3)->pluck('users.name', 'users.id');
    
        if (auth()->user()->hasRole('Super Admin')) {
            $agencyOptions = Agency::pluck('name', 'id');
        }
        else
        {
            $agencyOptions = Agency::where('admin_id', $userId)->pluck('name', 'id');
        }
        
        $userOptions = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->whereNotIn('id', AgencyUser::pluck('user_id'))
        ->where('role_id', 3)
        ->pluck('name', 'id');
        
        return $form
            ->schema([
                
                Select::make('agency_id')
                    ->label('Agency')
                ->options($agencyOptions)
                ->searchable()->required()->columnSpan(2),
                Radio::make('type')
                    ->label('Type')
                    ->options([
                        'existing' => 'Existing',
                        'new' => 'New',
                    ])->inline()->required()->reactive()->columnSpan(2),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    // ->visible(fn() => auth()->user()->hasRole('Super Admin')), 
                    ->visible(function (Closure $get): string {
                        return $get('type') === 'new';
                    })->columnSpan(2),
                Select::make('user_id')
                    ->label('Name')
                // ->options(User::all()->pluck('name', 'id'))
                    ->options($userOptions)
                    ->visible(function (Closure $get): string {
                        return $get('type') === 'existing';
                    })
                    ->searchable()
                    ->required()->columnSpan(2),
                Forms\Components\Hidden::make('status')->default('1'),
                Forms\Components\Hidden::make('contact')->default('0'),
                // Forms\Components\Toggle::make('status'),
                // Forms\Components\Toggle::make('contact')
                //     ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(191)
                    ->visible(function (Closure $get): string {
                        return $get('type') === 'new';
                    })->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agency.name'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name'),
                // Tables\Columns\IconColumn::make('contact')
                    // ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        
        if ($user->hasRole('Super Admin')) {
            // Super admins can see all data
            return parent::getEloquentQuery();
        }
        else
        {
            return parent::getEloquentQuery()
            ->where('admin_id', auth()->id());
        }
        
        
        
        // return parent::getEloquentQuery()->whereBelongsTo(auth()->user());
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    // protected function handleRecordCreation(array $data): Model
    // {
    //     //insert the student
    //     $record =  static::getModel()::create($data);

    //     // Create a new Guardian model instance
    //     $user = new User();
    //     $user->first_name = $data['guardian_fname'];
    //     $user->last_name = $data['guardian_lname'];
    //     $user->gender = $data['guardian_gender'];
    //     $user->email = $data['guardian_email'];
    //     $user->contact_no = $data['guardian_contact'];

    //     // Assuming 'student_id' is the foreign key linking to students
    //     $user->student_id = $record->student_id; 

    //     // Save the Guardian model to insert the data
    //     $user->save();


    //     return $record;
    // }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencyUsers::route('/'),
            'create' => Pages\CreateAgencyUser::route('/create'),
            'edit' => Pages\EditAgencyUser::route('/{record}/edit'),
        ];
    }    
}
