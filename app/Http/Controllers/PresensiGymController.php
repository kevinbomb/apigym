<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PresensiGym;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PresensiGymController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pg = PresensiGym::with(['member'])->get();

        return response()->json([
            'success' => true,
            'data' => $pg
        ], 200);
    }


    public function indexId($id)
    {
        $pg = PresensiGym::where('NO_MEMBER', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $pg
        ], 200);
    }

    public function indexSatu()
    {
        $pg = PresensiGym::whereNotNull('WAKTU_PRESENSIG')->with(['member'])->get();

        return response()->json([
            'success' => true,
            'data' => $pg
        ], 200);
    }

    public function konfirmasi($id)
    {
        $now = Carbon::now();
        $timezone = $now->copy()->addHours(7);
        $pg = PresensiGym::find($id);
        if(is_null($pg)){
            return response()->json([
                'success' => false,
                'message' => 'PG tidak ditemukan',
                'data' => null
            ], 400);
        }
        $pg->WAKTU_PRESENSIG = $timezone;
        $pg->save();
        return response()->json([
            'success' => true,
            'message' => 'PG berhasil dikonfirmasi',
            'data' => null
        ], 200);
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NO_MEMBER' => 'required',
            'TANGGAL_PRESENSIG' => 'required',
            'SLOT_WAKTU_PRESENSIG' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member = Member::find($request->NO_MEMBER);
        if ($member->STATUS_MEMBER == 0){
            return response([
                'message' => 'Member belum melakukan aktivasi'
            ], 400);
        }

        $booking = PresensiGym::where('SLOT_WAKTU_PRESENSIG', $request->SLOT_WAKTU_PRESENSIG)
            ->where('TANGGAL_PRESENSIG', $request->TANGGAL_PRESENSIG)
            ->count();
        if($booking >= 10){
            return response([
                'message' => 'Kuota sesi sudah penuh'
            ], 400);
        }

        $date = date("y.m.");
        $new_id = IdGenerator::generate(['table' => 'presensi_gym', 'field' => 'NO_PRESENSIG', 'length' => 9, 'prefix' => $date]);
        $now = Carbon::now('Asia/Bangkok');
        $timezone = $now->copy()->addHours(7);
        //Fungsi Simpan Data ke dalam Database
        $pg = PresensiGym::create([
            'NO_PRESENSIG' => $new_id,
            'NO_MEMBER' => $request->NO_MEMBER,
            'TANGGAL_PRESENSIG' => $request->TANGGAL_PRESENSIG,
            'SLOT_WAKTU_PRESENSIG' => $request->SLOT_WAKTU_PRESENSIG,
            'TANGGAL_PRESENSIG_DIBUAT'=> $timezone
            
        ]);
        return response([
            'message' => 'Berhasil Menambahkan Data Booking Gym',
            'data' => $pg
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pg = PresensiGym::with('member')->find($id);
        return response([
            'data' => $pg
        ], 200);
    }

    public function laporanJan(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 1)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 1)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanFeb(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 2)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 2)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanMar(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 3)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 3)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanApr(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 4)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 4)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanMay(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 5)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 5)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanJun(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 6)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 6)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanJul(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 7)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 7)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanAug(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 8)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 8)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanSep(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 9)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 9)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanOkt(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 10)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 10)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanNov(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 11)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 11)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }
    public function laporanDes(){
        $results = PresensiGym::select('TANGGAL_PRESENSIG', PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->whereMonth('TANGGAL_PRESENSIG', '=', 12)
        ->groupBy('TANGGAL_PRESENSIG')
        ->get();

        $totalCount = PresensiGym::whereMonth('TANGGAL_PRESENSIG', '=', 12)
        ->select(PresensiGym::raw('COUNT(NO_PRESENSIG) AS Peserta'))
        ->groupBy('TANGGAL_PRESENSIG')
        ->get()
        ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiGym $presensiGym)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresensiGym $presensiGym)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pg = PresensiGym::find($id);
        $now = Carbon::now();
        $timezone = $now->copy()->subHours(12);
        $yesterday = $timezone->addDay();
    
        if ($pg->TANGGAL_PRESENSIG >= $yesterday) {
            $pg->delete();
            return response()->json(['message' => 'Booking gym berhasil dihapus.'], 200);
        }
    
        return response()->json(['message' => 'Booking gym gagal dihapus.'], 200);
    }
}
