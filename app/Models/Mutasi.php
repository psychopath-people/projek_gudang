<?php
// app/Models/Mutasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barang_id',
        'tanggal',
        'jenis_mutasi',
        'jumlah',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
