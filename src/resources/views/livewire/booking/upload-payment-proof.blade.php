<div>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Upload Bukti Pembayaran</h2>
            
            @if(session('message'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('message') }}</div>
            @endif
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="font-semibold">📋 Informasi Transfer:</p>
                <p class="text-sm mt-2">Bank BCA - 1234567890 a.n Studio Self-Photo</p>
                <p class="text-sm">Total: {{ $booking->package->formatted_price }}</p>
                <p class="text-sm">Booking ID: #{{ $booking->id }}</p>
            </div>
            
            <form wire:submit.prevent="upload">
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Upload Bukti Transfer</label>
                    <input type="file" wire:model="proof" class="w-full border rounded-lg p-2">
                    @error('proof') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Catatan (opsional)</label>
                    <textarea wire:model="notes" rows="3" class="w-full border rounded-lg p-2" 
                              placeholder="Contoh: Transfer dari BCA an. John Doe"></textarea>
                </div>
                
                <button type="submit" wire:loading.attr="disabled"
                        class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700 transition">
                    Kirim Bukti
                </button>
            </form>
        </div>
    </div>
</div>
<div class="max-w-xl mx-auto p-6">

    <h1 class="text-xl font-bold mb-4">
        Upload Bukti Pembayaran
    </h1>

    @if(session()->has('success'))
        <div class="bg-green-100 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <input
        type="file"
        wire:model="proof"
        class="border p-2 w-full"
    >

    <button
        wire:click="save"
        class="mt-4 bg-pink-600 text-white px-4 py-2 rounded"
    >
        Upload
    </button>

</div>