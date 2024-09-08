<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    
    protected $table = 'kategori';
    protected $primaryKey = 'id';

    
    protected $fillable = [
        'id', 'nama',
    ];
    
    public function barang(){
        return $this->hasOne(Barang::class, 'kategori_id');
    }
}
