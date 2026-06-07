<div>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-white">Detail Booking</h1>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold 
                        @if($booking->booking_status->value == 'confirmed') bg-green-500 
                        @elseif($booking->booking_status->value == 'waiting_confirmation') bg-yellow-500 
                        @else bg-red-500 @endif">
                        {{ $booking->booking_status->label() }}
                    </span>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Booking ID</p>
                        <p class="font-mono">#{{ $booking->id }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Paket</p>
                        <p class="font-semibold">{{ $booking->package->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Tanggal & Jam</p>
                        <p class="font-semibold">{{ $booking->booking_date->format('d M Y') }}</p>
                        <p class="text-gray-600">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-500 text-sm">Total Pembayaran</p>
                        <p class="font-bold text-2xl text-pink-600">{{ $booking->package->formatted_price }}</p>
                    </div>
                </div>

                @if($booking->special_request)
                <div>
                    <h2 class="text-lg font-bold mb-2">📝 Permintaan Khusus</h2>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-gray-700">{{ $booking->special_request }}</p>
                    </div>
                </div>
                @endif

                @if($booking->payment_proof)
                <div>
                    <h2 class="text-lg font-bold mb-2">📎 Bukti Pembayaran</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <a href="{{ $booking->payment_proof_url }}" target="_blank" class="text-pink-600 hover:underline">Lihat Bukti Transfer</a>
                    </div>
                </div>
                @endif

                <div class="flex gap-3">
                    <a href="{{ route('booking.history') }}" class="text-gray-600 hover:text-pink-600">← Kembali ke Riwayat</a>
                    
                    @if($booking->booking_status->canBeCancelled())
                        <button wire:click="cancelBooking" onclick="return confirm('Yakin ingin membatalkan?')"
                                class="ml-auto text-red-600 hover:text-red-800">
                            Batalkan Booking
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>