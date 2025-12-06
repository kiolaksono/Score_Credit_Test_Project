<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Tentukan nama tabel yang benar
    protected $table = 'users';

    // 2. Tentukan Primary Key yang benar
    protected $primaryKey = 'user_id';

    // 3. Kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'username',
        'user_email',
        'user_password',
    ];

    // 4. Sembunyikan password saat data dikonversi ke Array/JSON
    protected $hidden = [
        'user_password',
    ];

    // 5. PENTING: Override method untuk memberitahu Laravel nama kolom password
    public function getAuthPassword()
    {
        return $this->user_password;
    }
}