<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalAbsen;
use App\Models\JenisIzin;
use App\Models\Pegawai;
use App\Models\ShiftPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use DateTime;

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
        $pegawai = Pegawai::where('status_pegawai', 1)->orderby('name')->get();
        $jenis_izin = JenisIzin::orderBy('hak', 'asc')->get();
        $data_absen = [];
        foreach ($pegawai as $peg) {
            $data['nip'] = $peg->nip;
            $data['nama'] = $peg->name;
            $data['jam_kerja'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->whereBetween('tanggal', [$from, $to])->count();
            $data['kehadiran'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $data['telat'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->where('is_telat', true)->where('keterangan', '!=', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $data['tanpa_keterangan'] = Absensi::where('pegawai_id', $peg->id)->where('keterangan', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $tidak_masuk = 0;
            foreach ($jenis_izin as $izin) {
                $getDataIzin = Absensi::where('pegawai_id', $peg->id)->where('jenis_izin_id', $izin->id)->whereBetween('tanggal', [$from, $to])->count();
                //hitung tidak masuk hak =0
                if ($izin->hak == 0)
                    $tidak_masuk += $getDataIzin;
                $data[strtolower(str_replace(' ', '_', $izin->name))] = $getDataIzin;
            }
            $data['tidak_masuk'] = $tidak_masuk + $data['tanpa_keterangan'];
            $absen = Absensi::with('jenis_izin')->where('hari', true)->whereNull('jenis_izin_id')->where('pegawai_id', $peg->id)->whereBetween('tanggal', [$from, $to])->orderBy('tanggal')->get();
            $diff_mins = 0;

            foreach ($absen as $r) {
                $jadwal = JadwalAbsen::where('hari', date('N'))->first();
                $jadwal_pegawai_shift = null;
                if ($peg->is_shift == 1) {
                    $jadwal_pegawai_shift = ShiftPegawai::with('shift')->where('pegawai_id', $peg->id)->whereDate('tanggal_mulai', '<=', $r->tanggal)->whereDate('tanggal_selesai', '>=', $r->tanggal)->first();
                    if (!$jadwal_pegawai_shift) {
                        alert()->warning('Warning', 'Pegawai ' . $peg->name . ' Tidak Memiliki Shift !!');
                    }
                }
                if ($r->keterangan != 'Tidak Absen') {
                    $jam_masuk = ($peg->is_shift == 1) ? $jadwal_pegawai_shift->shift->jam_masuk : $jadwal->jam_masuk;
                    $jam_pulang = ($peg->is_shift == 1) ? $jadwal_pegawai_shift->shift->jam_pulang : $jadwal->jam_pulang;
                    $assigned_time = $r->jam_masuk ?? $r->jam_keluar_istirahat ?? $r->jam_masuk_istirahat ?? $jam_masuk;
                    $completed_time = ($r->jam_pulang != null) ? ((strtotime($r->jam_pulang) > strtotime($jam_pulang)) ? $jam_pulang : $r->jam_pulang) : $jam_pulang;
                    $d1 = new DateTime($assigned_time);
                    if (date('H', strtotime($jam_masuk)) > 18 || date('H', strtotime($jam_pulang)) == 0) {
                        $d2 = new DateTime($completed_time . ' +1 day');
                    } else {
                        $d2 = new DateTime($completed_time);
                    }
                    $interval = $d2->diff($d1);
                    $jam_kerja = $interval->format('%H Jam %i Menit');
                    $diff_mins += floor(abs($d1->getTimestamp() - $d2->getTimestamp()) / 60);
                }
            }
            $data['total_menit'] = $diff_mins;
            $data['jam'] = floor($diff_mins / 60);
            $data['menit'] = $diff_mins % 60;
            $data_absen[] = $data;
        }
        // return $data_absen;
        return view('pages.absensi.laporan_absen.index', compact('data_absen', 'jenis_izin'))->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $from = date('Y-m-d', strtotime((Cache::has('dTgl')) ? Cache::get('dTgl') : date('Y-m-01')));
        $to = date('Y-m-d', strtotime((Cache::has('sTgl')) ? Cache::get('sTgl') : date('Y-m-d')));
        $pegawai = Pegawai::where('status_pegawai', 1)->orderby('name')->get();
        $jenis_izin = JenisIzin::orderBy('hak', 'asc')->get();
        $data_absen = [];
        foreach ($pegawai as $peg) {
            $data['nip'] = $peg->nip;
            $data['nama'] = $peg->name;
            $data['jam_kerja'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->whereBetween('tanggal', [$from, $to])->count();
            $data['kehadiran'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $data['telat'] = Absensi::where('pegawai_id', $peg->id)->where('hari', true)->where('is_telat', true)->where('keterangan', '!=', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $data['tanpa_keterangan'] = Absensi::where('pegawai_id', $peg->id)->where('keterangan', 'Tidak Absen')->whereBetween('tanggal', [$from, $to])->count();
            $tidak_masuk = 0;
            foreach ($jenis_izin as $izin) {
                $getDataIzin = Absensi::where('pegawai_id', $peg->id)->where('jenis_izin_id', $izin->id)->whereBetween('tanggal', [$from, $to])->count();
                //hitung tidak masuk hak =0
                if ($izin->hak == 0)
                    $tidak_masuk += $getDataIzin;
                $data[strtolower(str_replace(' ', '_', $izin->name))] = $getDataIzin;
            }
            $data['tidak_masuk'] = $tidak_masuk + $data['tanpa_keterangan'];
            $absen = Absensi::with('jenis_izin')->where('hari', true)->whereNull('jenis_izin_id')->where('pegawai_id', $peg->id)->whereBetween('tanggal', [$from, $to])->orderBy('tanggal')->get();
            $diff_mins = 0;
            foreach ($absen as $r) {
                $jadwal = JadwalAbsen::where('hari', date('N'))->first();
                $jadwal_pegawai_shift = null;
                if ($peg->is_shift == 1) {
                    $jadwal_pegawai_shift = ShiftPegawai::with('shift')->where('pegawai_id', $peg->id)->whereDate('tanggal_mulai', '<=', $r->tanggal)->whereDate('tanggal_selesai', '>=', $r->tanggal)->first();
                    if (!$jadwal_pegawai_shift) {
                        alert()->warning('Warning', 'Pegawai ' . $peg->name . ' Tidak Memiliki Shift !!');
                    }
                }

                if ($r->keterangan != 'Tidak Absen') {
                    $jam_masuk = ($peg->is_shift == 1) ? $jadwal_pegawai_shift->shift->jam_masuk : $jadwal->jam_masuk;
                    $jam_pulang = ($peg->is_shift == 1) ? $jadwal_pegawai_shift->shift->jam_pulang : $jadwal->jam_pulang;
                    $assigned_time = $r->jam_masuk ?? $r->jam_keluar_istirahat ?? $r->jam_masuk_istirahat ?? $jam_masuk;
                    $completed_time = ($r->jam_pulang != null) ? ((strtotime($r->jam_pulang) > strtotime($jam_pulang)) ? $jam_pulang : $r->jam_pulang) : $jam_pulang;
                    $d1 = new DateTime($assigned_time);
                    if (date('H', strtotime($jam_masuk)) > 18 || date('H', strtotime($jam_pulang)) == 0) {
                        $d2 = new DateTime($completed_time . ' +1 day');
                    } else {
                        $d2 = new DateTime($completed_time);
                    }
                    $interval = $d2->diff($d1);
                    $jam_kerja = $interval->format('%H Jam %i Menit');
                    $diff_mins += floor(abs($d1->getTimestamp() - $d2->getTimestamp()) / 60);
                }
            }
            $data['total_menit'] = $diff_mins;
            $data['jam'] = floor($diff_mins / 60);
            $data['menit'] = $diff_mins % 60;
            $data_absen[] = $data;
        }

        return view('pages.absensi.laporan_absen.cetak', compact('data_absen', 'jenis_izin'))->with($this->data);
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
