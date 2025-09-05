<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Mevcut kayıtları doldur (benzersizlik için id ekliyoruz)
        $products = DB::table('products')->select('id', 'name')->get();
        foreach ($products as $p) {
            $slug = Str::slug($p->name);
            // aynı isim varsa çakışmasın
            $exists = DB::table('products')->where('slug', $slug)->where('id', '!=', $p->id)->exists();
            if ($exists) $slug .= '-'.$p->id;

            DB::table('products')->where('id', $p->id)->update(['slug' => $slug]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
