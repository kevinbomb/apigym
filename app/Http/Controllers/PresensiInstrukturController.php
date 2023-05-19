<?php

namespace App\Http\Controllers;

use App\Models\PresensiInstruktur;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PresensiInstruktur $presensiInstruktur)
    {
        //
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
}
