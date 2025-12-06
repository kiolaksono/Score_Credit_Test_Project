<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'application_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Lebih eksplisit: daftar kolom yang boleh di-mass assign
    protected $fillable = [
        'application_number',
        'application_name',
        'application_gender',
        'application_birth_place',
        'application_birth_date',
        'application_address',
        'application_postal_code',
        'application_summary_score',
    ];

    protected $casts = [
        'application_birth_date' => 'date',
        'application_summary_score' => 'float',
    ];

    // Relasi: 1 Application punya banyak GroupScore
    public function groupScores()
    {
        return $this->hasMany(GroupScore::class, 'application_id');
    }

    // Relasi ke jawaban per pertanyaan yang disimpan
    public function groupItemScores()
    {
        return $this->hasMany(GroupItemScore::class, 'application_id');
    }
}