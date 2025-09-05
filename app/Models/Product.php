<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'pdf_path', 'slug']; // ← slug eklendi
    protected $casts = ['qr_active' => 'boolean'];

    public function category() { return $this->belongsTo(Category::class); }
    public function scans()    { return $this->hasMany(QrScan::class); }

    public function qrRedirectUrl(): string
    {
        return $this->qr_code ? route('qr.redirect', $this->qr_code) : '';
    }
}
