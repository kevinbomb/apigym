<?php

namespace App\Http\Controllers;

use App\Models\PresensiKelas;
use App\Models\JadwalHarian;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PresensiKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function indexId($id)
    {
        $pk = PresensiKelas::where('NO_MEMBER', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $pk
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
    public function show(PresensiKelas $presensiKelas)
    {
        //
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
    public function update(Request $request, PresensiKelas $presensiKelas)
    {
        //
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
