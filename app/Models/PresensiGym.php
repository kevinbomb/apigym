<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiGym extends Model
{
    use HasFactory;

    
    protected $table = 'presensi_gym';
    protected $primaryKey = 'NO_PRESENSIG';

    public $incrementing = false;
    public $timestamps = false;
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'NO_PRESENSIG',
        'NO_MEMBER',
        'SLOT_WAKTU_PRESENSIG',
        'TANGGAL_PRESENSIG_DIBUAT',
        'TANGGAL_PRESENSIG',
        'WAKTU_PRESENSIG',
    ]; 

    public function member(){
        return $this->belongsTo(Member::class, 'NO_MEMBER', 'NO_MEMBER');
    }
}
