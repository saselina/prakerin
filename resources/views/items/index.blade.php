<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
            📦 Daftar Barang
        </h1>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto bg-white text-gray-900 p-6 rounded-lg shadow">

    {{-- ✅ Tambahin pesan sukses login di sini --}}
            @if (session('status') === 'login-success')
                <div class="mb-4 text-green-600 font-semibold">
                    You're logged in!
                </div>
            @endif


<div class="mb-6">
    <form id="filterForm" method="GET" action="{{ route('items.index') }}"
          class="flex flex-wrap justify-center items-center bg-white p-4 rounded-xl shadow-md border border-gray-200 max-w-6xl mx-auto gap-5">

        {{-- 🔍 Pencarian --}}
        <div class="flex items-center flex-grow max-w-lg -ml-6"> {{-- 🔹 digeser sedikit ke kiri --}}
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari barang..."
                   class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition shadow-sm">
        </div>

        {{-- 🏢 Gedung --}}
        <select id="buildingSelect" name="building_id"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            <option value="">Gedung</option>
            @foreach ($buildings as $building)
                <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                    {{ $building->name }}
                </option>
            @endforeach
        </select>

        {{-- 🚪 Ruangan --}}
        <select id="roomSelect" name="room_id"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            <option value="">Ruangan</option>
            @if (request('building_id'))
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                @endforeach
            @endif
        </select>

        {{-- 🏷️ Kategori --}}
        <select name="category_id"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            <option value="">Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        {{-- 🔄 Reset --}}
        <a href="{{ route('items.index') }}"
           class="text-gray-600 hover:text-gray-800 text-sm underline">
            Reset
        </a>
    </form>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');
    const buildingSelect = document.getElementById('buildingSelect');
    const roomSelect = document.getElementById('roomSelect');
    const searchInput = form.querySelector('input[name="search"]');

    // 🏢 Saat gedung berubah
    buildingSelect.addEventListener('change', function () {
        const buildingId = this.value;
        roomSelect.innerHTML = '<option value="">Memuat...</option>';

        if (buildingId) {
            fetch('/get-rooms/' + buildingId)
                .then(response => response.json())
                .then(rooms => {
                    let options = '<option value="">Ruangan</option>';
                    rooms.forEach(room => {
                        options += `<option value="${room.id}">${room.name}</option>`;
                    });
                    roomSelect.innerHTML = options;

                    // 🔄 Langsung kirim form
                    form.submit();
                })
                .catch(() => {
                    roomSelect.innerHTML = '<option value="">Gagal memuat ruangan</option>';
                });
        } else {
            // ❌ Kalau gedung dikosongkan, ruangan ikut dikosongkan
            roomSelect.innerHTML = '<option value="">Ruangan</option>';
            form.submit();
        }
    });

    // 🚪 Saat ruangan atau kategori berubah → langsung submit
    form.querySelectorAll('select[name="room_id"], select[name="category_id"]').forEach(select => {
        select.addEventListener('change', () => form.submit());
    });

    // 🔍 Search otomatis
    let typingTimer;
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => form.submit(), 800);
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');

    // 🔄 Submit otomatis ketika dropdown berubah
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => form.submit());
    });

    // 🔍 Search otomatis setelah 0.8 detik berhenti mengetik
    const searchInput = form.querySelector('input[name="search"]');
    let typingTimer;
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => form.submit(), 800); // 0.8 detik setelah selesai ngetik
        });
    }

    // 🚪 Update ruangan sesuai gedung (AJAX seperti sebelumnya)
    const buildingSelect = document.getElementById('buildingSelect');
    const roomSelect = document.getElementById('roomSelect');
    if (buildingSelect) {
        buildingSelect.addEventListener('change', function () {
            const buildingId = this.value;
            roomSelect.innerHTML = '<option value="">Memuat...</option>';

            if (buildingId) {
                fetch('/get-rooms/' + buildingId)
                    .then(response => response.json())
                    .then(rooms => {
                        let options = '<option value="">Ruangan</option>';
                        rooms.forEach(room => {
                            options += `<option value="${room.id}">${room.name}</option>`;
                        });
                        roomSelect.innerHTML = options;
                    })
                    .catch(() => {
                        roomSelect.innerHTML = '<option value="">Gagal memuat ruangan</option>';
                    });
            } else {
                roomSelect.innerHTML = '<option value="">Ruangan</option>';
            }

            // 🟢 langsung kirim form juga
            form.submit();
        });
    }
});
</script>



{{-- 🧠 Script Dinamis Ruangan Berdasarkan Gedung --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buildingSelect = document.getElementById('buildingSelect');
    const roomSelect = document.getElementById('roomSelect');

    if (buildingSelect) {
        buildingSelect.addEventListener('change', function () {
            const buildingId = this.value;
            roomSelect.innerHTML = '<option value="">Memuat...</option>';

            if (buildingId) {
                fetch('/get-rooms/' + buildingId)
                    .then(response => response.json())
                    .then(rooms => {
                        let options = '<option value="">Ruangan</option>';
                        rooms.forEach(room => {
                            options += `<option value="${room.id}">${room.name}</option>`;
                        });
                        roomSelect.innerHTML = options;
                    })
                    .catch(() => {
                        roomSelect.innerHTML = '<option value="">Gagal memuat ruangan</option>';
                    });
            } else {
                roomSelect.innerHTML = '<option value="">Ruangan</option>';
            }
        });
    }
});
</script>

<style>
    /* Kunci ukuran area sortir */
    form.flex {
        min-height: 48px; /* biar gak menyusut */
        align-items: center;
    }

    /* Pastikan semua input dan select punya ukuran konsisten */
    input[type="text"],
    select {
        min-width: 150px;
        height: 38px;
        box-sizing: border-box;
    }

    /* Tombol biar sejajar sempurna */
    button[type="submit"] {
        height: 38px;
        line-height: 1;
    }

    /* Kunci tinggi tabel agar border tidak "melompat" */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    tbody {
        min-height: 300px; /* kunci tinggi tabel minimal */
        display: table-row-group;
    }

    Supaya kalau tabel kosong, tetap tinggi dan border tetap
    td[colspan] {
        height: 200px;
    }
</style>

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
