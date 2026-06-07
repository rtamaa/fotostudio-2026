<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use App\Enums\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('email')
                                    ->required()
                                    ->email()
                                    ->unique(ignoreRecord: true),
                                
                                Forms\Components\TextInput::make('phone')
                                    ->label('No. WhatsApp')
                                    ->tel()
                                    ->maxLength(20),
                                
                                Forms\Components\Select::make('role')
                                    ->options([
                                        UserRole::USER->value => UserRole::USER->label(),
                                        UserRole::ADMIN->value => UserRole::ADMIN->label(),
                                    ])
                                    ->required()
                                    ->default(UserRole::USER->value),
                                
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->minLength(8),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('WhatsApp'),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'primary' => 'admin',
                        'gray' => 'user',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}