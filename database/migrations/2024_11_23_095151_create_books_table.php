<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->foreignId('penerbit_id')->nullable()->constrained('penerbits');
            $table->foreignId('kategori_id')->nullable()->constrained('kategories');
            $table->integer('tahun');
            $table->string('isbn')->unique()->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('bahasa')->default('Indonesia');
            $table->integer('stok')->default(1);
            $table->boolean('is_available')->default(true);
            $table->integer('total_dipinjam')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
