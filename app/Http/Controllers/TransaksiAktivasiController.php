<?php

namespace App\Http\Controllers;

use App\Models\TransaksiAktivasi;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class TransaksiAktivasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get posts
        $transaksiAktivasi = TransaksiAktivasi::with(['member','pegawai'])->get();
        return response($transaksiAktivasi);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        $member = Member::find($id);
        // $idP = Auth::id();
        $date = date("y.m.");
        $new_id = IdGenerator::generate(['table' => 'transaksi_aktivasi', 'field' => 'ID_TRANSAKSIA', 'length' => 9, 'prefix' => $date]);
        $now = Carbon::now();
        $end = date('Y-m-d H:i:s', strtotime('+1 years'));

        //Fungsi Simpan Data ke dalam Database
        $ta = TransaksiAktivasi::create([
            'ID_TRANSAKSIA' => $new_id,
            'NO_MEMBER' => $member->NO_MEMBER,
            'ID_PEGAWAI' => 'P05',
            'TANGGAL_TRANSAKSIA'=> $now,
            'BIAYA_TRANSAKSIA'=> 3000000,
            'TANGGAL_EXP_TRANSAKSIA' => $end,

            
        ]);
        return response([
            'message' => 'Berhasil Menambahkan Data Aktivasi',
            'data' => $ta
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaksiAktivasi = TransaksiAktivasi::with(['member','pegawai'])->find($id);
        return response([
            'data' => $transaksiAktivasi
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransaksiAktivasi $transaksiAktivasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransaksiAktivasi $transaksiAktivasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiAktivasi $transaksiAktivasi)
    {
        //
    }
}
