<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Http\Requests\StoreJadwalRequest;
use App\Http\Requests\UpdateJadwalRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function cekJadwalInstruktur(StoreJadwalRequest $request){
        $cek = Jadwal::where('ID_INSTRUKTUR', $request->ID_INSTRUKTUR)
        ->where('HARI_JADWAL', $request->HARI_JADWAL)->where('SESI', $request->SESI)->first();
        if(is_null($cek)){
            return false;
        }else{
            return true;
        }
    }

    public function cekJadwalInstruktur2(UpdateJadwalRequest $request){
        $cek = Jadwal::where('ID_INSTRUKTUR', $request->ID_INSTRUKTUR)
        ->where('HARI_JADWAL', $request->HARI_JADWAL)->where('SESI', $request->SESI)->first();
        if(is_null($cek)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get posts
        $jadwal = Jadwal::with(['instruktur','kelas'])->get();
        return response($jadwal);
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
    public function store(StoreJadwalRequest $request)
    {
        $max_id = DB::table('jadwal')->max('id_jadwal');
        $new_id = $max_id + 1;

        //eits cek dulu
        if(self::cekJadwalInstruktur($request)){
            return response()->json([
                'tabrakan'=>true,
                'success' => false,
                'message' => 'Jadwal Instruktur Bertabrakan',
                'data' => null
            ], 400);
        }
        $jadwal = Jadwal::create([
            'ID_JADWAL' => $new_id,
            'ID_KELAS' => $request->ID_KELAS,
            'SESI' => $request->SESI,
            'HARI_JADWAL'=> $request->HARI_JADWAL,
            'ID_INSTRUKTUR'=> $request->ID_INSTRUKTUR
        ]);
        if($jadwal->save()){
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Umum berhasil ditambahkan',
                'data' => $jadwal
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Umum gagal ditambahkan',
                'data' => null
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['instruktur','kelas'])->find($id);
        return response([
            'data' => $jadwal
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJadwalRequest $request, $id)
    {
        $jadwal = Jadwal::find($id);

        if(self::cekJadwalInstruktur2($request)){
            return response()->json([
                'tabrakan'=>true,
                'success' => false,
                'message' =>  'Jadwal Instruktur Bertabrakan',
                'data' => null
            ], 400);
        }
        // update the jadwal instance with the request data
        $jadwal->update($request->all());

        if($jadwal->save()){
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diubah',
                'data' => $jadwal
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Jadwal gagal diubah',
                'data' => null
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);
        $jadwal->delete();
        return response()->json(['message' => 'jadwal berhasil dihapus.'], 200);
    }
}
