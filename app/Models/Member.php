<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Member extends Authenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'member';
    protected $primaryKey = 'NO_MEMBER';
    protected $keyType = 'String'; 

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'NO_MEMBER',
        'NAMA_MEMBER',
        'ALAMAT_MEMBER',
        'TANGGAL_LAHIR_MEMBER',
        'NO_TELP_MEMBER',
        'PASSWORD_MEMBER',
        'STATUS_MEMBER',
        'TANGGAL_EXP_MEMBER',
        'SALDO_MEMBER',
    ]; 

    
}
