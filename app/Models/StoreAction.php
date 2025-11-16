<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAction extends Model
{
    use HasFactory;

    protected $table = 'store_actions';

    protected $fillable = [
        'product_id',
        'action_type',
        'quantity_changed',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
