<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Jabatan extends Model

{

    use HasFactory;

    

    protected $table = 'jabatans';

    protected $primaryKey = 'id';



    

    protected $fillable = [

        'id', 'nama',

    ];



    

    public function pegawai(){

        return $this->hasOne(User::class, 'jabatan_id');

    }

}

