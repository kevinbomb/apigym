<?php

namespace App\Http\Controllers;

use App\Models\PresensiInstruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiInstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presensiI = PresensiInstruktur::with('instruktur')->get();

        return response()->json([
            'success' => true,
            'data' => $presensiI
        ], 200);
    }

    public function indexId($id)
    {
        $pi = PresensiInstruktur::where('ID_INSTRUKTUR', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $pi
        ], 200);
    }

    public function indexOngoing()
    {
        $presensiI = PresensiInstruktur::with('instruktur')->whereNull('WAKTU_SELESAI')->get();

        return response()->json([
            'success' => true,
            'data' => $presensiI
        ], 200);
    }

    public function resetTerlambat()
    {
        $limit = date('Y-m-d H:i:s', strtotime('-1 months'));

        $presensiI = PresensiInstruktur::with('instruktur')->get();

        foreach ($presensiI as $member) {
            if($member->updated_at <= $limit){
                $member->TERLAMBAT = "00:00:00";
                $member->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'all late records cleared.',
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
            'ID_INSTRUKTUR' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $timestamp = Carbon::now()->format('H:i:s');
        // $timezone = Carbon::parse($timestamp)->addHours(7)->format('H:i:s');
        //Fungsi Simpan Data ke dalam Database
        $pi = PresensiInstruktur::create([
            'ID_INSTRUKTUR' => $request->ID_INSTRUKTUR,
            'WAKTU_MULAI' => $timestamp            
        ]);
        return response([
            'message' => 'Berhasil Menambahkan Data Presensi Instruktur',
            'data' => $pi
        ], 200);
    }

    public function selesai($id)
    {
        $now = Carbon::now();
        // $timezone = $now->copy()->addHours(7);
        $pi = PresensiInstruktur::find($id);
        if(is_null($pi)){
            return response()->json([
                'success' => false,
                'message' => 'PI tidak ditemukan',
                'data' => null
            ], 400);
        }
        $pi->WAKTU_SELESAI = $now;
        $pi->save();
        return response()->json([
            'success' => true,
            'message' => 'PI waktu selesai berhasil ditambahkan',
            'data' => null
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ID_INSTRUKTUR' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
         
        $presensiI = PresensiInstruktur::with('instruktur')->where('ID_INSTRUKTUR', $request->ID_INSTRUKTUR)->first();

        return response()->json([
            'success' => true,
            'data' => $presensiI
        ], 200);
    }

    public function getTerlambat($id){
        $result = PresensiInstruktur::where('ID_INSTRUKTUR', $id)
        ->sum('TERLAMBAT');

        return response()->json([
            'success' => true,
            'data' => $result
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiInstruktur $presensiInstruktur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresensiInstruktur $presensiInstruktur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresensiInstruktur $presensiInstruktur)
    {
        //
    }

    public function laporan($bulan){
        $results = DB::table('presensi_instruktur AS pi')
        ->select('i.NAMA_INSTRUKTUR')
        ->selectSub(function ($query) {
            $query->from('presensi_instruktur AS pi2')
                ->whereColumn('pi2.ID_INSTRUKTUR', 'pi.ID_INSTRUKTUR')
                ->select(DB::raw('COUNT(*)'));
        }, 'JUMLAH_HADIR')
        ->selectSub(function ($query) {
            $query->from('presensi_instruktur AS pi3')
                ->whereColumn('pi3.ID_INSTRUKTUR', 'pi.ID_INSTRUKTUR')
                ->select(DB::raw('SUM(pi3.TERLAMBAT)'));
        }, 'TERLAMBAT')
        ->whereMonth('created_at', '=', $bulan)
        ->join('instruktur AS i', 'pi.ID_INSTRUKTUR', '=', 'i.ID_INSTRUKTUR')
        ->groupBy('i.NAMA_INSTRUKTUR', 'pi.ID_INSTRUKTUR')
        ->get();

        return response([
            'data' => $results
        ], 200);
    }
}
