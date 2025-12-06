<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupScore extends Model
{
    use HasFactory;

    protected $table = 'group_scores';
    protected $primaryKey = 'group_score_id';

    protected $fillable = [
        'application_id', // ID Aplikasi (Foreign Key)
        'group_id',       // ID Group/Kategori (Foreign Key)
        'group_score',          // Nilai hasil hitung (Sum Item Scores * Group Weight)
    ];

    // --- RELASI ---

    // Kembali ke Application
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // Terhubung ke Master Data Group (untuk tahu ini skor group apa)
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}