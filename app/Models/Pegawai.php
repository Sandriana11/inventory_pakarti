<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    
    protected $table = 'pegawai';
    protected $primaryKey = 'nip';

    
    protected $fillable = [
        'nip', 'nama', 'bidang', 'hp'
    ];


    public function bidang(){
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}
