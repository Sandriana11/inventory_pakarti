<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;
    
    protected $table = 'lokasis';
    protected $primaryKey = 'id';

    
    protected $fillable = [
        'id', 'nama',
    ];

    
    public function barang(){
        return $this->hasOne(Barang::class, 'lokasi_id');
    }
}
