<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_id')
                    ->relationship('event', 'title')
                    ->required()->columnSpan(2),
                Forms\Components\Textarea::make('comment')
                    ->required()->columnSpan(2),
                Forms\Components\Hidden::make('user_id')->default(auth()->id()),
                // Forms\Components\Select::make('user_id')
                //     ->relationship('user', 'name')
                //     ->required(),
                Forms\Components\TextInput::make('parent_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title'),
                Tables\Columns\TextColumn::make('comment'),
                Tables\Columns\TextColumn::make('user.name'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                // Tables\Columns\TextColumn::make('parent_id'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('Super Admin')) {
            return parent::getEloquentQuery();
        }
        
        return parent::getEloquentQuery()
        ->select('comments.id as comment_id', 'comments.*')
        ->join('events', 'comments.event_id', '=', 'events.id')
        ->where(function ($query) {
            $query->where('events.admin_id', auth()->id())
                ->orWhere('events.user_id', auth()->id());
        });
    }
}
