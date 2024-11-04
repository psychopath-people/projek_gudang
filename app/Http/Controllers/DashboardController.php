<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Exports\MutasiExport;
use App\Models\Barang;
use App\Models\User;
use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            $data = [
                'user' => $user,
                'total_barang' => Barang::count(),
                'total_stok' => Barang::sum('stok'),
                'mutasi_hari_ini' => Mutasi::whereDate('tanggal', today())->count(),
                'barang_kosong' => Barang::where('stok', 0)->count(),

                // Barang dengan stok minimum
                'barang_minimum' => Barang::where('stok', '<=', 5)
                                        ->orderBy('stok')
                                        ->limit(5)
                                        ->get(),

                // Mutasi terbaru
                'mutasi_terbaru' => Mutasi::with(['barang', 'user'])
                                        ->latest('tanggal')
                                        ->limit(5)
                                        ->get(),

                // Data untuk grafik
                'grafik_mutasi' => Mutasi::selectRaw('DATE(tanggal) as date,
                                                    SUM(CASE WHEN jenis_mutasi = "masuk" THEN jumlah ELSE 0 END) as total_masuk,
                                                    SUM(CASE WHEN jenis_mutasi = "keluar" THEN jumlah ELSE 0 END) as total_keluar')
                                        ->whereDate('tanggal', '>=', now()->subDays(7))
                                        ->groupBy('date')
                                        ->orderBy('date', 'desc')
                                        ->get()
            ];

            return view('dashboard', $data);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function export(Request $request)
    {
        $mutasis = Mutasi::with(['barang', 'user'])
            ->when($request->start_date && $request->end_date, function($query) use ($request) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            })
            ->when($request->jenis_mutasi, function($query) use ($request) {
                $query->where('jenis_mutasi', $request->jenis_mutasi);
            })
            ->when($request->barang_id, function($query) use ($request) {
                $query->where('barang_id', $request->barang_id);
            })
            ->get();

        return Excel::download(new MutasiExport($mutasis), 'mutasi.xlsx');
    }
    // public function index()
    // {
    //     return view('dashboard', [
    //         'user' => Auth::user()
    //     ]);
    // }
}
