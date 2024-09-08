<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pindah extends Model
{
    use HasFactory;
    
    protected $table = 'pindah';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama',
    ];

    
    public function lines(){
        return $this->hasMany(PindahDetail::class, 'pindah_id');
    }
    
    public function lokasi(){
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
    
}
