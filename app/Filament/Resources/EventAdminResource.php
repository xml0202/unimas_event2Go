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
use Filament\Forms\Components\FileUpload;
use Closure;
use App\Models\Category;
use Filament\Tables\Filters\SelectFilter;

class EventAdminResource extends Resource
{
    protected static ?string $model = EventAdmin::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    public static ?string $pluralModelLabel = 'Events';
    
    protected static ?string $navigationGroup = 'Content';
    
    protected static ?int $navigationSort = 1;
    
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('status')->default('1'),
                Forms\Components\TextInput::make('title')->required()->columnSpan(2),
                FileUpload::make('attachment')->multiple()->image()->visibility('public'),
                Forms\Components\Textarea::make('introduction')->required()->columnSpan(2),
                Forms\Components\TextInput::make('organized_by')->label('Organized By')->required()->columnSpan(2),
                Forms\Components\TextInput::make('in_collaboration')->label('In Collaboration')->required()->columnSpan(2),
                Forms\Components\TextInput::make('program_objective')->label('Program Objective')->required()->columnSpan(2),
                Forms\Components\TextInput::make('program_impact')->label('Program Impact')->required()->columnSpan(2),
                Forms\Components\TextInput::make('invitation')->label('Invitation')->required()->columnSpan(2),
                Forms\Components\DateTimePicker::make('start_datetime')->label('Start Date')->columnSpan(1)->required(),
                Forms\Components\DateTimePicker::make('end_datetime')->label('End Date')->columnSpan(1)->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->columnSpan(1)
                    ->required(),
                Forms\Components\Select::make('category')->label('Category')
                    ->options(Category::all()->pluck('category_name', 'category_name'))
                    ->columnSpan(1)->required(),
                Forms\Components\TextInput::make('max_user')->label('Max Users')->reactive()->columnSpan(1)->required(),
                Forms\Components\TextInput::make('earn_points')->label('Earn Points')->columnSpan(1)->required(),
                Forms\Components\TextInput::make('location')
                    ->label('Location')
                    ->columnSpan(2)
                    ->required(),
                // Forms\Components\Toggle::make('approval')->label('Approval')
                //     ->reactive()
                //     ->afterStateUpdated(function ($state, callable $set) {
                //         $state ? $set('status', 2) : $set('status', '1');
                    // }),
            ]);         
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(Category::all()->pluck('category_name', 'category_name'))
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'view' => Pages\ViewEventAdmin::route('/{record}'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('admin_id', auth()->id());
    }

}
