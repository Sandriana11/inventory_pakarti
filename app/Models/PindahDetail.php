<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PindahDetail extends Model
{
    use HasFactory;
    
    protected $table = 'pindah_lines';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama',
    ];

    
    public function barang(){
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function lokasi(){
        return $this->belongsTo(lokasi::class, 'lokasi_id');
    }
}
