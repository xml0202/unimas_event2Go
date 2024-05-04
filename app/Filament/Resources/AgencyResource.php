<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Filament\Resources\AgencyResource\RelationManagers;
use App\Models\Agency;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        $adminId = auth()->id();
        
        return $form
            ->schema([
                Forms\Components\Hidden::make('admin_id')->default($adminId),
                Forms\Components\TextInput::make('name')
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Hidden::make('status')->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
        
        
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\AgencyUsersRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
            'view' => Pages\ViewAgency::route('/{record}'),
        ];
    }    
}
