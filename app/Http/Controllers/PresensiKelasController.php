<?php

namespace App\Http\Controllers;

use App\Models\PresensiKelas;
use App\Models\Member;
use App\Models\PresensiInstruktur;
use App\Models\JadwalHarian;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pk = PresensiKelas::with(['member'])->get();

        return response()->json([
            'success' => true,
            'data' => $pk
        ], 200);
    }

    public function indexToday()
    {
        $currentDate = Carbon::now()->toDateString();
    
        $pg = PresensiKelas::with('member')
            ->whereDate('TANGGAL_PRESENSIK', $currentDate)
            ->whereNull('KEHADIRAN')
            ->get();
    
        return response()->json([
            'success' => true,
            'data' => $pg
        ], 200);
    }

    public function indexId($id)
    {
        $pk = PresensiKelas::where('NO_MEMBER', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $pk
        ], 200);
    }

    public function indexSatu()
    {
        $pk = PresensiKelas::whereNotNull('WAKTU_PRESENSIK')->with(['member'])->get();

        return response()->json([
            'success' => true,
            'data' => $pk
        ], 200);
    }

    public function konfirmasi($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ID_INSTRUKTUR' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cek = PresensiInstruktur::where('ID_INSTRUKTUR', $request->ID_INSTRUKTUR)
            ->whereNull('WAKTU_SELESAI')
            ->count();
        if($cek == 0){
            return response([
                'message' => 'Instruktur Belum di presensi MO'
            ], 400);
        }

        $now = Carbon::now();
        $pk = PresensiKelas::find($id);
        if(is_null($pk)){
            return response()->json([
                'success' => false,
                'message' => 'PK tidak ditemukan',
                'data' => null
            ], 400);
        }
        $pk->WAKTU_PRESENSIK = $now;
        $pk->KEHADIRAN = 1;
        $pk->save();

        $member = Member::find($pk->NO_MEMBER);
        if($member->PAKET_MEMBER > 0){
            $member->PAKET_MEMBER = $member->PAKET_MEMBER-1;
        }else{
            $member->SALDO_MEMBER = $member->SALDO_MEMBER-$pk->TARIF_PRESENSIK;
        }
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'PK berhasil dikonfirmasi',
            'data' => null
        ], 200);
    }

    public function absen($id)
    {
        $now = Carbon::now();
        $pk = PresensiKelas::find($id);
        if(is_null($pk)){
            return response()->json([
                'success' => false,
                'message' => 'PK tidak ditemukan',
                'data' => null
            ], 400);
        }
        $pk->WAKTU_PRESENSIK = $now;
        $pk->KEHADIRAN = 0;
        $pk->save();

        $member = Member::find($pk->NO_MEMBER);
        if($member->PAKET_MEMBER > 0){
            $member->PAKET_MEMBER = $member->PAKET_MEMBER-1;
        }else{
            $member->SALDO_MEMBER = $member->SALDO_MEMBER-$pk->TARIF_PRESENSIK;
        }
        $member->save();
        
        return response()->json([
            'success' => true,
            'message' => 'PK berhasil dikonfirmasi, ,meski member tak hadir',
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
            'ID_JADWALH' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jadwalHarian = JadwalHarian::join('jadwal', 'jadwalharian.ID_JADWAL', '=', 'jadwal.ID_JADWAL')
            ->join('kelas', 'jadwal.ID_KELAS', '=', 'kelas.ID_KELAS')
            ->join('instruktur', 'jadwal.ID_INSTRUKTUR', '=', 'instruktur.ID_INSTRUKTUR')
            ->where('jadwalharian.ID_JADWALH', $request->ID_JADWALH)
            ->select(
                'jadwalharian.TANGGAL_JADWALH',
                'jadwal.SESI',
                'jadwal.HARI_JADWAL',
                'kelas.NAMA_KELAS',
                'kelas.BIAYA_KELAS',
                'instruktur.NAMA_INSTRUKTUR',
                'jadwalharian.STATUS_JADWALH',
                'jadwalharian.ID_JADWALH'
            )
            ->first();
        
        $date = date("y.m.");
        $new_id = IdGenerator::generate(['table' => 'presensikelas', 'field' => 'NO_PRESENSIK', 'length' => 9, 'prefix' => $date]);
        $now = Carbon::now();

        //Fungsi Simpan Data ke dalam Database
        $izin = PresensiKelas::create([
            'NO_PRESENSIK' => $new_id,
            'NO_MEMBER' => $request->NO_MEMBER,
            'ID_JADWALH' => $request->ID_JADWALH,
            'TANGGAL_PRESENSIK' => $jadwalHarian->TANGGAL_JADWALH,
            'SESI' => $jadwalHarian->SESI,
            'NAMA_KELAS' => $jadwalHarian->NAMA_KELAS,
            'TANGGAL_PRESENSIK_DIBUAT'=> $now,
            'TARIF_PRESENSIK'=> $jadwalHarian->BIAYA_KELAS
            
        ]);
        return response([
            'message' => 'Berhasil Menambahkan Data Presensi Kelas',
            'data' => $izin
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pk = PresensiKelas::with('member')->find($id);
        return response([
            'data' => $pk
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiKelas $presensiKelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function laporan($bulan)
    {
        $results = DB::table('presensikelas AS p')
            ->select('p.NAMA_KELAS', 'i.NAMA_INSTRUKTUR', DB::raw('COUNT(p.ID_JADWALH) AS JUMLAH_PESERTA'))
            ->selectSub(function ($query) {
                $query->from('jadwalharian AS j')
                    ->whereColumn('j.ID_JADWALH', 'p.ID_JADWALH')
                    ->where('j.STATUS_JADWALH', 0)
                    ->select(DB::raw('COUNT(*)'));
            }, 'LIBUR')
            ->whereMonth('TANGGAL_PRESENSIK', '=', $bulan)
            ->join('jadwalharian AS j', 'p.ID_JADWALH', '=', 'j.ID_JADWALH')
            ->join('instruktur AS i', 'j.ID_INSTRUKTUR', '=', 'i.ID_INSTRUKTUR')
            ->groupBy('p.NAMA_KELAS', 'i.NAMA_INSTRUKTUR', 'p.ID_JADWALH')
            ->get();
        
        $totalCount = PresensiKelas::whereMonth('TANGGAL_PRESENSIK', '=', $bulan)
            ->select(PresensiKelas::raw('COUNT(NO_PRESENSIK) AS Peserta'))
            ->groupBy('TANGGAL_PRESENSIK')
            ->get()
            ->sum('Peserta');

        return response([
            'data' => $results,
            'total' => $totalCount
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pk = PresensiKelas::find($id);
        $now = Carbon::now();
        $tomorrow = $now->addDay();
        $yesterday = $now->subDay();
    
        if ($pk->TANGGAL_PRESENSIK > $yesterday) {
            $pk->delete();
            return response()->json(['message' => 'Presensi kelas berhasil dihapus.'], 200);
        }
    
        return response()->json(['message' => 'Presensi kelas gagal dihapus.'], 200);
    }
}
