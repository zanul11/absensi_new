<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalAbsen;
use App\Models\JenisIzin;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        // return Kehadiran::whereNotNull('deleted_at')->withTrashed()->get();
        $data = [
            'category_name' => 'home',
            'page_name' => 'home',

        ];
         $data_absen = [];
         $data['jam_kerja'] = Absensi::where('hari', true)->whereMonth('tanggal',date('m'))->count();
         $data['kehadiran'] = Absensi::where('hari', true)->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal',date('m'))->count();
         $data['tepat_waktu'] = Absensi::where('hari', true)->where('is_telat', false)->whereNotNull('jam_masuk')->whereNull('jenis_izin_id')->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal',date('m'))->count();
         $data['telat'] = Absensi::where('hari', true)->where('is_telat', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal',date('m'))->count();
         $data['tanpa_keterangan'] = Absensi::where('keterangan', 'Tidak Absen')->whereMonth('tanggal',date('m'))->count();
         $data['izin']  = Absensi::whereNotNull('jenis_izin_id')->whereMonth('tanggal',date('m'))->count();
         
         $jenis_izin_dinas = JenisIzin::where('hak', 1)->get();
         $data['izin_dinas']  = Absensi::whereIn('jenis_izin_id', $jenis_izin_dinas->pluck('id'))->whereMonth('tanggal',date('m'))->count();
         
         $data['tanpa_keterangan'] = Absensi::where('keterangan', 'Tidak Absen')->whereMonth('tanggal',date('m'))->count();
         
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



    public function data()
    {
        $pegawai = Pegawai::all();
       
      
            for ($i = 0; $i <= 5; $i++) {
                $getHari = date('w', strtotime("+" . $i . " day", strtotime('2024-02-01')));
                $getTgl = date('Y-m-d', strtotime("+" . $i . " day", strtotime('2024-02-01')));
                 $cekHariKerja = JadwalAbsen::where('hari', $getHari)->first();
            foreach ($pegawai as $key => $value) {
        
                if($cekHariKerja->status==1){
                    if(Kehadiran::where('pegawai_id', $value->id)->first()){
                        if(!Kehadiran::where('pegawai_id', $value->id)->whereNotNull('jenis_izin_id')->where('tanggal', $getTgl)->first()){
                            $data[] = [
                                "id" => Str::uuid(),
                                "pegawai_id" => $value->id,
                                'jenis' => 0,
                                "tanggal" => $getTgl,
                                'jenis_izin_id' => null,
                                "keterangan" => 'Posting Ulang Karena Terhapus',
                                "jam" => JadwalAbsen::where('hari', $getHari)->first()->jam_masuk,
                                 "user" => Auth::user()->name,
                                 "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                                'location' => 'By Sistem',
                                'deleted_at' => null
                            ];
                            $data[] = [
                                "id" => Str::uuid(),
                                "pegawai_id" => $value->id,
                                'jenis' => 1,
                                "tanggal" => $getTgl,
                                'jenis_izin_id' => null,
                                "keterangan" => 'Posting Ulang Karena Terhapus',
                                "jam" => JadwalAbsen::where('hari', $getHari)->first()->jam_pulang,
                                 "user" => Auth::user()->name,
                                 "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                                'location' => 'By Sistem',
                                'deleted_at' => null
                            ];
                            $data[] = [
                                "id" => Str::uuid(),
                                "pegawai_id" => $value->id,
                                'jenis' => 2,
                                "tanggal" => $getTgl,
                                'jenis_izin_id' => null,
                                "keterangan" => 'Posting Ulang Karena Terhapus',
                                "jam" => JadwalAbsen::where('hari', $getHari)->first()->jam_keluar_istirahat,
                                 "user" => Auth::user()->name,
                                 "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                                'location' => 'By Sistem',
                                'deleted_at' => null
                            ];
                            $data[] = [
                                "id" => Str::uuid(),
                                "pegawai_id" => $value->id,
                                'jenis' => 3,
                                "tanggal" => $getTgl,
                                'jenis_izin_id' => null,
                                "keterangan" => 'Posting Ulang Karena Terhapus',
                                "jam" => JadwalAbsen::where('hari', $getHari)->first()->jam_masuk_istirahat,
                                 "user" => Auth::user()->name,
                                 "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                                'location' => 'By Sistem',
                                'deleted_at' => null
                            ];
                        }
                    }
                } 
            }
            
        }
        // return $data;
        Kehadiran::insert($data);
return redirect()->route('home');
    }

    public function getNamaHari($hari)
    {
        switch ($hari) {
            case 1:
                return "Senin";
                break;
            case 2:
                return "Selasa";
                break;
            case 3:
                return "Rabu";
                break;
            case 4:
                return "Kamis";
                break;
            case 5:
                return "Jumat";
                break;
            case 6:
                return "Sabtu";
                break;
            default:
                return "Minggu";
        }
    }
}
