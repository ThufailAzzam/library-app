<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    use HasFactory;

    protected $table = 'kategori_bukus';
    protected $guarded = [];

    public function books()
    {
        return $this->belongsToMany(BookModel::class, 'buku_kategori', 'kategori_id', 'buku_id');
    }
    
}