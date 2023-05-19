<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKelas;
use App\Models\Member;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;

class TransaksiKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksiKelas = TransaksiKelas::with(['member','pegawai','promo','kelas'])->get();
        return response($transaksiKelas);
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
    public function store(Request $request, $id)
    {
        $member = Member::find($id);
        if($member->PAKET_MEMBER>0){
            return response()->json(['message' => 'Paket data masih ada!'], 400);
        }
        $validator = Validator::make($request->all(), [
            'ID_PROMO' => 'required',
            'ID_KELAS' => 'required',
            'JUMLAH_TRANSAKSIK' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            
            $kelas = Kelas::find($request->ID_KELAS);
            $harga = $kelas->BIAYA_KELAS;
            // $harga = 150000;
            // $idP = Auth::id();
            $date = date("y.m.");
            $new_id = IdGenerator::generate(['table' => 'transaksi_depositk', 'field' => 'NO_TRANSAKSIK', 'length' => 9, 'prefix' => $date]);
            $now = date('Y-m-d H:i:s');
            $exp = date('Y-m-d H:i:s', strtotime('+1 months'));
            $jumlah = $request->JUMLAH_TRANSAKSIK;
            $bonus = 0;
            if($request->ID_PROMO==2){
                $bonus = 1;
            }else if($request->ID_PROMO==3){
                $bonus = 2;
                $exp = date('Y-m-d H:i:s', strtotime('+2 months'));
            }

            $biaya = $harga * $jumlah;
            $total = ($jumlah + $bonus);

        //Fungsi Simpan Data ke dalam Database
        $tk = TransaksiKelas::create([
            'NO_TRANSAKSIK' => $new_id,
            'NO_MEMBER' => $member->NO_MEMBER,
            'ID_PEGAWAI' => 'P05',
            'ID_PROMO' => $request->ID_PROMO,
            'ID_KELAS' => $request->ID_KELAS,
            'TANGGAL_TRANSAKSIK'=> $now,
            'BIAYA_TRANSAKSIK'=> $biaya,
            'TANGGAL_EXP_TRANSAKSIK'=> $exp,
            'JUMLAH_TRANSAKSIK'=> $jumlah,
            'BONUS_TRANSAKSIK' => $bonus,
            'SISA_SALDO_TRANSAKSIK' => $member->SALDO_MEMBER,
            'TOTAL_TRANSAKSIK' => $total,
            
        ]);
        $member->PAKET_MEMBER = $total;
        $member->TANGGAL_EXP_PAKET = $exp;
        $member->save();
        return response([
            'message' => 'Berhasil Menambahkan Data Deposit Kelas',
            'data' => $tk
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaksiKelas = TransaksiKelas::with(['member','pegawai','promo','kelas'])->find($id);
        return response([
            'data' => $transaksiKelas
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransaksiKelas $transaksiKelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransaksiKelas $transaksiKelas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiKelas $transaksiKelas)
    {
        //
    }
}
