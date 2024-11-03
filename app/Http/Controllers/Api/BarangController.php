<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // List all barang items with optional search
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->search) {
            $query->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode', 'like', "%{$request->search}%");
        }

        $barangs = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $barangs
        ]);
    }

    // Store a new barang
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'lokasi' => 'required',
            'stok' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'nullable',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        $barang = Barang::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang created successfully',
            'data' => $barang
        ], 201);
    }

    // Show a specific barang by ID
    public function show($id)
    {
        $barang = Barang::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    // Update a specific barang by ID
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|unique:barangs,kode,' . $id,
            'nama_barang' => 'required',
            'kategori' => 'required',
            'lokasi' => 'required',
            'stok' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'nullable',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        $barang->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang updated successfully',
            'data' => $barang
        ]);
    }

    // Delete a specific barang by ID
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang deleted successfully'
        ]);
    }

    // Show history of a specific barang
    public function history($id)
    {
        $barang = Barang::findOrFail($id);
        $mutasis = $barang->mutasis()
                         ->with('user')
                         ->latest()
                         ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'barang' => $barang,
                'mutasis' => $mutasis,
                'summary' => [
                    'total_masuk' => $barang->mutasis()->where('jenis_mutasi', 'masuk')->sum('jumlah'),
                    'total_keluar' => $barang->mutasis()->where('jenis_mutasi', 'keluar')->sum('jumlah'),
                    'stok_saat_ini' => $barang->stok
                ]
            ]
        ]);
    }
}
