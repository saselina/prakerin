<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
            📦 Daftar Barang
        </h1>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto bg-white text-gray-900 p-6 rounded-lg shadow">


            {{-- ✅ Notifikasi sukses --}}
            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 bg-green-500 text-white px-4 py-2 rounded"
                >
                    ✅ <strong>{{ session('success') }}</strong>
                </div>
            @endif

            {{-- 🔎 Form Search & Sort --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
    <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap items-center gap-3 mb-4">

    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari barang..."
           class="border rounded px-3 py-2 text-sm w-60">

    <select name="building_id" class="border rounded px-3 py-2 text-sm">
        <option value="">Gedung</option>
        @foreach ($buildings as $building)
            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                {{ $building->name }}
            </option>
        @endforeach
    </select>

    <select name="room_id" class="border rounded px-3 py-2 text-sm">
        <option value="">Ruangan</option>
        @foreach ($rooms as $room)
            <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                {{ $room->name }}
            </option>
        @endforeach
    </select>

    <select name="category_id" class="border rounded px-3 py-2 text-sm">
        <option value="">Kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    {{-- <select name="sort" class="border rounded px-3 py-2 text-sm">
        <option value="">Urutkan berdasarkan...</option>
        <option value="building" {{ request('sort') == 'building' ? 'selected' : '' }}>Gedung</option>
        <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>Kategori</option>
        <option value="quantity" {{ request('sort') == 'quantity' ? 'selected' : '' }}>Jumlah</option>
    </select> --}}

    <button type="submit" class="bg-blue-500 text-gray-600 px-4 py-2 rounded hover:bg-blue-600">
        Terapkan
    </button>

    <a href="{{ route('items.index') }}" class="ml-2 text-gray-600 hover:underline">Reset</a>
</form>
</div>


            {{-- ✅ Tombol tambah --}}
            <div class="mb-4">
                <a href="{{ route('items.create') }}" class="btn btn-success">➕ Tambah Barang</a>
            </div>

            {{-- ✅ Tabel data --}}
<div class="overflow-x-auto mt-6">
    <table class="min-w-full border border-gray-300 rounded-lg text-left text-lg"> {{-- 🔹 text-lg di sini --}}
        <thead class="bg-gray-100 text-gray-900">
            <tr>
                <th class="px-6 py-3 border-b text-center font-semibold">No</th>
                <th class="px-6 py-3 border-b font-semibold">Gedung</th>
                <th class="px-6 py-3 border-b font-semibold">Ruangan</th>
                <th class="px-6 py-3 border-b font-semibold">Kategori</th>
                <th class="px-6 py-3 border-b font-semibold">Nama Barang</th>
                <th class="px-6 py-3 border-b font-semibold">Deskripsi</th>
                <th class="px-6 py-3 border-b text-center font-semibold">Jumlah</th>
                <th class="px-6 py-3 border-b text-center font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white text-gray-800">
            @forelse ($items as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 border-b text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-3 border-b">{{ $item->room->building->name ?? '-' }}</td>
                    <td class="px-6 py-3 border-b">{{ $item->room->name ?? '-' }}</td>
                    <td class="px-6 py-3 border-b">{{ $item->category->name ?? '-' }}</td>
                    <td class="px-6 py-3 border-b font-medium">{{ $item->name }}</td>
                    <td class="px-6 py-3 border-b">{{ $item->description ?? '-' }}</td>
                    <td class="px-6 py-3 border-b text-center font-semibold">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 border-b text-center">
                        <a href="{{ route('items.edit', $item->id) }}"
                           class="inline-block text-yellow-600 hover:text-yellow-800 text-xl"> {{-- ✏️ icon lebih besar --}}
                            ✏️
                        </a>
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 ml-2 text-xl">🗑️</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">
                        Belum ada data barang
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



    {{-- Script AJAX-nya masih sama --}}
    <script>
        $('#buildingSelect').on('change', function () {
            const buildingId = $(this).val();
            const roomSelect = $('#roomSelect');
            roomSelect.html('<option>Memuat...</option>');

            if (buildingId) {
                $.get('/get-rooms/' + buildingId, function (rooms) {
                    let options = '<option value="">-- Semua Ruangan --</option>';
                    rooms.forEach(room => {
                        options += `<option value="${room.id}">${room.name}</option>`;
                    });
                    roomSelect.html(options);
                });
            } else {
                roomSelect.html('<option value="">-- Semua Ruangan --</option>');
            }
        });
    </script>
</x-app-layout>
