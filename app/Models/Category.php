<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name']; // Kategori adı sonradan değişebilir, ürün bağlandığında ürünün category_id'si değişmeyecek (UI/Controller engeli)
    public function products() { return $this->hasMany(Product::class); }
}
