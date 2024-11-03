<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Mutasi;
use App\Exports\MutasiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class LaporanController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('laporan.index', compact('barangs'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis_laporan' => 'required|in:mutasi,stok',
            'format' => 'required|in:excel,pdf'
        ]);

        if ($request->jenis_laporan == 'mutasi') {
            return $this->generateMutasiReport($request);
        } else {
            return $this->generateStokReport($request);
        }
    }

    private function generateMutasiReport($request)
    {
        $mutasis = Mutasi::with(['barang', 'user'])
            ->whereBetween('tanggal', [$request->start_date, $request->end_date])
            ->when($request->barang_id, function($query) use ($request) {
                $query->where('barang_id', $request->barang_id);
            })
            ->get();

        $summary = [
            'total_masuk' => $mutasis->where('jenis_mutasi', 'masuk')->sum('jumlah'),
            'total_keluar' => $mutasis->where('jenis_mutasi', 'keluar')->sum('jumlah'),
            'periode' => [
                'start' => $request->start_date,
                'end' => $request->end_date
            ]
        ];

        if ($request->format == 'excel') {
            return Excel::download(
                new MutasiExport($mutasis),
                'laporan-mutasi-' . date('Y-m-d') . '.xlsx'
            );
        } else {
            $pdf = PDF::loadView('laporan.mutasi_pdf', [
                'mutasis' => $mutasis,
                'summary' => $summary
            ]);
            return $pdf->download('laporan-mutasi-' . date('Y-m-d') . '.pdf');
        }
    }

    private function generateStokReport($request)
    {
        $barangs = Barang::withCount([
            'mutasis as total_masuk' => function($query) use ($request) {
                $query->where('jenis_mutasi', 'masuk')
                      ->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            },
            'mutasis as total_keluar' => function($query) use ($request) {
                $query->where('jenis_mutasi', 'keluar')
                      ->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
        ])->get();

        if ($request->format == 'excel') {
            return Excel::download(
                new StokExport($barangs, $request->all()),
                'laporan-stok-' . date('Y-m-d') . '.xlsx'
            );
        } else {
            $pdf = PDF::loadView('laporan.stok_pdf', [
                'barangs' => $barangs,
                'periode' => [
                    'start' => $request->start_date,
                    'end' => $request->end_date
                ]
            ]);
            return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
        }
    }
}
