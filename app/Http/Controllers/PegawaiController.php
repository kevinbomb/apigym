<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get posts
        $pegawai = Pegawai::get();
        //render view with posts
        // return view('instruktur.index', compact('instruktur'));
        return new JsonResponse($pegawai);
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
    public function store(StorePegawaiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePegawaiRequest $request, Pegawai $pegawai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        //
    }

    public function gantiPw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pegawai' => 'required',
            'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $ins = Pegawai::find($request->id_pegawai);
        $ins->password = bcrypt($request->new_password);
        
        if($ins->save()){
            return response([
                'message' => 'Berhasil Mengubah Password Pegawai',
                'data' => $ins,
            ], 200);
        }

        return response([
            'message' => 'Gagal Mereset Password Pegawai',
            'data' => null,
        ], 400);
    }
}
