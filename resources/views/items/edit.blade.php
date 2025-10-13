<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-4">✏️ Edit Barang</h1>

    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('items.update', $item->id) }}" method="POST">
      @csrf
      @method('PUT')

      {{-- 1️⃣ GEDUNG --}}
      <div class="mb-3">
        <label class="form-label">Gedung</label>
        <select id="buildingSelect" class="form-select" required>
          <option value="">-- Pilih Gedung --</option>
          @foreach ($buildings as $building)
            <option value="{{ $building->id }}" {{ $item->room->building_id == $building->id ? 'selected' : '' }}>
              {{ $building->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- 2️⃣ RUANGAN --}}
      <div class="mb-3">
        <label class="form-label">Ruangan</label>
        <select name="room_id" id="roomSelect" class="form-select" required>
          <option value="">-- Pilih Ruangan --</option>
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}" {{ $item->room_id == $room->id ? 'selected' : '' }}>
              {{ $room->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- 3️⃣ KATEGORI --}}
      <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- 4️⃣ NAMA BARANG --}}
      <div class="mb-3">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="name" class="form-control" required value="{{ $item->name }}">
      </div>

      {{-- 5️⃣ DESKRIPSI --}}
      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ $item->description }}</textarea>
      </div>

      {{-- 6️⃣ JUMLAH --}}
      <div class="mb-3">
        <label class="form-label">Jumlah Barang</label>
        <input type="number" name="quantity" class="form-control" required min="1" value="{{ $item->quantity }}">
      </div>

      <button type="submit" class="btn btn-primary">💾 Update</button>
      <a href="{{ route('items.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>

  <script>
    // Fungsi AJAX saat ganti gedung
    $('#buildingSelect').on('change', function () {
      const buildingId = $(this).val();
      const roomSelect = $('#roomSelect');
      roomSelect.html('<option value="">Memuat...</option>');

      if (buildingId) {
        $.ajax({
          url: '/get-rooms/' + buildingId,
          type: 'GET',
          success: function (rooms) {
            let options = '<option value="">-- Pilih Ruangan --</option>';
            rooms.forEach(room => {
              options += `<option value="${room.id}">${room.name}</option>`;
            });
            roomSelect.html(options);
          },
          error: function () {
            roomSelect.html('<option value="">Gagal memuat ruangan</option>');
          }
        });
      } else {
        roomSelect.html('<option value="">-- Pilih Ruangan --</option>');
      }
    });
  </script>
</body>
</html>
