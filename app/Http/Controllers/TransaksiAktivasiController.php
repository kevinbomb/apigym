<?php

namespace App\Http\Controllers;

use App\Models\TransaksiAktivasi;
use Illuminate\Http\Request;

class TransaksiAktivasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get posts
        $transaksiAktivasi = TransaksiAktivasi::with(['member','pegawai'])->get();
        return response($transaksiAktivasi);
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
    public function show(TransaksiAktivasi $transaksiAktivasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransaksiAktivasi $transaksiAktivasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransaksiAktivasi $transaksiAktivasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransaksiAktivasi $transaksiAktivasi)
    {
        //
    }
}
