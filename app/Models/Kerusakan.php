<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Kerusakan extends Model

{

    use HasFactory;

    

    protected $table = 'crash';

    protected $primaryKey = 'id';



    

    protected $fillable = [

        'id', 'nomor', 'pegawai_id', 'tgl', 'kategori', 'status', 'user_id', 'barang', 'no_inventaris', 'lokasi'

    ];

    protected $appends = [
        'date_human'
    ];



    public function pelapor(){

        return $this->belongsTo(User::class, 'user_id');

    }

    

    public function barang(){

        return $this->belongsTo(Barang::class, 'barang_id');

    }



    public function kategori(){

        return $this->belongsTo(Kategori::class, 'kategori_id');

    }

    

    public function lokasi(){

        return $this->belongsTo(Lokasi::class, 'lokasi_id');

    }



    public function perbaikan(){

        return $this->hasOne(Perbaikan::class, 'crash_id');

    }

    public function getDateHumanAttribute($value)
    {
        return Carbon::parse($this->attributes['tgl'])->diffForHumans();
    }

}

