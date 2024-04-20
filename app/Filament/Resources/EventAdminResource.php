<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventAdminResource\Pages;
use App\Filament\Resources\EventAdminResource\RelationManagers;
use App\Models\EventAdmin;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventAdminResource extends Resource
{
    protected static ?string $model = EventAdmin::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    public static ?string $pluralModelLabel = 'Event';
    
    protected static ?string $navigationGroup = 'Content';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEventAdmins::route('/'),
            'create' => Pages\CreateEventAdmin::route('/create'),
            'edit' => Pages\EditEventAdmin::route('/{record}/edit'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('admin_id', auth()->id());
    }
}
