<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan semua item
    public function index(Request $request)
{
    $buildings = Building::all();
    $rooms = Room::all();

    $query = Item::with(['room.building', 'category']);

    // Filter berdasarkan gedung
    if ($request->building_id) {
        $query->whereHas('room.building', function($q) use ($request) {
            $q->where('id', $request->building_id);
        });

        // Ambil hanya ruangan dari gedung terpilih
        $rooms = Room::where('building_id', $request->building_id)->get();
    }

    // Filter berdasarkan ruangan
    if ($request->room_id) {
        $query->where('room_id', $request->room_id);
    }

    $items = $query->get();

    return view('items.index', compact('items', 'buildings', 'rooms'));
}


    // Menampilkan form tambah item
    public function create()
    {
        $categories = Category::all();
        $buildings = Building::all(); // ambil data gedung
        return view('items.create', compact('categories', 'buildings'));
    }

    // Ambil ruangan berdasarkan gedung (AJAX)
    public function getRooms($building_id)
    {
        $rooms = Room::where('building_id', $building_id)->get();
        return response()->json($rooms);
    }

    // Simpan data baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Data berhasil ditambahkan!');
    }

    // Form edit item
    public function edit(Item $item)
    {
        $categories = Category::all();
        $buildings = Building::all();
        $rooms = Room::where('building_id', $item->room->building_id)->get();
        return view('items.edit', compact('item', 'categories', 'buildings', 'rooms'));
    }

    // Update data item
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Hapus item
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Data berhasil dihapus!');
    }
}
