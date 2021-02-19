<?php

namespace App;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use FormatDate;

    protected $guarded = [];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
