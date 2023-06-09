<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinan';
    protected $primaryKey = 'ID_PERIZINAN';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'ID_INSTRUKTUR',
        'KETERANGAN_PERIZINAN',
        'STATUS_PERIZINAN',
        'TANGGAL_PERIZINAN',
        'TANGGAL_PERIZINAN_DIAJUKAN',
    ]; 

    public function instruktur(){
        return $this->belongsTo(Instruktur::class, 'ID_INSTRUKTUR', 'ID_INSTRUKTUR');
    }
}
