<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiUang extends Model
{
    use HasFactory;

    protected $table = 'transaksi_depositu';
    protected $primaryKey = 'NO_TRANSAKSIU';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'NO_TRANSAKSIU',
        'NO_MEMBER',
        'ID_PEGAWAI',
        'ID_PROMO',
        'TANGGAL_TRANSAKSIU',
        'JUMLAH_TRANSAKSIU',
        'BONUS_TRANSAKSIU',
        'SISA_SALDO_TRANSAKSIU',
        'TOTAL_TRANSAKSIU',
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
}
