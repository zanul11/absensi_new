<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JenisIzin;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LaporanAbsenController extends Controller
{
    protected   $data = [
        'category_name' => 'absensi',
        'page_name' => 'laporan_absen',
    ];

    public function index()
    {
        if (isset(request()->tanggal)) {
            $tanggal = (explode("to", str_replace(' ', '', request()->tanggal)));
            Cache::put('dTgl', $tanggal[0]);
            Cache::put('sTgl', $tanggal[1] ?? $tanggal[0]);
        } else {
            Cache::put('dTgl', date('01-m-Y'));
            Cache::put('sTgl', date('d-m-Y'));
        }
        $from = date('Y-m-d', strtotime((Cache::has('dTgl')) ? Cache::get('dTgl') : date('Y-m-01')));
        $to = date('Y-m-d', strtotime((Cache::has('sTgl')) ? Cache::get('sTgl') : date('Y-m-d')));
        $pegawai = Pegawai::orderby('name')->get();
        $jenis_izin = JenisIzin::all();

        foreach ($pegawai as $peg) {
            $data['nip'] = $peg->nip;
            $data['nama'] = $peg->name;
            $data['jam_kerja'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->whereBetween('tanggal', [$from, $to])->count();
            $data['kehadiran'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $data['telat'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->where('is_telat', true)->where('keterangan', '!=', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $data['tanpa_keterangan'] = Absensi::where('pegawai_id', $peg->id)->where('keterangan', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();

            foreach ($jenis_izin as $izin) {
                $getDataIzin = Absensi::where('pegawai_id', $peg->id)->where('jenis_izin_id', $izin->id)->whereBetween('tanggal', [$from, $to])->count();
                $data[strtolower(str_replace(' ', '_', $izin->name))] = $getDataIzin;
            }
            $data_absen[] = $data;
        }
        // return $data_absen;
        return view('pages.absensi.laporan_absen.index', compact('data_absen'))->with($this->data);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
