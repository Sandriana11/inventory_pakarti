<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintener extends Model
{
    use HasFactory;
    
    protected $table = 'mainteners';
    protected $primaryKey = 'id';

    
    protected $fillable = [
        'id', 'nama', 'tipe', 'hp', 'alamat', 'nip'
    ];
}
