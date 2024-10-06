<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaModel extends Model
{
    use HasFactory;
    protected $table = 'nota';
    protected $fillable = [
        'total_harga',
    ];

    public function detail()
    {
        return $this->hasMany(DetailNotaModel::class, 'nota_id');
    }
}
