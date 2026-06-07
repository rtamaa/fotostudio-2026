<div>
    <div class="bg-white rounded-lg p-6 shadow">
        <h3 class="text-xl font-bold mb-4">Detail Booking</h3>
        <div class="grid grid-cols-2 gap-4">
            <div><p class="text-gray-500">Paket</p><p class="font-semibold">{{ $package->name }}</p></div>
            <div><p class="text-gray-500">Harga</p><p class="text-pink-600 font-bold">{{ $package->formatted_price }}</p></div>
            <div><p class="text-gray-500">Tanggal</p><p class="font-semibold">{{ \Carbon\Carbon::parse($bookingDate)->format('d M Y') }}</p></div>
            <div><p class="text-gray-500">Jam</p><p class="font-semibold">{{ $startTime }}</p></div>
        </div>
        <textarea wire:model="specialRequest" rows="3" class="w-full border rounded-lg p-2" placeholder="Permintaan khusus"></textarea>
        <button wire:click="processBooking" class="w-full bg-pink-500 text-white py-3 rounded-lg mt-4">Booking Sekarang</button>
    </div>
</div>