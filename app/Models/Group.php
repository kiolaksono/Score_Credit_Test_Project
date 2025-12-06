<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups'; // Sesuaikan nama tabel Anda
    protected $primaryKey = 'group_id';
    protected $guarded = [];

    // Relasi: 1 Group punya banyak GroupItem
    public function groupItems()
    {
        return $this->hasMany(GroupItem::class, 'group_id');
    }
}