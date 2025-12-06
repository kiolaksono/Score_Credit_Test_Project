<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupItem extends Model
{
    protected $table = 'group_items';
    protected $primaryKey = 'group_item_id';
    protected $guarded = [];

    // Relasi: 1 GroupItem punya banyak Item (Pilihan Jawaban)
    public function items()
    {
        return $this->hasMany(Item::class, 'group_item_id');
    }
    
    // Relasi kebalikannya (opsional, tapi bagus ada)
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}