<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo';
    protected $primaryKey = 'id_promo';
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'nama_promo',
        'keterangan_promo',
        'jenis_promo',
        'syarat_promo',
        'bonus_promo',
    ]; 
}
