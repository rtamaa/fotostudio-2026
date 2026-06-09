<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\BookingLog;
use App\Models\Booking;
use App\Enums\PaymentStatus;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Enums\BookingStatus;

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
                Tables\Columns\TextColumn::make('order_id')->searchable(),

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

            // =========================
            // 🔥 APPROVE BUTTON (TAMBAHAN)
            // =========================
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(\App\Filament\Admin\Resources\PaymentResource::class)
                            ->approvePayment($record);
                    }),
            ])

            ->defaultSort('created_at', 'desc');
    }

    // =========================
    // 🧠 APPROVE PAYMENT LOGIC
    // =========================
    public function approvePayment(Payment $payment): void
    {
        $payment->update([
            'status' => PaymentStatus::SUCCESS,
            'paid_at' => now(),
        ]);

        $booking = $payment->booking;

        $booking->update([
            'booking_status' => BookingStatus::CONFIRMED,
            'paid_at' => now(),
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'created_by' => Auth::id(),
            'action' => 'payment_approved',
            'description' => 'Admin menyetujui pembayaran & booking dikonfirmasi',
        ]);
    }

    // =========================
    // LOG CREATE PAYMENT
    // =========================
    public static function afterCreate($record): void
    {
        BookingLog::create([
            'booking_id' => $record->booking_id,
            'created_by' => Auth::id(),
            'action' => 'PAYMENT_CREATED',
            'description' => 'Admin membuat data payment',
            'new_data' => [
                'order_id' => $record->order_id,
                'gross_amount' => $record->gross_amount,
                'status' => $record->status,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // =========================
    // LOG UPDATE PAYMENT
    // =========================
    public static function afterSave($record): void
    {
        BookingLog::create([
            'booking_id' => $record->booking_id,
            'created_by' => Auth::id(),
            'action' => 'PAYMENT_UPDATED',
            'description' => 'Admin mengupdate payment',
            'new_data' => [
                'status' => $record->status,
                'paid_at' => $record->paid_at,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
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