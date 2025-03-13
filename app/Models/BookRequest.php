<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookRequest extends Model
{
    use HasFactory;

    // Use the table name from your migration
    protected $table = 'perminatan_bukus';

    
    public $timestamps = true; 

    // Fill-able fields
    protected $fillable = [
        'id_user', 
        'judul',
        'penulis',
        'kode_buku',
        'penerbit',
        'tahun_terbit',
        'alasan_permintaan',
        'status',
    ];

    // Relationship with User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relationship with BookModel (not Buku)
    public function book(): BelongsTo
    {
        return $this->belongsTo(BookModel::class, 'id_buku');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    
    // Helper method to check if the request is pending
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}