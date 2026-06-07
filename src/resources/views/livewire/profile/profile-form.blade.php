<div>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Profil Saya</h2>
            <div class="space-y-4">
                <div><label>Nama</label><input type="text" wire:model="name" class="w-full border rounded p-2"></div>
                <div><label>Email</label><input type="email" wire:model="email" class="w-full border rounded p-2"></div>
                <div><label>No WhatsApp</label><input type="tel" wire:model="phone" class="w-full border rounded p-2"></div>
                <button wire:click="updateProfile" class="bg-pink-500 text-white px-4 py-2 rounded">Update Profil</button>
            </div>
            <div class="border-t pt-4 mt-4">
                <h3 class="font-bold mb-2">Ganti Password</h3>
                <div class="space-y-2">
                    <input type="password" wire:model="current_password" placeholder="Password saat ini" class="w-full border rounded p-2">
                    <input type="password" wire:model="new_password" placeholder="Password baru" class="w-full border rounded p-2">
                    <button wire:click="updatePassword" class="bg-gray-600 text-white px-4 py-2 rounded">Ganti Password</button>
                </div>
            </div>
        </div>
    </div>
</div>