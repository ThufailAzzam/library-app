<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Group;
use App\Filament\Resources\BookRequestResource\Pages;
use App\Models\BookRequest;
use Filament\Forms\Components\Section;
use App\Models\BookModel;
use Filament\Forms\Components\Textarea;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\Action;

class BookRequestResource extends Resource
{
    protected static ?string $model = BookRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    
    protected static ?string $navigationLabel = 'Permintaan Buku';
    
    protected static ?string $pluralModelLabel = 'Permintaan Buku';
    
    protected static ?string $navigationGroup = 'Perpustakaan Keliling';

    protected static ?string $slug = 'request';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Hidden::make('id_user')
                    ->default(fn () => Auth::id()),
                Section::make('Info Buku yang Diinginkan')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('judul')->label('Judul')->required(),
                                TextInput::make('penulis')->label('Penulis')->nullable(),
                                TextInput::make('kode_buku')->label('Kode ISBN')->nullable(),
                                TextInput::make('penerbit')->label('Penerbit')->nullable(),
                                TextInput::make('tahun_terbit')->label('Tahun Terbit')->nullable(),
                            ]),
                        
                        ]),
                
                //Select::make('id_user')
                    //->label('Pengguna')
                    //->relationship('user', 'name')
                   // ->searchable()
                   // ->preload()
                   // ->required(),
                
                Textarea::make('alasan_permintaan')
                    ->label('Alasan Permintaan')
                    ->required()
                    ->placeholder('Jelaskan mengapa buku ini perlu ditambahkan ke perpustakaan...')
                    ->helperText('Sertakan detail tambahan seperti penulis, penerbit, dan tahun terbit jika buku belum terdaftar')
                    ->maxLength(255),
                        
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('penulis')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kode_buku')
                    ->label('ISBN')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('penerbit')
                    ->label('penerbit')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('alasan_permintaan')
                    ->label('Alasan')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->alasan_permintaan;
                    }),
                
                TextColumn::make('created_at')
                    ->label('Tanggal Permintaan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tgl_permintaan', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tgl_permintaan', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('approveRequest')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (BookRequest $record) {
                        // Check if this is a request for an existing book
                        if ($record->book) {
                            // The book exists, you could update stock or mark as approved
                            Notification::make()
                                ->title('Permintaan disetujui')
                                ->success()
                                ->send();
                        } else {
                            // This is a request for a new book, redirect to create book form
                            // In a real implementation, you might want to redirect or create a new book
                            Notification::make()
                                ->title('Permintaan untuk buku baru disetujui')
                                ->success()
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('create')
                                        ->label('Tambah Buku')
                                        ->url(BookResource::getUrl('create'))
                                        ->button(),
                                ])
                                ->send();
                        }
                        
                        // Delete the request after approval (optional)
                        // $record->delete();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Approve Book Request')
                    ->modalDescription('Are you sure you want to approve this request?')
                    ->modalSubmitActionLabel('Yes, approve request'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookRequests::route('/'),
            'create' => Pages\CreateBookRequest::route('/create'),
            'edit' => Pages\EditBookRequest::route('/{record}/edit'),
        ];
    }


}