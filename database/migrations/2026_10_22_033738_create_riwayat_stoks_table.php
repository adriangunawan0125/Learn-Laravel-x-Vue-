
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_stoks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produk_id')
                ->constrained('produks')
                ->restrictOnDelete();

            $table->enum('jenis', ['masuk', 'keluar']);
            $table->unsignedInteger('jumlah');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->index(['jenis', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_stoks');
    }
};
