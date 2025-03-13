<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianPegawai extends Model
{
    use HasFactory;

    protected $table = 'penilaian_pegawais';

    
    protected $fillable = [
        'id_pelapor',
        'id_pegawai',
        'penilaian',
        'skor_penilaian',
        'jenis_insiden',
        'deskripsi',
        'lokasi',
        'anonim',
        'foto_kejadian',
    ];

    /**
     * Get the pelapor that submitted the assessment.
     */
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_pelapor');
    }

    /**
     * Get the pegawai being assessed.
     */
    public function user()
{
    return $this->belongsTo(User::class, 'id_pegawai'); // Adjust the foreign key if necessary
}
}