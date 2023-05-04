<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Pegawai extends Authenticable
{
    use HasApiTokens, HasFactory, Notifiable;
    

    protected $table = 'pegawai';
    protected $primaryKey = 'ID_PEGAWAI';
    protected $keyType = 'string';
    /**
    * fillable
    *
    * @var array
    */

    protected $fillable = [
        'ID_PEGAWAI',
        'NAMA_PEGAWAI',
        'ROLE_PEGAWAI',
        'TANGGAL_LAHIR_PEGAWAI',
        'password',
        'ALAMAT_PEGAWAI',
        'NO_TELP_PEGAWAI',
    ]; 
}
