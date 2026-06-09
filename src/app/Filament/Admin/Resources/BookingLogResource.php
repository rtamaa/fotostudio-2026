<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BookingLogResource\Pages;
use App\Models\BookingLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingLogResource extends Resource
{
    protected static ?string $model = BookingLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('action')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('old_data')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('new_data')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('ip_address')
                    ->maxLength(255),

                Forms\Components\TextInput::make('user_agent')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_id')
                    ->label('Booking')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('User')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBookingLogs::route('/'),
            'create' => Pages\CreateBookingLog::route('/create'),
            'edit' => Pages\EditBookingLog::route('/{record}/edit'),
        ];
    }
}