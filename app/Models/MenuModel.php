<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    use HasFactory;
    protected $table = 'menu';
    protected $fillable = [
        'nama',
        'gambar',
        'deskripsi',
        'harga_pokok',
    ];
    public function detail()
    {
        return $this->hasMany(DetailNotaModel::class, 'menu_id');
    }
}
