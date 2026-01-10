<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'idx';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['idx'];
    // protected $guarded = ['id'];

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

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        // Contoh: hanya user dengan role tertentu bisa masuk dashboard
        return $this->hasAnyRole(['admin', 'operator', 'guru', 'siswa', 'wali kelas', 'wali murid']);
    }

    // public function hasAnyRole($roles): bool
    // {
    //     return null !== $this->roles()->whereIn('name', $roles)->first();
    // }
}
