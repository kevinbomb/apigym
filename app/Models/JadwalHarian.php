<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalHarian extends Model
{
    use HasFactory;

    protected $table = 'jadwalharian';
    // protected $primaryKey = ['ID_JADWAL', 'TANGGAL_JADWAL_H'];
    protected $primaryKey = 'ID_JADWALH';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'ID_JADWALH',
        'TANGGAL_JADWALH',
        'ID_JADWAL',
        'STATUS_JADWALH',
        'ID_INSTRUKTUR',
    ]; 
}
