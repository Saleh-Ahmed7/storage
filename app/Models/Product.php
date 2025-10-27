<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
protected $table = 'store'; 

protected $fillable = ['product_name', 'quantity', 'location', 'barcode'];
}
