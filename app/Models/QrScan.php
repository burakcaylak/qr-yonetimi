<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrScan extends Model
{
    protected $fillable = ['product_id','scanned_at','ip','user_agent','referer'];
    protected $dates = ['scanned_at'];

    public function product() { return $this->belongsTo(Product::class); }
}
