<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalAbsen;
use App\Models\JenisIzin;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'category_name' => 'home',
            'page_name' => 'home',

        ];
         $data_absen = [];
         $data['jam_kerja'] = Absensi::where('hari', true)->whereMonth('tanggal',2)->count();
         $data['kehadiran'] = Absensi::where('hari', true)->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal',2)->count();
         $data['tepat_waktu'] = Absensi::where('hari', true)->where('is_telat', false)->whereNotNull('jam_masuk')->whereNull('jenis_izin_id')->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal',2)->count();
         $data['telat'] = Absensi::where('hari', true)->where('is_telat', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal',2)->count();
         $data['tanpa_keterangan'] = Absensi::where('keterangan', 'Tidak Absen')->whereMonth('tanggal',2)->count();
         $data['izin']  = Absensi::whereNotNull('jenis_izin_id')->whereMonth('tanggal',2)->count();
         
         $jenis_izin_dinas = JenisIzin::where('hak', 1)->get();
         $data['izin_dinas']  = Absensi::whereIn('jenis_izin_id', $jenis_izin_dinas->pluck('id'))->whereMonth('tanggal',2)->count();
         
         $data['tanpa_keterangan'] = Absensi::where('keterangan', 'Tidak Absen')->whereMonth('tanggal',2)->count();
         
         $chart_performance[] = [
             'name' => 'Tepat Waktu',
             'y'=> $data['tepat_waktu']/$data['jam_kerja'],
             
         ];
         $chart_performance[] = [
            'name' => 'Telat',
            'y'=> $data['telat']/$data['jam_kerja']
        ];
        $chart_performance[] = [
            'name' => 'Tanpa Keterangan',
            'y'=> $data['tanpa_keterangan']/$data['jam_kerja'],
            'sliced'=> true,
             'selected'=> true,
        ];
        $chart_performance[] = [
            'name' => 'Izin',
            'y'=> ($data['izin']- $data['izin_dinas'])/$data['jam_kerja']
        ];
        $chart_performance[] = [
            'name' => 'Dinas',
            'y'=> $data['izin_dinas']/$data['jam_kerja']
        ];

        // return $chart_performance;

        
        return view('dashboard', compact('chart_performance'))->with($data);
    }
}
