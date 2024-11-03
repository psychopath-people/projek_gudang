<?php
namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run()
    {
        $barangs = [
            [
                'kode' => 'BRG001',
                'nama_barang' => 'Laptop Asus',
                'kategori' => 'Elektronik',
                'lokasi' => 'Rak-A1',
                'stok' => 10,
                'harga' => 8000000.00,
                'satuan' => 'unit',
                'deskripsi' => 'Laptop Asus ROG Gaming',
                'supplier' => 'PT Asus Indonesia',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'BRG002',
                'nama_barang' => 'Mouse Wireless',
                'kategori' => 'Aksesoris',
                'lokasi' => 'Rak-A2',
                'stok' => 20,
                'harga' => 150000.00,
                'satuan' => 'pcs',
                'deskripsi' => 'Mouse Wireless Logitech',
                'supplier' => 'PT Logitech Indonesia',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'BRG003',
                'nama_barang' => 'Keyboard Mechanical',
                'kategori' => 'Aksesoris',
                'lokasi' => 'Rak-A2',
                'stok' => 15,
                'harga' => 500000.00,
                'satuan' => 'pcs',
                'deskripsi' => 'Keyboard Mechanical RGB',
                'supplier' => 'PT Gaming Gear',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
