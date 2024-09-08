<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Bidang extends Model

{

    use HasFactory;

    

    protected $table = 'bidang';

    protected $primaryKey = 'id';



    

    protected $fillable = [

        'id', 'nama',

    ];



    

    public function pegawai(){

        return $this->hasOne(User::class, 'bidang_id');

    }

}

