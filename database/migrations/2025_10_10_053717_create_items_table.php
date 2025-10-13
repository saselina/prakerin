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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
             $table->string('name'); // nama barang
            $table->text('description')->nullable(); // deskripsi barang
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // relasi ke kategori
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // relasi ke ruangan
            $table->integer('quantity')->default(1); // jumlah barang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
