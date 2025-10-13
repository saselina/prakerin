<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-4">📦 Daftar Barang</h1>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif


    {{-- Tombol tambah --}}
    <div class="mb-3">
      <a href="{{ route('items.create') }}" class="btn btn-success">➕ Tambah Barang</a>
    </div>

    {{-- Tabel data --}}
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark text-center">
        <tr>
          <th>No</th>
          <th>Gedung</th>
          <th>Ruangan</th>
          <th>Kategori</th>
          <th>Nama Barang</th>
          <th>Deskripsi</th>
          <th>Jumlah</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($items as $index => $item)
          <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->room->building->name ?? '-' }}</td>
            <td>{{ $item->room->name ?? '-' }}</td>
            <td>{{ $item->category->name ?? '-' }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->description ?? '-' }}</td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-center">
              <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">✏️</a>
              <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data barang</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <script>
    // Saat pilih gedung, otomatis ambil ruangan dari database
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
</body>
</html>
