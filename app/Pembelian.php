<?php

namespace App;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use FormatDate;

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
