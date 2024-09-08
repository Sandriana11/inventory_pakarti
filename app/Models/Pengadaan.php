<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;
    
    protected $table = 'pengadaan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama',
    ];

    
    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    
    public function lokasi(){
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
    
    public function bidang(){
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}
