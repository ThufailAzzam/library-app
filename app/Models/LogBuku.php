<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBuku extends Model
{
    use HasFactory;

    protected $table = 'logbukus';
    protected $guarded = [];

    public function book()
    {
        return $this->belongsTo(BookModel::class, 'book_id');
    }
}