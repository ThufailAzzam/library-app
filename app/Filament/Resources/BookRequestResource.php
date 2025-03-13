<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Group;
use App\Filament\Resources\BookRequestResource\Pages;
use App\Models\BookRequest;
use Filament\Forms\Components\Section;
use App\Models\BookModel;
use Illuminate\Support\Collection;
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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

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
                                TextInput::make('judul')
                                    ->label('Judul')
                                    ->required()
                                    ,
                                TextInput::make('penulis')
                                    ->label('Penulis')
                                    ->nullable()
                                    ,
                                TextInput::make('kode_buku')
                                    ->label('Kode ISBN')
                                    ->nullable(),
                                TextInput::make('penerbit')
                                    ->label('Penerbit')
                                    ->nullable(),
                                TextInput::make('tahun_terbit')
                                    ->label('Tahun Terbit')
                                    ->nullable(),
                            ]),
                        
                        ]),
                
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
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
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
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Status')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (BookRequest $record) => $record->status === 'pending'),
                
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (BookRequest $record) => $record->status === 'pending'),
                
                Action::make('approveRequest')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (BookRequest $record) {
                        // Update the status to approved
                        $record->status = 'approved';
                        $record->save();
                        
                        Notification::make()
                            ->title('Permintaan disetujui')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Approve Book Request')
                    ->modalDescription('Are you sure you want to approve this request?')
                    ->modalSubmitActionLabel('Yes, approve request')
                    ->visible(fn (BookRequest $record) => $record->status === 'pending'),
                
                Action::make('rejectRequest')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (BookRequest $record) {
                        $record->status = 'rejected';
                        $record->save();
                        
                        Notification::make()
                            ->title('Permintaan ditolak')
                            ->danger()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reject Book Request')
                    ->modalDescription('Are you sure you want to reject this request?')
                    ->modalSubmitActionLabel('Yes, reject request')
                    ->visible(fn (BookRequest $record) => $record->status === 'pending'),
                
                Action::make('createBook')
                    ->label('Add to Library')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(function (BookRequest $record) {
                        // Pass the request ID as a parameter to the book creation page
                        return BookResource::getUrl('create', ['request_id' => $record->id]);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn (BookRequest $record) => $record->isApproved()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->hidden(fn (BookRequest $record) => $record->isApproved()),
                
                Tables\Actions\BulkAction::make('approveBulk')
                    ->label('Approve Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (!$record->isApproved()) {
                                $record->status = 'approved';
                                $record->save();
                            }
                        });
                        
                        Notification::make()
                            ->title('Selected requests approved')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Approve Selected Requests')
                    ->modalDescription('Are you sure you want to approve all selected requests?')
                    ->modalSubmitActionLabel('Yes, approve requests'),
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