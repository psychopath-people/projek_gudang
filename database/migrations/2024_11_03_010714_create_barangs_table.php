<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama_barang');
            $table->string('kategori');
            $table->string('lokasi');
            $table->integer('stok')->default(0);
            $table->decimal('harga', 15, 2)->default(0);
            $table->string('satuan')->nullable(); // pcs, box, unit, dll
            $table->text('deskripsi')->nullable();
            $table->string('supplier')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
