<?php

namespace App\Http\Controllers;

use App\Models\TransaksiAktivasi;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function laporan(){
        $results = DB::select('SELECT
        MID(ta.TANGGAL_TRANSAKSIA, 6, 2) as `Month`,
        sum(ta.BIAYA_TRANSAKSIA) AS Aktivasi,
        (SELECT sum(tk.BIAYA_TRANSAKSIK) FROM transaksi_depositk tk WHERE LEFT(tk.TANGGAL_TRANSAKSIK, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7))  as DepositK,
        (SELECT sum(tu.JUMLAH_TRANSAKSIU) FROM transaksi_depositu tu WHERE LEFT(tu.TANGGAL_TRANSAKSIU, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7)) as DepositU,
        ((sum(ta.BIAYA_TRANSAKSIA)) + (SELECT sum(tk.BIAYA_TRANSAKSIK) FROM transaksi_depositk tk WHERE LEFT(tk.TANGGAL_TRANSAKSIK, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7)) + (SELECT sum(tu.JUMLAH_TRANSAKSIU) FROM transaksi_depositu tu WHERE LEFT(tu.TANGGAL_TRANSAKSIU, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7)) ) as Total
        FROM transaksi_aktivasi ta
        GROUP BY MID(ta.TANGGAL_TRANSAKSIA, 6, 2);');

        // $results = DB::table('transaksi_aktivasi AS ta')
        // ->selectRaw("MID(ta.TANGGAL_TRANSAKSIA, 6, 2) as Bulan")
        // ->selectRaw("SUM(ta.BIAYA_TRANSAKSIA) AS Aktivasi")
        // ->selectRaw("(SELECT SUM(tk.BIAYA_TRANSAKSIK) FROM transaksi_depositk tk WHERE LEFT(tk.TANGGAL_TRANSAKSIK, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7)) AS DepositK")
        // ->selectRaw("(SELECT SUM(tu.JUMLAH_TRANSAKSIU) FROM transaksi_depositu tu WHERE LEFT(tu.TANGGAL_TRANSAKSIU, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7)) AS DepositU")
        // ->selectRaw("( (SUM(ta.BIAYA_TRANSAKSIA)) + (SELECT SUM(tk.BIAYA_TRANSAKSIK) FROM transaksi_depositk tk WHERE LEFT(tk.TANGGAL_TRANSAKSIK, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7)) + (SELECT SUM(tu.JUMLAH_TRANSAKSIU) FROM transaksi_depositu tu WHERE LEFT(tu.TANGGAL_TRANSAKSIU, 7) = LEFT(ta.TANGGAL_TRANSAKSIA, 7)) ) AS Total")
        // ->groupBy(DB::raw("MID(ta.TANGGAL_TRANSAKSIA, 6, 2)"))
        // ->groupBy(DB::raw("LEFT(ta.TANGGAL_TRANSAKSIA, 7)"))
        // ->groupBy('ta.TANGGAL_TRANSAKSIA')
        // ->get();

        $total = DB::select (' SELECT (SELECT SUM(ta.BIAYA_TRANSAKSIA) FROM transaksi_aktivasi ta) + (SELECT SUM(tk.BIAYA_TRANSAKSIK) FROM transaksi_depositk tk) + (SELECT SUM(tu.JUMLAH_TRANSAKSIU) FROM transaksi_depositu tu) AS subtotal ');

        return response([
            'data' => $results,
            'total' => $total
        ], 200);
    }
}
