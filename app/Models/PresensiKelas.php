<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiKelas extends Model
{
    use HasFactory;

    protected $table = 'presensikelas';
    protected $primaryKey = 'NO_PRESENSIK';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'NO_PRESENSIK',
        'NO_MEMBER',
        'ID_JADWAL',
        'ID_JADWALH',
        'NAMA_KELAS',
        'TANGGAL_PRESENSIK_DIBUAT',
        'TANGGAL_PRESENSIK',
        'SESI',
        'WAKTU_PRESENSIK',
        'TARIF_PRESENSIK',
    ]; 

    public function jadwal(){
        return $this->belongsTo(Jadwal::class, 'ID_JADWAL', 'ID_JADWAL');
    }

    public function jadwalh(){
        return $this->belongsTo(Jadwalh::class, 'ID_JADWALH', 'ID_JADWALH');
    }

    public function member(){
        return $this->belongsTo(Member::class, 'NO_MEMBER', 'NO_MEMBER');
    }
}
