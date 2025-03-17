<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianPegawaiResource\Pages;
use App\Models\PenilaianPegawai;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class PenilaianPegawaiResource extends Resource
{
    protected static ?string $model = PenilaianPegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    
    protected static ?string $navigationLabel = 'Penilaian Pegawai';
    
    protected static ?string $modelLabel = 'Penilaian Pegawai';
    
    protected static ?string $pluralModelLabel = 'Penilaian Pegawai';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_pegawai')
                    ->label('Pegawai')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Select::make('jenis_insiden')
                    ->label('Jenis Insiden')
                    ->options([
                        'Perilaku Tidak Tertib' => 'Perilaku Tidak Tertib',
                        'Perilaku Tidak Sopan' => 'Perilaku Tidak Sopan',
                        'Layanan Tidak Profesional' => 'Layanan Tidak Profesional',
                        'Keterlambatan Bantuan' => 'Keterlambatan Bantuan',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                    
                Radio::make('penilaian')
                    ->label('Penilaian')
                    ->options([
                        'Sangat Baik' => 'Sangat Baik',
                        'Baik' => 'Baik',
                        'Cukup' => 'Cukup',
                        'Buruk' => 'Buruk',
                    ])
                    ->inline()
                    ->required(),
                    
                TextInput::make('skor_penilaian')
                    ->label('Skor Penilaian (Opsional)')
                    ->minValue(1)
                    ->maxValue(10)
                    ->helperText('Nilai dari angka 1-10')
                    ->step(1)
                    ->default(5),
                    
                TextInput::make('lokasi')
                    ->label('Lokasi di Perpustakaan')
                    ->maxLength(100),
                
                Richeditor::make('deskripsi')
                    ->label('Deskripsi Kejadian')
                    ->toolbarButtons([
                        
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->required(),
                    
                FileUpload::make('foto_kejadian')
                    ->label('Foto Bukti (Bila perlu)')
                    ->helperText('Unggah foto sebagai bukti kejadian (opsional)')
                    ->image()
                    ->imageResizeMode('cover')
                    
                    ->directory('fotobukti')
                    ->visibility('public')
                    ->maxSize(5120) // 5MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']),
                    
                Toggle::make('anonim')
                    ->label('Laporkan Secara Anonim')
                    ->default(false)
                    ->helperText('Jika diaktifkan, identitas Anda tidak akan ditampilkan pada laporan ini'),
            ]);
    }

    public static function table(Table $table): Table
    {
       // $user = Auth::user();
    //    $isAdmin = $user->role === 'admin';
        
        return $table
            ->columns([
                TextColumn::make('pegawai.name')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('jenis_insiden')
                    ->label('Jenis Insiden')
                    ->sortable(),
                    
                BadgeColumn::make('penilaian')
                    ->label('Penilaian')
                    ->colors([
                        'success' => 'Sangat Baik',
                        'primary' => 'Baik',
                        'warning' => 'Cukup',
                        'danger' => 'Buruk',
                    ]),
                    
                TextColumn::make('skor_penilaian')
                    ->label('Skor')
                    ->sortable(),
                
                ImageColumn::make('foto_kejadian')
                    ->disk('public')
                    // ->label('Foto')
                    // ->circular(false)
                    // ->square()
                    ->defaultImageUrl(url('/storage/copanga.webp')) 
                    // ->extraImgAttributes(['loading' => 'lazy'])
                    ->visibility('public'),
                
                
                TextColumn::make('pelapor.name')
                    ->label('Pelapor')
                   // ->visible($isAdmin)
                    ->placeholder('Anonim')
                    ->sortable(),
                    
                IconColumn::make('anonim')
                    ->label('Anonim')
                    ->boolean()
                    //->visible($isAdmin)
                    ,
                    
                TextColumn::make('created_at')
                    ->label('Tanggal Laporan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('penilaian')
                    ->label('Filter Penilaian')
                    ->options([
                        'Sangat Baik' => 'Sangat Baik',
                        'Baik' => 'Baik',
                        'Cukup' => 'Cukup',
                        'Buruk' => 'Buruk',
                    ]),
                    
                SelectFilter::make('jenis_insiden')
                    ->label('Filter Jenis Insiden')
                    ->options([
                        'Perilaku Tidak Tertib' => 'Perilaku Tidak Tertib',
                        'Perilaku Tidak Sopan' => 'Perilaku Tidak Sopan',
                        'Layanan Tidak Profesional' => 'Layanan Tidak Profesional',
                        'Keterlambatan Bantuan' => 'Keterlambatan Bantuan',
                        'Lainnya' => 'Lainnya',
                    ]),
                
                TernaryFilter::make('foto_kejadian')
                    ->label('Ada Foto')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('foto_kejadian'),
                        false: fn (Builder $query) => $query->whereNull('foto_kejadian'),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Penilaian'),
                
                // Only admins can edit/delete records
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                 //   ->visible($isAdmin)
                 ,
                    
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                   // ->visible($isAdmin)
                    ->modalHeading('Hapus Penilaian')
                    ->modalDescription('Apakah Anda yakin ingin menghapus penilaian ini? Tindakan ini tidak dapat dibatalkan.'),
            ])
            ->bulkActions([
                // Only show bulk actions for admin
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang dipilih')
                        ->modalHeading('Hapus Penilaian Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus penilaian yang dipilih? Tindakan ini tidak dapat dibatalkan.'),
                ])
                //->visible($isAdmin),
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
            'index' => Pages\ListPenilaianPegawais::route('/'),
            'create' => Pages\CreatePenilaianPegawai::route('/create'),
            'edit' => Pages\EditPenilaianPegawai::route('/{record}/edit'),
        ];
    }
    
    // Modify permissions based on user role
  //  public static function canCreate(): bool 
  //  {
 //       return auth()->check() && in_array(auth()->user()->role, ['admin', 'Peserta Kegiatan']);
  //  }
    
//    public static function canEdit(Model $record): bool 
  //  {
 //       return auth()->check() && auth()->user()->role === 'admin';
   // }
    
  //  public static function canDelete(Model $record): bool 
   // {
 //       return auth()->check() && auth()->user()->role === 'admin';
   // }
    
  //  public static function canDeleteAny(): bool 
   // {
  //      return auth()->check() && auth()->user()->role === 'admin';
 //   }
}