<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'ID_JADWAL';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'ID_JADWAL',
        'ID_KELAS',
        'SESI',
        'HARI_JADWAL',
        'ID_INSTRUKTUR',
    ]; 

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'ID_KELAS', 'ID_KELAS');
    }

    public function instruktur(){
        return $this->belongsTo(Instruktur::class, 'ID_INSTRUKTUR', 'ID_INSTRUKTUR');
    }
}
