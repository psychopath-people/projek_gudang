<?php
// app/Http/Controllers/MutasiController.php
namespace App\Http\Controllers;

use App\Exports\MutasiExport;
use App\Models\Mutasi;
use App\Models\Barang;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Mutasi::with(['user', 'barang']);

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter by jenis_mutasi
        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        // Filter by barang
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $mutasis = $query->latest('tanggal')->paginate(10);
        $barangs = Barang::all(); // For filter dropdown

        return view('mutasi.index', compact('mutasis', 'barangs'));
    }

    public function create()
    {
        $barangs = Barang::where('status', 'aktif')->get();
        return view('mutasi.create', compact('barangs'));
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

        // Update stok barang
        if ($request->jenis_mutasi == 'masuk') {
            $barang->stok += $request->jumlah;
        } else {
            if ($barang->stok < $request->jumlah) {
                return back()->withErrors(['jumlah' => 'Stok tidak mencukupi!'])->withInput();
            }
            $barang->stok -= $request->jumlah;
        }

        $barang->save();

        $validated['user_id'] = 1;
        Mutasi::create($validated);



        return redirect()->route('mutasi.index')
            ->with('success', 'Mutasi berhasil dicatat');
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
        $pdf = PDF::loadView('mutasi.report', $data);
        return $pdf->download('laporan-mutasi.pdf');
    }

    return view('mutasi.report', $data);
}


public function scopeMasuk($query)
{
    return $query->where('jenis_mutasi', 'masuk');
}

public function scopeKeluar($query)
{
    return $query->where('jenis_mutasi', 'keluar');
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

    return view('dashboard', $data);
}
public function filter(Request $request)
{
    $query = Mutasi::with(['barang', 'user']);

    // Filter periode
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

    // Filter kategori
    if ($request->filled('kategori')) {
        $query->whereHas('barang', function($q) use ($request) {
            $q->where('kategori', $request->kategori);
        });
    }

    // Filter lokasi
    if ($request->filled('lokasi')) {
        $query->whereHas('barang', function($q) use ($request) {
            $q->where('lokasi', $request->lokasi);
        });
    }

    // Filter jenis mutasi
    if ($request->filled('jenis_mutasi')) {
        $query->where('jenis_mutasi', $request->jenis_mutasi);
    }

    // Filter user
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    $mutasis = $query->latest('tanggal')->paginate(10);

    // Calculate summary
    $summary = [
        'total_transaksi' => $mutasis->total(),
        'total_masuk' => $query->clone()->where('jenis_mutasi', 'masuk')->sum('jumlah'),
        'total_keluar' => $query->clone()->where('jenis_mutasi', 'keluar')->sum('jumlah'),
    ];
    $summary['selisih'] = $summary['total_masuk'] - $summary['total_keluar'];

    // Get filter options
    $kategoris = Barang::distinct()->pluck('kategori');
    $lokasis = Barang::distinct()->pluck('lokasi');
    $users = User::all();

    return view('mutasi.filter', compact('mutasis', 'summary', 'kategoris', 'lokasis', 'users'));
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

    if (request()->wantsJson()) {
        return response()->json([
            'barang' => $barang,
            'mutasis' => $mutasis,
            'summary' => $summary
        ]);
    }

    return view('mutasi.history_barang', compact('barang', 'mutasis', 'summary'));
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

    if (request()->wantsJson()) {
        return response()->json([
            'user' => $user,
            'mutasis' => $mutasis,
            'summary' => $summary
        ]);
    }

    return view('mutasi.history_user', compact('user', 'mutasis', 'summary'));
}

// Method untuk API JSON
public function apiMutasi(Request $request)
{
$query = Mutasi::with(['barang', 'user']);

    // Filter by date range
    if ($request->filled(['start_date', 'end_date'])) {
        $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
    }

    // Filter by barang
    if ($request->filled('barang_id')) {
        $query->where('barang_id', $request->barang_id);
    }

    // Filter by user
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // Filter by jenis_mutasi
    if ($request->filled('jenis_mutasi')) {
        $query->where('jenis_mutasi', $request->jenis_mutasi);
    }

    $mutasis = $query->latest('tanggal')->paginate($request->input('per_page', 10));

    return response()->json($mutasis);
}
public function print(Request $request)
    {
        $query = Mutasi::with(['user', 'barang']);

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $mutasis = $query->latest('tanggal')->get();

        $pdf = FacadePdf::loadView('mutasi.print', [
            'mutasis' => $mutasis,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return $pdf->download('laporan-mutasi.pdf');
    }
    public function export(Request $request)
    {
        return Excel::download(new MutasiExport($request), 'mutasi.xlsx');
    }
}
