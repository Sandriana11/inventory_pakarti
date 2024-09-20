<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    
    protected $table = 'barangs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama', 'tahun', 'nomor', 'kategori_id', 'lokasi_id', 'deskripsi', 'user_id'
    ];

    
    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    
    public function lokasi(){
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
