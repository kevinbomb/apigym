<?php

namespace App\Http\Controllers;

use App\Models\TransaksiUang;
use App\Models\Member;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;


class TransaksiUangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksiUang = TransaksiUang::with(['member','pegawai','promo'])->get();
        return response($transaksiUang);
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
        $validator = Validator::make($request->all(), [
            'JUMLAH_TRANSAKSIU' => 'required | numeric',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $member = Member::find($id);
            // $idP = Auth::id();
            $date = date("y.m.");
            $new_id = IdGenerator::generate(['table' => 'transaksi_depositu', 'field' => 'NO_TRANSAKSIU', 'length' => 9, 'prefix' => $date]);
            $now = date('Y-m-d H:i:s');
            $promo = NULL;

            if($request->JUMLAH_TRANSAKSIU>=3000000){
                $bonus = 300000;
                $promo = 1;
            }else{
                $bonus = 0;
            }

            $total = ($request->JUMLAH_TRANSAKSIU + $bonus + $member->SALDO_MEMBER);

        //Fungsi Simpan Data ke dalam Database
        $tu = TransaksiUang::create([
            'NO_TRANSAKSIU' => $new_id,
            'NO_MEMBER' => $member->NO_MEMBER,
            'ID_PEGAWAI' => 'P05',
            'ID_PROMO' => $promo,
            'TANGGAL_TRANSAKSIU'=> $now,
            'JUMLAH_TRANSAKSIU'=> $request->JUMLAH_TRANSAKSIU,
            'BONUS_TRANSAKSIU' => $bonus,
            'SISA_SALDO_TRANSAKSIU' => $member->SALDO_MEMBER,
            'TOTAL_TRANSAKSIU' => $total,
            
        ]);
        $member->SALDO_MEMBER = $total;
        $member->save();
        return response([
            'message' => 'Berhasil Menambahkan Data Deposit Uang',
            'data' => $tu
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaksiUang = TransaksiUang::with(['member','pegawai','promo'])->find($id);
        return response([
            'data' => $transaksiUang
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransaksiUang $transaksiUang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransaksiUang $transaksiUang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiUang $transaksiUang)
    {
        //
    }
}
