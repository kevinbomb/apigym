<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PerizinanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexNol()
    {
        $perizinan = Perizinan::where('STATUS_PERIZINAN', 0)->with('instruktur')->get();

        return response()->json([
            'success' => true,
            'data' => $perizinan
        ], 200);
    }

    public function indexSatu()
    {
        $perizinan = Perizinan::where('STATUS_PERIZINAN', 1)->with('instruktur')->get();

        return response()->json([
            'success' => true,
            'data' => $perizinan
        ], 200);
    }

    public function indexId($id)
    {
        $perizinan = Perizinan::where('ID_INSTRUKTUR', $id)->with('instruktur')->get();

        return response()->json([
            'success' => true,
            'data' => $perizinan
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
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'KETERANGAN_PERIZINAN' => 'required',
            'TANGGAL_PERIZINAN' => 'required | date',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $now = date('Y-m-d H:i:s');

        //Fungsi Simpan Data ke dalam Database
        $izin = Perizinan::create([
            'KETERANGAN_PERIZINAN' => $request->KETERANGAN_PERIZINAN,
            'STATUS_PERIZINAN' => 0,
            'TANGGAL_PERIZINAN' => $request->TANGGAL_PERIZINAN,
            'TANGGAL_PERIZINAN_DIAJUKAN'=> $now,
            'ID_INSTRUKTUR'=> $id
            
        ]);
        return response([
            'message' => 'Berhasil Menambahkan Data Perizinan',
            'data' => $izin
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Perizinan $perizinan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perizinan $perizinan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function konfirmasi($id)
    {
        $perizinan = Perizinan::find($id);
        if(is_null($perizinan)){
            return response()->json([
                'success' => false,
                'message' => 'Izin tidak ditemukan',
                'data' => null
            ], 400);
        }
        $perizinan->STATUS_PERIZINAN = !($perizinan->STATUS_PERIZINAN);
        $perizinan->save();
        return response()->json([
            'success' => true,
            'message' => 'Izin berhasil dikonfirmasi',
            'data' => null
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perizinan = Perizinan::find($id);
        $perizinan->delete();
        return response()->json(['message' => 'perizinan berhasil dihapus.'], 200);
    }
}
