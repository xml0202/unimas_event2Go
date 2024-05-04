<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Settings';
    protected static bool $shouldRegisterNavigation = true;
    
    // public static function shouldRegisterNavigation(): bool
    // {
    //     if (Auth::check()) {
    //         $user = Auth::user();
    //         if ($user->hasRole('Super Admin')) {
    //             // Hide navigation for users with the 'restricted_role'
    //             return false;
    //         }
    //     }
        
    //     return static::$shouldRegisterNavigation;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(191),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (Page $livewire) => ($livewire instanceof CreateUser))
                    ->maxLength(255)
                    ->default('password')
                    ->hiddenOn('create'),
                Hidden::make('password')->hiddenOn('edit'),
                Hidden::make('roles')->visible(fn() => auth()->user()->hasRole('Super Admin')),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')->preload()
                    ->hidden(fn() => auth()->user()->hasRole('Admin')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                // Tables\Columns\TextColumn::make('email_verified_at')
                    // ->dateTime()->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                    // ->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
    
        // Check if the user is a super admin
        if ($user->hasRole('Super Admin')) {
            // Super admins can see all data
            return parent::getEloquentQuery();
        }
    
        // If the user is an admin
        if ($user->hasRole('Admin')) {
            // Admins can see users with role 'agency' and 'user' only
            return parent::getEloquentQuery()->whereHas('roles', function ($query) {
                $query->whereIn('name', ['User']);
            });
        }
    
        // If the user is from an agency
        if ($user->hasRole('Agency')) {
            // Agency users can see users with role 'user' only
            return parent::getEloquentQuery()->whereHas('roles', function ($query) {
                $query->where('name', 'User');
            });
        }
    
        // Default behavior, restrict access to no data
        return parent::getEloquentQuery()->where('id', null);
    }
}
