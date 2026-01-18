<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Alamat Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Form Tambah Alamat Baru -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">➕ Tambah Alamat Baru</h3>
                
                <form action="{{ route('addresses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label Alamat</label>
                            <input type="text" name="label" placeholder="Rumah / Kantor / Kos" required
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                            <input type="text" name="recipient" placeholder="Nama lengkap penerima" required
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp / Telepon</label>
                        <input type="text" name="phone" placeholder="08xxxxxxxxxx" required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="3" placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota" required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">
                        💾 Simpan Alamat
                    </button>
                </form>
            </div>

            <!-- Daftar Alamat Tersimpan -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📍 Alamat Tersimpan</h3>
                
                @forelse($addresses as $address)
                    <div class="border border-gray-200 rounded-xl p-4 mb-4 {{ $address->is_default ? 'bg-blue-50 border-blue-300' : '' }}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="font-bold text-gray-800">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span class="ml-2 text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">Utama</span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if(!$address->is_default)
                                    <form action="{{ route('addresses.default', $address->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:underline">Jadikan Utama</button>
                                    </form>
                                @endif
                                <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium">{{ $address->recipient }}</p>
                            <p>📞 {{ $address->phone }}</p>
                            <p class="text-gray-500">{{ $address->address }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">📭</div>
                        <p>Belum ada alamat tersimpan.</p>
                        <p class="text-sm">Tambahkan alamat pertama Anda di atas!</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
