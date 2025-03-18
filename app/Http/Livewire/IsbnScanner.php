<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class IsbnScanner extends Component
{
    public $isbn = '';
    public $bookData = null;
    public $loading = false;
    public $error = null;

    protected $listeners = ['useBookData' => 'fillBookForm'];

    public function render()
    {
        return view('livewire.isbn-scanner');
    }

    public function fetchBookData()
    {
        $this->loading = true;
        $this->error = null;
        $this->bookData = null;

        try {
            // Clean up the ISBN (remove dashes, spaces, etc.)
            $cleanIsbn = preg_replace('/[^0-9X]/', '', $this->isbn);
            
            if (empty($cleanIsbn)) {
                $this->error = 'Please enter an ISBN';
                $this->loading = false;
                return;
            }
            
            // Fetch book data from Open Library API
            $response = Http::get("https://openlibrary.org/api/books?bibkeys=ISBN:{$cleanIsbn}&format=json&jscmd=data");
            
            if ($response->successful()) {
                $data = $response->json();
                $key = "ISBN:{$cleanIsbn}";
                
                if (isset($data[$key])) {
                    $this->bookData = $data[$key];
                } else {
                    
                    $googleResponse = Http::get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$cleanIsbn}");
                    
                    if ($googleResponse->successful() && isset($googleResponse->json()['items'][0])) {
                        $googleData = $googleResponse->json()['items'][0]['volumeInfo'];
                        
                        $this->bookData = [
                            'title' => $googleData['title'] ?? 'Unknown Title',
                            'authors' => array_map(function($author) {
                                return ['name' => $author];
                            }, $googleData['authors'] ?? []),
                            'publishers' => isset($googleData['publisher']) ? [['name' => $googleData['publisher']]] : [],
                            'publish_date' => $googleData['publishedDate'] ?? '',
                        ];
                    } else {
                        $this->error = 'Book not found for this ISBN';
                    }
                }
            } else {
                $this->error = 'Failed to fetch book data';
            }
        } catch (\Exception $e) {
            $this->error = 'An error occurred: ' . $e->getMessage();
        }

        $this->loading = false;
    }

    public function fillBookForm()
    {
        if (!$this->bookData) {
            return;
        }

        // Clean up the ISBN
        $cleanIsbn = preg_replace('/[^0-9X]/', '', $this->isbn);
        
        // Extract year from publish date
        $publishYear = null;
        if (isset($this->bookData['publish_date'])) {
            preg_match('/\d{4}/', $this->bookData['publish_date'], $matches);
            $publishYear = !empty($matches) ? (int) $matches[0] : null;
        }
        
        // Emit event with book data to be captured by the form
        $this->emit('bookDataFetched', [
            'isbn' => $cleanIsbn,
            'title' => $this->bookData['title'] ?? '',
            'authors' => isset($this->bookData['authors']) ? collect($this->bookData['authors'])->pluck('name')->join(', ') : '',
            'publisher' => isset($this->bookData['publishers']) ? collect($this->bookData['publishers'])->pluck('name')->first() : '',
            'publish_year' => $publishYear,
        ]);
    }
}