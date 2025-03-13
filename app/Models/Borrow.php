<?php

namespace App\Models;

use Filament\Forms\Components\HasManyRepeater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
class Borrow extends Model
{
    use HasFactory;
    protected $table = 'peminjamans';
    protected $guarded = [];

    public function bukus()
    {
        return $this->belongsTo(BookModel::class);
    }

    public static function borrowBook($bukus_id, $nama_peminjam, $borrow_date, $due_date)
    {
        $book = BookModel::find($bukus_id);

        if ($book && $book->stok > 0) {
            // Decrease the stock
            $book->stok -= 1;
            $book->save();
            

            // Create a borrow record
            return self::create([
                'bukus_id' => $bukus_id,
                'nama_peminjam' => $nama_peminjam,
                'borrow_date' => $borrow_date,
                'due_date' => $due_date,
                
                'status' => 'active',
            ]);
        }

        return null; // No stock available
        
    }
    /**
     * Return a book and update the stock.
     */
    public function returnBook($return_date, $condition = null)
{
    $book = $this->bukus;
    
    if ($book) {
        // Calculate fine based on the condition of the book
        $fine = 0;

        if ($condition === 'rusak') {
            // If the book is returned in a damaged state, set the fine to the full price of the book
            $fine = $book->harga_buku;
        } else {
            // Calculate fine if the book is returned late
            $dueDate = $this->due_date;

            if ($return_date > $dueDate) {
                // Calculate the number of hours late
                $hoursLate = $return_date->diffInHours($dueDate);
                // Calculate fine: 10% of the book price + 1% for each hour late
                $fine = ($book->harga_buku * 0.10) + ($hoursLate * ($book->harga_buku * 0.01));
            }
        }

        // Increase the stock
        $book->stok += 1;
        
        $book->save();
        $book->increment('jumlah_pinjam');
        

        // Update the borrow record
        $this->update([
            'return_date' => $return_date,
            'status' => 'returned',
            'fine' => $fine,
            'condition' => $condition,
        ]);
        
    }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($borrow) {
            // Set the status to 'active' when creating a new borrowing record
            $borrow->status = 'active';
            
        });

        

        static::retrieved(function ($borrow) {
            $borrow->checkOverdue();
        });

        static::saving(function ($borrow) {
            $borrow->checkOverdue();
        });
    }

    

    public function checkOverdue()
    {
        if ($this->status !== 'returned' && Carbon::now()->greaterThan($this->due_date)) {
            $this->status = 'overdue';
            $this->saveQuietly(); // Menghindari infinite loop
        }
    }
}