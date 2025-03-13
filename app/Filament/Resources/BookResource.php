<?php

namespace App\Filament\Resources;

use App\Models\LogBuku;
use App\Filament\Resources\BookResource\Pages\PopularBooks;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\BookResource\RelationManagers\LogBukuRelationManager;
use App\Filament\Resources\BookResource\RelationManagers\BookRequestsRelationManager;
use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use App\Models\BookModel;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\CheckboxList;
use DesignTheBox\BarcodeField\Forms\Components\BarcodeInput;
use App\View\Components\ISBNScanner;

class BookResource extends Resource
{
    protected static ?string $model = BookModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Buku yang Ada';
    protected static ?string $navigationGroup = 'Perpustakaan Keliling';
    protected static ?string $slug = 'perpustakaan';

    public static ?string $label = 'Buku Yang Ada';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('request_id'),

                TextInput::make('judul')
                ->required()
                ->label('Judul Buku')
                ->placeholder('Masukkan Judul Buku....'),
                TextInput::make('penulis')
                ->required()
                ->label('Penulis/Author Buku')
                ->placeholder('Masukkan Nama Penulis....'),
                TextInput::make('kode_buku')
                ->required()
                ->numeric()
                ->label('Kode ISBN')
                ->placeholder('Masukkan Kode Buku....'),
                Select::make('categories') // Multi-select for categories
                ->label('Kategori Buku')
                ->multiple()
                ->relationship('categories', 'nama_kategori')
                ->preload(),
                TextInput::make('penerbit')
                ->required()
                ->label('Nama Penerbit')
                ->placeholder('Masukkan Nama Penerbit...'),
                TextInput::make('tahun_terbit')
                ->required()
                ->numeric()
                ->minValue(1850)
                ->maxValue(2100)
                ->label('Tahun Terbit')
                ->placeholder('Masukkan Tahun Terbit Buku...'),
                TextInput::make('stok')
                ->required()
                ->numeric()
                ->label('Stok Buku')
                ->placeholder('Masukkan Jumlah Stok....'),
                TextInput::make('harga_buku') 
                ->required()
                ->numeric()
                ->label('Harga Buku')
                ->placeholder('Masukkan Harga Buku....')
                ->prefix('IDR'),
                CheckboxList::make('mobil') // Add this line
                ->relationship('mobil', 'nopol') // Assuming 'nopol' is the car's license plate
                ->label('Cars'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                ->label('Judul Buku')
                ->searchable()
                ->copyable()
                ->sortable(),
                TextColumn::make('penulis')
                ->searchable()
                ->copyable()
                ->sortable(),
                TextColumn::make('kode_buku')
                ->label('ISBN')
                ->searchable()
                ->copyable()
                ->sortable(),
                TextColumn::make('categories.nama_kategori') // Display the category names
                ->label('Kategori')
                ->searchable()
                ->sortable(),
                TextColumn::make('penerbit')
                ->searchable()
                ->copyable()
                ->sortable(),
                TextColumn::make('tahun_terbit')
                ->searchable()
                ->copyable()
                ->sortable(),
                TextColumn::make('stok')
                ->searchable()
                ->sortable(),

                TextColumn::make('harga_buku') 
                ->label('Harga Buku')
                ->money('IDR') // Format as currency
                ->sortable(),
                
                TextColumn::make('logBukuCount') // New column for log count
                ->label('Log Count')
                ->sortable()
                ->formatStateUsing(fn ($state) => $state ?? 0) // Format to show 0 if null
                ->getStateUsing(function (BookModel $record) {
                    return $record->logBuku()->count();
                }),

                TextColumn::make('from_request')
                    ->label('Dari Request')
                    ->getStateUsing(fn (BookModel $record) => $record->request_id ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Yes' ? 'success' : 'gray'),
                
            ])
            ->filters([
                SelectFilter::make('kategori')
                ->label('Kategori Buku')
                ->relationship('categories', 'nama_kategori'),
                TernaryFilter::make('from_request')
                    ->label('From Request')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('request_id'),
                        false: fn (Builder $query) => $query->whereNull('request_id'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('decreaseStock')
                ->label('Decrease Stock')
                ->color('danger')
                ->icon('heroicon-o-minus')
                ->form([
                    Forms\Components\TextInput::make('reason')
                        ->label('Reason for Decreasing Stock')
                        ->required(),
                ])
                ->action(function (BookModel $record, array $data) {
                    if ($record->stok <= 0) {
                        Notification::make()
                            ->title('Stock is already zero')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Decrease the stock
                    $record->stok -= 1;
                    $record->save();

                    // Log the reason using the LogBuku model
                    LogBuku::create([
                        'book_id' => $record->id,
                        'reason' => $data['reason'],
                    ]);

                    Notification::make()
                        ->title('Stock decreased successfully')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Decrease Stock')
                ->modalDescription('Are you sure you want to decrease the stock of this book?')
                ->modalSubmitActionLabel('Yes, decrease stock'),

                Action::make('viewRequest')
                    ->label('View Request')
                    ->icon('heroicon-o-eye')
                    ->url(fn (BookModel $record) => $record->request_id 
                        ? BookRequestResource::getUrl('edit', ['record' => $record->request_id]) 
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn (BookModel $record) => !empty($record->request_id)),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
{
    return [
        LogBukuRelationManager::class,
        
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route(path: '/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
            
        ];
    }
}
