<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Gestion des utilisateurs';

    protected static ?string $navigationLabel = 'Utilisateurs';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations de base')
                    ->description('Informations personnelles de l\'utilisateur')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom complet')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('email')
                            ->label('Adresse email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),

                Section::make('Sécurité')
                    ->description('Paramètres de sécurité et d\'accès')
                    ->schema([
                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),

                        Toggle::make('is_admin')
                            ->label('Administrateur')
                            ->required()
                            ->default(false)
                            ->helperText('Cocher pour donner les droits d\'administrateur'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_admin')
                    ->label('Statut Admin')
                    ->options([
                        true => 'Administrateurs',
                        false => 'Utilisateurs normaux',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (User $record) {
                        // Empêcher la suppression de soi-même
                        if ($record->id === auth()->id()) {
                            throw new \Exception('Vous ne pouvez pas supprimer votre propre compte.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Empêcher la suppression de soi-même
                            foreach ($records as $record) {
                                if ($record->id === auth()->id()) {
                                    throw new \Exception('Vous ne pouvez pas supprimer votre propre compte.');
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Ajouter des relations si nécessaire
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
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * Vérifier si l'utilisateur actuel peut accéder à cette ressource
     */
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Vérifier si l'utilisateur actuel peut créer cette ressource
     */
    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Vérifier si l'utilisateur actuel peut voir cette ressource
     */
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }
}