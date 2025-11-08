<?php

namespace App\Models;

// Impor trait dan kelas bawaan Laravel
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Tambahkan 'role_id' di sini.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Kita tambahkan role_id
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast ke tipe bawaan.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI DAN FUNGSI HELPER KUSTOM ---

    /**
     * Dapatkan peran (Role) yang dimiliki pengguna.
     * * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        // Berelasi dengan Model Role
        return $this->belongsTo(Role::class);
    }

    /**
     * Helper untuk memeriksa apakah pengguna memiliki peran tertentu.
     *
     * @param string $roleName Nama peran yang akan diperiksa (e.g., 'Admin', 'Guru', 'Siswa')
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        // Cek apakah relasi role ada dan nama perannya sesuai
        return $this->role && $this->role->name === $roleName;
    }
}
