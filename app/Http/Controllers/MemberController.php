<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use Illuminate\Http\JsonResponse;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $member = Member::get();
        return response($member);
    }

    public function cekStatus()
    {
        $currentDate = Carbon::now()->toDateString();

        $members = Member::where('TANGGAL_EXP_MEMBER', '<=', $currentDate)->get();

        foreach ($members as $member) {
            $member->TANGGAL_EXP_MEMBER = NULL;
            $member->STATUS_MEMBER = 0;
            $member->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Member status updated successfully.',
            'data' => null
        ], 200);
    }

    public function cekStatusIndex(){
        $currentDate = Carbon::now()->toDateString();

        $members = Member::where('TANGGAL_EXP_MEMBER', '<=', $currentDate)->get();
        return response([
            'message' => 'Berhasil mencari Member aktivasi habis',
            'data' => $members
        ], 200);
    }

    public function cekPaketTampil(){
        $currentDate = Carbon::now()->toDateString();

        $members = Member::where('TANGGAL_EXP_PAKET', '<=', $currentDate)->get();
        return response([
            'message' => 'Berhasil mencari Member paket habis',
            'data' => $members
        ], 200);
    }

    public function cekPaketUpdate()
    {
        $currentDate = Carbon::now()->toDateString();

        $members = Member::where('TANGGAL_EXP_PAKET', '<=', $currentDate)->get();

        foreach ($members as $member) {
            $member->TANGGAL_EXP_PAKET = null;
            $member->PAKET_MEMBER = 0;
            $member->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Member package emptied successfully.',
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
    public function store(StoreMemberRequest $request)
    {
        $date = date("y.m.");
        $new_id = IdGenerator::generate(['table' => 'member', 'field' => 'NO_MEMBER', 'length' => 9, 'prefix' => $date]);
        $end = date('Y-m-d', strtotime('+1 years'));
        // $request['STATUS_MEMBER'] = 0;
        // $request['NO_MEMBER'] = $id;

        //Fungsi Simpan Data ke dalam Database
        $member = Member::create([
            'NO_MEMBER' => $new_id,
            'NAMA_MEMBER' => $request->NAMA_MEMBER,
            'TANGGAL_LAHIR_MEMBER' => $request->TANGGAL_LAHIR_MEMBER,
            'ALAMAT_MEMBER'=> $request->ALAMAT_MEMBER,
            'NO_TELP_MEMBER'=> $request->NO_TELP_MEMBER,
            'PASSWORD_MEMBER' => bcrypt($request->PASSWORD_MEMBER),
            'STATUS_MEMBER' => 0,

            
        ]);
        return response([
            'message' => 'Berhasil Menambahkan Member',
            'data' => $member
        ], 200);

        // $member = Member::create($request);
        // return response([
        //     'message' => 'Berhasil Menambahkan Member',
        //     'data' => $member
        // ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $member = Member::find($id);
        return response([
            'data' => $member
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, $id)
    {
        // find the Instruktur instance by id
        $member = Member::find($id);

        // update the member instance with the request data
        $request['PASSWORD_MEMBER'] = bcrypt($request->PASSWORD_MEMBER);
        $member->update($request->all());

        return response([
            'message' => 'Berhasil Memperbarui Member',
            'data' => $member
        ], 200);
    }

    public function aktivasi($id)
    {
        // find the Instruktur instance by id
        $member = Member::find($id);

        $end = date('Y-m-d', strtotime('+1 years'));
        
        $member->STATUS_MEMBER = 1;
        $member->TANGGAL_EXP_MEMBER = $end;
        // $member->update($member->all());
        if($member->save()){
            return response([
                'message' => 'Berhasil Mengaktivasi Member',
                'data' => $member,
            ], 200);
        }

        return response([
            'message' => 'Gagal Mengaktivasi Member',
            'data' => null,
        ], 400);
    }

    public function resetPw($id)
    {
        // find the Instruktur instance by id
        $member = Member::find($id);

        // update the member instance with the request data
        // $member->TANGGAL_LAHIR_MEMBER = ($member->TANGGAL_LAHIR_MEMBER);
        $member->password = bcrypt($member->TANGGAL_LAHIR_MEMBER);
        // $member->update($member->all());
        if($member->save()){
            return response([
                'message' => 'Berhasil Mereset Password Member',
                'data' => $member,
            ], 200);
        }

        return response([
            'message' => 'Gagal Mereset Password Member',
            'data' => null,
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $member->delete();
        return response()->json(['message' => 'member berhasil dihapus.'], 200);
    }
}
