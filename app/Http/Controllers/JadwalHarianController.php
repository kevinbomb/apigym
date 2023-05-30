<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Jadwal;
use App\Models\JadwalHarian;

class JadwalHarianController extends Controller
{
   
    public function cekGenerate(){
        $jadwalHarian = JadwalHarian::where('TANGGAL_JADWALH', '>', Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'))
            ->first();
        if(is_null($jadwalHarian)){
            return false;
        }else{
            return true;
        }
    }
 
    

    public function generateJadwalHarian(){
        
        if(self::cekGenerate()){
            return response()->json([
                'success' => false,
                'message' => 'Jadwal harian minggu ini sudah digenerate',
                'data' => null,
            ], 400);
        }
        $start_date = Carbon::now()->startOfWeek(Carbon::SUNDAY)->addDay();
        $end_date =  Carbon::now()->startOfWeek(Carbon::SUNDAY)->addDays(7);

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $jadwalUmum = Jadwal::where('HARI_JADWAL', Carbon::parse($date)->format('l'))->get();
            for($index = 0; $index < count($jadwalUmum); $index++){
                $jadwalHarian = JadwalHarian::create([
                    'TANGGAL_JADWALH' => $date,
                    'ID_JADWAL' => $jadwalUmum[$index]->ID_JADWAL,
                    'STATUS_JADWALH' => 1,
                    'ID_INSTRUKTUR' => $jadwalUmum[$index]->ID_INSTRUKTUR,
                    
                ]);
                
                // $jadwalHarian = new jadwalHarian;
                // $jadwalHarian->ID_JADWAL = $jadwalUmum[$index]->ID_JADWAL;
                // $jadwalHarian->TANGGAL_JADWALH = $date;
                
                // $jadwalHarian->save();    
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Jadwal harian tergenerate',
            'data' => null,
        ], 200);
    }
    
    public function libur($id){
        $jadwalHarian = JadwalHarian::find($id);
        if(is_null($jadwalHarian)){
            return response()->json([
                'success' => false,
                'message' => 'Jadwal harian tidak ditemukan',
                'data' => null
            ], 400);
        }
        $jadwalHarian->STATUS_JADWALH = !($jadwalHarian->STATUS_JADWALH);
        $jadwalHarian->save();
        return response()->json([
            'success' => true,
            'message' => 'Jadwal harian berhasil diliburkan',
            'data' => null
        ], 200);
    }
    
    

    public function index(){
        $indexJadwalHarian = JadwalHarian::join('jadwal', 'jadwalharian.ID_JADWAL', '=', 'jadwal.ID_JADWAL')
                        ->join('kelas', 'jadwal.ID_KELAS', '=', 'kelas.ID_KELAS')
                        ->join('instruktur', 'jadwal.ID_INSTRUKTUR', '=', 'instruktur.ID_INSTRUKTUR')
                        ->orderBy('jadwalharian.TANGGAL_JADWALH', 'asc')
                        ->orderBy('jadwal.SESI')
                        ->select('jadwalharian.TANGGAL_JADWALH', 'jadwal.SESI','jadwal.HARI_JADWAL', 'kelas.NAMA_KELAS', 'instruktur.NAMA_INSTRUKTUR', 'jadwalharian.STATUS_JADWALH', 'jadwalharian.ID_JADWALH')
                        ->get();
                        // ->groupBy('TANGGAL_JADWALH');
                       

        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal Harian',
            'data' => $indexJadwalHarian,
        ], 200);
    }

    public function indexToday()
    {
        $currentDate = Carbon::now()->toDateString();
    
        $indexJadwalHarian = JadwalHarian::join('jadwal', 'jadwalharian.ID_JADWAL', '=', 'jadwal.ID_JADWAL')
                        ->join('kelas', 'jadwal.ID_KELAS', '=', 'kelas.ID_KELAS')
                        ->join('instruktur', 'jadwal.ID_INSTRUKTUR', '=', 'instruktur.ID_INSTRUKTUR')
                        ->orderBy('jadwalharian.TANGGAL_JADWALH', 'asc')
                        ->orderBy('jadwal.SESI')
                        ->select('jadwalharian.TANGGAL_JADWALH', 'jadwal.SESI','jadwal.HARI_JADWAL', 'kelas.NAMA_KELAS', 'instruktur.ID_INSTRUKTUR', 'instruktur.NAMA_INSTRUKTUR', 'jadwalharian.STATUS_JADWALH', 'jadwalharian.ID_JADWALH')
                        ->whereDate('TANGGAL_JADWALH', $currentDate)
                        ->get();
                        // ->groupBy('TANGGAL_JADWALH');
                       

        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal Harian Hari Ini',
            'data' => $indexJadwalHarian,
        ], 200);
    }

    public function show($id)
    {
        $jadwalHarian = JadwalHarian::join('jadwal', 'jadwalharian.ID_JADWAL', '=', 'jadwal.ID_JADWAL')
            ->join('kelas', 'jadwal.ID_KELAS', '=', 'kelas.ID_KELAS')
            ->join('instruktur', 'jadwal.ID_INSTRUKTUR', '=', 'instruktur.ID_INSTRUKTUR')
            ->where('jadwalharian.ID_JADWALH', $id)
            ->select(
                'jadwalharian.TANGGAL_JADWALH',
                'jadwal.SESI',
                'jadwal.HARI_JADWAL',
                'kelas.NAMA_KELAS',
                'instruktur.NAMA_INSTRUKTUR',
                'jadwalharian.STATUS_JADWALH',
                'jadwalharian.ID_JADWALH'
            )
            ->first();

        if ($jadwalHarian) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Harian Found',
                'data' => $jadwalHarian,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Harian Not Found',
            ], 404);
        }
    }




}