<?php

namespace App;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use FormatDate;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }
}
