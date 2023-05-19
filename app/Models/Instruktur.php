<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Instruktur extends Authenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'instruktur';
    protected $primaryKey = 'ID_INSTRUKTUR';

    public $incrementing = false;
    public $timestamps = false;


    protected $fillable = [
        'ID_INSTRUKTUR',
        'NAMA_INSTRUKTUR',
        'TANGGAL_LAHIR_INSTRUKTUR',
        'ALAMAT_INSTRUKTUR',
        'NO_TELP_INSTRUKTUR',
        'PASSWORD_INSTRUKTUR',
        'GAJI_INSTRUKTUR',
    ]; 
}
