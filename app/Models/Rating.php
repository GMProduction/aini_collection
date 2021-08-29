<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
      'id_produk',
      'ulasan',
      'id_user',
      'rating',
      'id_keranjang'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
