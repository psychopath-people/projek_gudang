<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangController extends Controller
{

        public function index(Request $request)
    {
        $query = Barang::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('kode', 'LIKE', "%{$search}%")
                  ->orWhere('kategori', 'LIKE', "%{$search}%")
                  ->orWhere('lokasi', 'LIKE', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        // Get unique kategoris and lokasis for filter dropdowns
        $kategoris = Barang::distinct()->pluck('kategori')->sort();
        $lokasis = Barang::distinct()->pluck('lokasi')->sort();

        $barangs = $query->latest()->paginate(10);

        // Append query parameters to pagination links
        $barangs->appends($request->all());

        return view('barang.index', compact('barangs', 'kategoris', 'lokasis'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:barangs,kode',
            'nama_barang' => 'required|min:3',
            'kategori' => 'required',
            'lokasi' => 'required',
            'stok' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'nullable',
            'supplier' => 'nullable',
            'deskripsi' => 'nullable',
            'status' => 'required|in:aktif,tidak_aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('images/barang'), $fotoName);
            $validated['foto'] = 'images/barang/' . $fotoName;
        }

        Barang::create($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|min:3',
            'kode' => 'required|unique:barangs,kode,' . $barang->id,
            'kategori' => 'required',
            'lokasi' => 'required',
            'stok' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'nullable',
            'deskripsi' => 'nullable',
            'supplier' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($barang->foto && file_exists(public_path($barang->foto))) {
                unlink(public_path($barang->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('images/barang'), $fotoName);
            $validated['foto'] = 'images/barang/' . $fotoName;
        }

        $barang->update($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        // Hapus foto jika ada
        if ($barang->foto && file_exists(public_path($barang->foto))) {
            unlink(public_path($barang->foto));
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }
}
