<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('booking_id')
                ->relationship('booking', 'id')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('order_id')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('transaction_id')
                ->maxLength(255),

            Forms\Components\TextInput::make('payment_type')
                ->maxLength(255),

            Forms\Components\TextInput::make('gross_amount')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('status')
                ->options(
                    collect(PaymentStatus::cases())
                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                )
                ->required(),

            Forms\Components\DateTimePicker::make('paid_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking.user.name')
                    ->label('Pelanggan'),

                Tables\Columns\TextColumn::make('gross_amount')
                    ->money('IDR'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'success',
                        'warning' => 'warning',
                        'danger'  => 'danger',
                    ])
                    ->formatStateUsing(function ($state) {
                        return $state instanceof PaymentStatus
                            ? $state->label()
                            : PaymentStatus::from($state)->label();
                    }),

                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit'   => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}