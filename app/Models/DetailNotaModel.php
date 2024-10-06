<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailNotaModel extends Model
{
    use HasFactory;
    protected $table = 'detail_nota';
    protected $fillable = [
        'nota_id',
        'menu_id',
        'harga_tertera',
    ];

    public function menu()
    {
        return $this->belongsTo(MenuModel::class, 'menu_id');
    }
    public function nota()
    {
        return $this->belongsTo(NotaModel::class, 'nota_id');
    }
}
