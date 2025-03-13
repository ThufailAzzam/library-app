<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Forms\Components\HasManyRepeater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookModel extends Model
{
    use HasFactory;
    protected $table = 'bukus';
    protected $guarded = [];
    protected $fillable = [
        'judul',
        'penulis',
        'kode_buku',
        'penerbit',
        'tahun_terbit',
        'stok',
        'harga_buku',
        'request_id', 
    ];

    public function borrow()
    {
        return $this->hasMany(Borrow::class, 'bukus_id');
    }

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class, 'id_buku');
    }

    

    public function logBuku()
    {
        return $this->hasMany(LogBuku::class, 'book_id');
    }


    public function borrowBook()
    {
        if ($this->stok > 0) {
            $this->stok -= 1; // Decrease stock
            $this->save();
            return true;
        }
        return false;
    }

    public function returnBook()
    {
        $this->stok += 1; // Increase stock
        $this->save();
    }

    public function isAvailable()
    {
        return $this->stok > 0;
    }
    
    public function mobil()
    {
        return $this->belongsToMany(Mobil::class, 'mobil_book', 'book_id', 'mobil_id');
    }

    public function categories()
    {
        return $this->belongsToMany(KategoriBuku::class, 'buku_kategori', 'buku_id', 'kategori_id');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(BookRequest::class, 'request_id');
    }
}
