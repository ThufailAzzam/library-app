<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Popularitas extends Model
{
    protected $table = 'bukus'; // Use the bukus table as the base

    // Method to get popular books based on jumlah_pinjam
    public static function getPopularBooks()
    {
        return self::orderBy('jumlah_pinjam', 'desc')->get(); // Order by jumlah_pinjam
    }
}