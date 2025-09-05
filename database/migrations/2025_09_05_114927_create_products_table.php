<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');                        // değişmeyecek
            $table->string('pdf_path');                    // storage path (public)
            $table->string('qr_code')->nullable()->unique(); // sabit kod (örn. ULID)
            $table->boolean('qr_active')->default(true);   // aktif/pasif
            $table->timestamps();

            $table->unique(['name', 'category_id']);       // aynı kategoride aynı isim tek olsun
        });
    }
    public function down(): void {
        Schema::dropIfExists('products');
    }
};

