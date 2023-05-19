<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use App\Http\Requests\StoreInstrukturRequest;
use App\Http\Requests\UpdateInstrukturRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get posts
        $instruktur = Instruktur::get();
        return response($instruktur);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstrukturRequest $request)
    {
        
        // Get the highest existing ID_INST value
        $max_id = DB::table('instruktur')->max('id_instruktur');

        // Generate a new ID by adding 1 to the highest existing ID
        $new_id = $max_id + 1;

        //Fungsi Simpan Data ke dalam Database
        $instruktur = Instruktur::create([
            'ID_INSTRUKTUR' => $new_id,
            'NAMA_INSTRUKTUR' => $request->NAMA_INSTRUKTUR,
            'TANGGAL_LAHIR_INSTRUKTUR' => $request->TANGGAL_LAHIR_INSTRUKTUR,
            'ALAMAT_INSTRUKTUR'=> $request->ALAMAT_INSTRUKTUR,
            'NO_TELP_INSTRUKTUR'=> $request->NO_TELP_INSTRUKTUR,
            'GAJI_INSTRUKTUR'=> $request->GAJI_INSTRUKTUR,
            'PASSWORD_INSTRUKTUR' => bcrypt($request->PASSWORD_INSTRUKTUR)
        ]);
        return response([
            'message' => 'Berhasil Menambahkan Instruktur',
            'data' => $instruktur
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $instruktur = Instruktur::find($id);
        return response([
            'data' => $instruktur
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instruktur $instruktur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstrukturRequest $request, $id)
    {
        // find the Instruktur instance by id
        $instruktur = Instruktur::find($id);

        // update the Instruktur instance with the request data
        $request['PASSWORD_INSTRUKTUR'] = bcrypt($request->PASSWORD_INSTRUKTUR);
        $instruktur->update($request->all());

        return response([
            'message' => 'Berhasil Memperbarui Instruktur',
            'data' => $instruktur
        ], 200);
    }

    public function gantiPw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ID_INSTRUKTUR' => 'required',
            'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $ins = Instruktur::find($request->ID_INSTRUKTUR);
        $ins->password = bcrypt($request->new_password);
        
        if($ins->save()){
            return response([
                'message' => 'Berhasil Mengubah Password Instruktur',
                'data' => $ins,
            ], 200);
        }

        return response([
            'message' => 'Gagal Mereset Password Instruktur',
            'data' => null,
        ], 400);
    }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy($id)
    {
        // find the Instruktur instance by id
        $instruktur = Instruktur::find($id);

        // delete the Instruktur instance
        $instruktur->delete();

        return response()->json(['message' => 'Instruktur berhasil dihapus.'], 200);

    }
}
