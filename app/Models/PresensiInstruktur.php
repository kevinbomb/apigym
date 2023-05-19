<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiInstruktur extends Model
{
    use HasFactory;

    protected $table = 'presensi_instruktur';
    protected $primaryKey = 'ID_PRESENSII';
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'ID_PRESENSII',
        'ID_INSTRUKTUR',
        'WAKTU_MULAI',
        'WAKTU_SELESAI',
        'TERLAMBAT',
        'DURASI',
    ]; 

    public function instruktur(){
        return $this->belongsTo(Instruktur::class, 'ID_INSTRUKTUR', 'ID_INSTRUKTUR');
    }
}
