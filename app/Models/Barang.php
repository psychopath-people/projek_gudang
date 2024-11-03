<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kode',
        'nama_barang',
        'kategori',
        'lokasi',
        'stok',
        'harga',
        'satuan',
        'deskripsi',
        'supplier',
        'foto',
        'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    public function getKodeBarangAttribute()
    {
        return strtoupper($this->kode);
    }
    public function mutasis()
    {
    return $this->hasMany(Mutasi::class);
    }

}
