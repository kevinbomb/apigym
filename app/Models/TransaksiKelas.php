<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKelas extends Model
{
    use HasFactory;

    protected $table = 'transaksi_depositk';
    protected $primaryKey = 'NO_TRANSAKSIK';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'NO_TRANSAKSIK',
        'NO_MEMBER',
        'ID_PEGAWAI',
        'ID_PROMO',
        'ID_KELAS',
        'TANGGAL_TRANSAKSIK',
        'JUMLAH_TRANSAKSIK',
        'BIAYA_TRANSAKSIK',
        'BONUS_TRANSAKSIK',
        'TANGGAL_EXP_TRANSAKSIK',
        'TOTAL_TRANSAKSIK',
    ]; 

    public function member(){
        return $this->belongsTo(Member::class, 'NO_MEMBER', 'NO_MEMBER');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI', 'ID_PEGAWAI');
    }

    public function promo(){
        return $this->belongsTo(Promo::class, 'ID_PROMO', 'ID_PROMO');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'ID_KELAS', 'ID_KELAS');
    }
}
