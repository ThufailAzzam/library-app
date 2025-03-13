<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BookRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookRequests';

    protected static ?string $recordTitleAttribute = 'alasan_permintaan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_user')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Forms\Components\DateTimePicker::make('tgl_permintaan')
                    ->label('Tanggal Permintaan')
                    ->default(now())
                    ->required(),
                
                Forms\Components\Textarea::make('alasan_permintaan')
                    ->label('Alasan Permintaan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna'),
                Tables\Columns\TextColumn::make('tgl_permintaan')
                    ->label('Tanggal Permintaan')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('alasan_permintaan')
                    ->label('Alasan')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}