<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }


    public function canAccessPanel(Panel $panel): bool
    {
        $roles = $this->getRoleNames();

        // Jika user tidak punya role, langsung tolak akses
        if ($roles->isEmpty()) {
            return false;
        }

        // Daftar panel & role yang diizinkan
        $roleAccess = [
            'admin'    => 'Admin',
            'kadin'    => 'Kepala Dinas',
            'sekdin'   => 'Sekretaris Dinas',
            'keuangan' => 'Bagian Keuangan',
            'petugas'  => 'Petugas',
            'pemohon'  => 'Pemohon Kegiatan',
            'peserta'  => 'Peserta Kegiatan',
        ];

        // Cek apakah role user cocok dengan panel
        return isset($roleAccess[$panel->getId()]) &&
            $roles->contains($roleAccess[$panel->getId()]);
    }
}
