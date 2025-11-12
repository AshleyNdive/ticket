<?php

namespace App\Filament\Resources;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('guard_name')
                    ->default('web')
                    ->required(),
                Select::make('permissions')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->label('Permissions'),
            ]);
    }



public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->searchable()
                ->label('Role ID'), // Modified Label

            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable()
                ->label('Role Title'),

            Tables\Columns\TextColumn::make('guard_name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('created_at')->dateTime(),

            Tables\Columns\TextColumn::make('permissions.name')
                ->label('Permissions')
                ->listWithLineBreaks()
                ->bulleted()
                ->searchable(),
        ])

        ->filters([
            
            Tables\Filters\SelectFilter::make('permissions')
                ->relationship('permissions', 'name')
                ->label('Filter by Permission')
                ->preload(),
        ])

        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
}

    public static function shouldRegisterNavigation(): bool{
        return true;
    }


    public static function canViewAny(): bool
    {
       return auth()->user()?->hasAnyRole(['admin', 'superadmin']);
    }


    public static function canEdit(Model $record): bool
    {

        return auth()->user()->can('edit roles');
    }


    public static function canDelete(Model $record): bool
    {

        return auth()->user()->can('delete roles') && $record->name !== 'superadmin';
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
