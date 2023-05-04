<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiAktivasi extends Model
{
    use HasFactory;

    protected $table = 'transaksi_aktivasi';
    protected $primaryKey = 'ID_TRANSAKSIA';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'ID_TRANSAKSIA',
        'NO_MEMBER',
        'ID_PEGAWAI',
        'TANGGAL_TRANSAKSIA',
        'BIAYA_TRANSAKSIA',
        'TANGGAL_EXP_TRANSAKSIA',
    ]; 

    public function member(){
        return $this->belongsTo(Member::class, 'NO_MEMBER', 'NO_MEMBER');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI', 'ID_PEGAWAI');
    }
}
