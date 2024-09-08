<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;
    
    protected $table = 'maintenance';
    protected $primaryKey = 'id';

    
    protected $fillable = [
        'id', 'crash_id', 'status'
    ];

    
    public function kerusakan(){
        return $this->belongsTo(Kerusakan::class, 'crash_id');
    }
    
    public function eksekutor(){
        return $this->belongsTo(User::class, 'eksekutor_id');
    }
}
