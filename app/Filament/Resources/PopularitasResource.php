<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PopularitasResource\Pages;
use App\Filament\Resources\PopularitasResource\RelationManagers;
use App\Models\Popularitas;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PopularitasResource extends Resource
{
    protected static ?string $model = Popularitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Popularitas Buku';
    protected static ?string $navigationGroup = 'Perpustakaan Keliling';
    protected static ?string $slug = 'popularitas';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('penulis')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_pinjam') // Display the jumlah_pinjam
                    ->label('Jumlah Peminjaman')
                    ->sortable(),
            ])
            ->filters([
                
            ])
            ->actions([
            
            ])
            ->bulkActions([
                
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPopularitas::route('/'),
        ];
    }
}