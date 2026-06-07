<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Booking;
use App\Enums\PaymentStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Transaksi';

    // =========================
    // FORM (INI YANG KAMU KURANG)
    // =========================
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('booking_id')
                ->label('Booking')
                ->relationship('booking', 'id')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('order_id')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('transaction_id')
                ->nullable(),

            Forms\Components\Select::make('payment_type')
                ->options([
                    'cash' => 'Cash',
                    'midtrans' => 'Midtrans',
                    'transfer' => 'Transfer',
                ])
                ->nullable(),

            Forms\Components\TextInput::make('gross_amount')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('status')
                ->options(collect(PaymentStatus::cases())
                    ->mapWithKeys(fn ($status) => [
                        $status->value => $status->label(),
                    ])->toArray())
                ->default(PaymentStatus::PENDING->value)
                ->required(),

            Forms\Components\DateTimePicker::make('paid_at')
                ->nullable(),
        ]);
    }

    // =========================
    // TABLE
    // =========================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking.user.name')
                    ->label('Pelanggan'),

                Tables\Columns\TextColumn::make('gross_amount')
                    ->money('IDR'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'success',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(
                        fn ($state) =>
                            $state instanceof PaymentStatus
                                ? $state->label()
                                : PaymentStatus::from($state)->label()
                    ),

                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // =========================
    // PAGES
    // =========================
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
        ];
    }
}