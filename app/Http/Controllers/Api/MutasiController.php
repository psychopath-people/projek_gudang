<?php

namespace App\Http\Controllers\API;

use App\Models\Mutasi;
use App\Models\Barang;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;


class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Mutasi::with(['user', 'barang']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $mutasis = $query->latest('tanggal')->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $mutasis
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal' => 'required|date',
            'jenis_mutasi' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jenis_mutasi == 'masuk') {
            $barang->stok += $request->jumlah;
        } else {
            if ($barang->stok < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi!'
                ], 400);
            }
            $barang->stok -= $request->jumlah;
        }

        $barang->save();
        $validated['user_id'] = auth()->id();  // Gunakan ID user yang sedang login

        $mutasi = Mutasi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mutasi berhasil dicatat',
            'data' => $mutasi
        ], 201);
    }

    public function report(Request $request)
    {
        $query = Mutasi::with(['user', 'barang']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $data = [
            'total_masuk' => $query->clone()->where('jenis_mutasi', 'masuk')->sum('jumlah'),
            'total_keluar' => $query->clone()->where('jenis_mutasi', 'keluar')->sum('jumlah'),
            'mutasis' => $query->orderBy('tanggal', 'desc')->get()
        ];

        if ($request->type === 'pdf') {
            $pdf = FacadePdf::loadView('mutasi.report', $data);
            return $pdf->download('laporan-mutasi.pdf');
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function dashboard()
    {
        $data = [
            'total_barang' => Barang::count(),
            'total_stok' => Barang::sum('stok'),
            'mutasi_hari_ini' => Mutasi::whereDate('tanggal', today())->count(),
            'barang_kosong' => Barang::where('stok', 0)->count(),
            'grafik_mutasi' => Mutasi::selectRaw('DATE(tanggal) as date,
                                                  SUM(CASE WHEN jenis_mutasi = "masuk" THEN jumlah ELSE 0 END) as total_masuk,
                                                  SUM(CASE WHEN jenis_mutasi = "keluar" THEN jumlah ELSE 0 END) as total_keluar')
                                    ->groupBy('date')
                                    ->orderBy('date', 'desc')
                                    ->limit(7)
                                    ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function filter(Request $request)
    {
        $query = Mutasi::with(['barang', 'user']);

        switch($request->periode) {
            case 'hari_ini':
                $query->whereDate('tanggal', today());
                break;
            case 'minggu_ini':
                $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'bulan_ini':
                $query->whereMonth('tanggal', now()->month)
                      ->whereYear('tanggal', now()->year);
                break;
            case 'custom':
                if ($request->filled(['start_date', 'end_date'])) {
                    $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
                }
                break;
        }

        if ($request->filled('kategori')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('kategori', $request->kategori);
            });
        }

        if ($request->filled('lokasi')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('lokasi', $request->lokasi);
            });
        }

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $mutasis = $query->latest('tanggal')->paginate(10);

        $summary = [
            'total_transaksi' => $mutasis->total(),
            'total_masuk' => $query->clone()->where('jenis_mutasi', 'masuk')->sum('jumlah'),
            'total_keluar' => $query->clone()->where('jenis_mutasi', 'keluar')->sum('jumlah'),
            'selisih' => $summary['total_masuk'] - $summary['total_keluar']
        ];

        return response()->json([
            'success' => true,
            'data' => $mutasis,
            'summary' => $summary
        ]);
    }

    public function historyBarang($id)
    {
        $barang = Barang::findOrFail($id);
        $mutasis = Mutasi::with('user')
            ->where('barang_id', $id)
            ->latest('tanggal')
            ->paginate(10);

        $summary = [
            'total_masuk' => $mutasis->where('jenis_mutasi', 'masuk')->sum('jumlah'),
            'total_keluar' => $mutasis->where('jenis_mutasi', 'keluar')->sum('jumlah'),
            'stok_saat_ini' => $barang->stok
        ];

        return response()->json([
            'success' => true,
            'barang' => $barang,
            'mutasis' => $mutasis,
            'summary' => $summary
        ]);
    }

    public function historyUser($id)
    {
        $user = User::findOrFail($id);
        $mutasis = Mutasi::with('barang')
            ->where('user_id', $id)
            ->latest('tanggal')
            ->paginate(10);

        $summary = [
            'total_transaksi' => $mutasis->count(),
            'total_barang_masuk' => $mutasis->where('jenis_mutasi', 'masuk')->sum('jumlah'),
            'total_barang_keluar' => $mutasis->where('jenis_mutasi', 'keluar')->sum('jumlah')
        ];

        return response()->json([
            'success' => true,
            'user' => $user,
            'mutasis' => $mutasis,
            'summary' => $summary
        ]);
    }
}
