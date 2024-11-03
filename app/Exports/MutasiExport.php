<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MutasiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $mutasis;

    public function __construct($mutasis)
    {
        $this->mutasis = $mutasis;
    }

    public function collection()
    {
        return $this->mutasis;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Barang',
            'Nama Barang',
            'Jenis Mutasi',
            'Jumlah',
            'Satuan',
            'Petugas',
            'Keterangan'
        ];
    }

    public function map($mutasi): array
    {
        return [
            $mutasi->tanggal->format('d/m/Y'),
            $mutasi->barang->kode,
            $mutasi->barang->nama_barang,
            strtoupper($mutasi->jenis_mutasi),
            $mutasi->jumlah,
            $mutasi->barang->satuan,
            $mutasi->user->name,
            $mutasi->keterangan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:H1' => ['fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'E2EFDA']
            ]]
        ];
    }
}
