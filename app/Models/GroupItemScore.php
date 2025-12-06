<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupItemScore extends Model
{
    use HasFactory;

    protected $table = 'group_item_scores';
    protected $primaryKey = 'group_item_score_id';

    protected $fillable = [
        'application_id', // ID Aplikasi
        'group_item_id',  // ID Pertanyaan (Soal)
        'item_id',        // ID Jawaban yang dipilih (Opsional, tapi bagus untuk history)
        'group_item_score',          // Nilai hasil hitung (Item Score * Group Item Weight)
    ];

    // --- RELASI ---

    // Kembali ke Application
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // Terhubung ke Master Data Pertanyaan (Soal)
    public function groupItem()
    {
        return $this->belongsTo(GroupItem::class);
    }

    // Terhubung ke Master Data Jawaban (Pilihan Ganda)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}