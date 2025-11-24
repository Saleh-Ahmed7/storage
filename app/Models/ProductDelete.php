<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDelete extends Model
{
 
    protected $table = 'product_delete';

    protected $fillable = [
        'product_id',
        'name',
        'reason',
        'action_type',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

