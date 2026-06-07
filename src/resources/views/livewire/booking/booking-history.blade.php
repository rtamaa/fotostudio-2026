<div>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-6">
                <h1 class="text-2xl font-bold text-white">Riwayat Booking</h1>
            </div>

            <div class="p-6">

                <div class="flex gap-2 mb-6">
                    <button wire:click="$set('statusFilter', 'all')">
                        Semua
                    </button>

                    <button wire:click="$set('statusFilter', 'confirmed')">
                        Confirmed
                    </button>

                    <button wire:click="$set('statusFilter', 'waiting_confirmation')">
                        Menunggu
                    </button>
                </div>

                @forelse($bookings as $booking)

                    <div class="border rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start">

                            <div>
                                <p class="text-sm text-gray-500">
                                    #{{ $booking->id }}
                                </p>

                                <h3 class="font-semibold">
                                    {{ $booking->package?->name }}
                                </h3>

                                <p class="text-gray-600">
                                    {{ $booking->booking_date?->format('d M Y') }}
                                    •
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                </p>

                                <p class="font-semibold text-pink-600">
                                    {{ $booking->package?->formatted_price }}
                                </p>
                            </div>

                            <div class="text-right">

                                @php
                                    $status = $booking->booking_status->value;
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs

                                    @if($status === 'confirmed')
                                        bg-green-100 text-green-800
                                    @elseif($status === 'waiting_confirmation')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($status === 'completed')
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ $booking->booking_status->label() }}
                                </span>

                                @if(
                                    in_array(
                                        $booking->booking_status->value,
                                        ['pending', 'waiting_confirmation']
                                    )
                                )
                                    <div class="mt-2">
                                        <button
                                            wire:click="cancelBooking({{ $booking->id }})"
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            Batalkan
                                        </button>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>

                @empty

                    <div class="text-center py-10 text-gray-500">
                        Belum ada riwayat booking.
                    </div>

                @endforelse

            </div>
        </div>
    </div>
</div>