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

class RincianAbsenController extends Controller
{
    protected   $data = [
        'category_name' => 'absensi',
        'page_name' => 'rincian_absen',
    ];

    public function index()
    {
        $jenis_izin = JenisIzin::orderBy('hak', 'asc')->get();
        $data = [];

        if (isset(request()->tanggal)) {
            $tanggal = (explode("to", str_replace(' ', '', request()->tanggal)));
            Cache::put('dTgl', $tanggal[0]);
            Cache::put('sTgl', $tanggal[1] ?? $tanggal[0]);
            $from = date('Y-m-d', strtotime((Cache::has('dTgl')) ? Cache::get('dTgl') : date('Y-m-01')));
            $to = date('Y-m-d', strtotime((Cache::has('sTgl')) ? Cache::get('sTgl') : date('Y-m-d')));
            $pegawai = Pegawai::orderby('name')->get();
            $data_absen = [];
            $hitung_absen = true;
            foreach ($pegawai as $peg) {
                $data_absen = [];
                $absen = Absensi::with('jenis_izin')->where('pegawai_id', $peg->id)->whereBetween('tanggal', [$from, $to])->orderBy('tanggal')->get();
                foreach ($absen as $r) {
                    $hitung_absen = true;
                    $jam_kerja = '0 Jam 0 Menit';
                    $diff_mins = 0;
                    $assigned_time = null;
                    $completed_time = null;
                    $jadwal = JadwalAbsen::where('hari', date('N'))->first();
                    $jadwal_pegawai_shift = null;
                    if ($peg->is_shift == 1) {
                        $jadwal_pegawai_shift = ShiftPegawai::with('shift')->where('pegawai_id', $peg->id)->whereDate('tanggal_mulai', '<=', $r->tanggal)->whereDate('tanggal_selesai', '>=', $r->tanggal)->first();
                        if (!$jadwal_pegawai_shift) {
                            $hitung_absen = false;
                            // alert()->warning('Warning', 'Pegawai ' . $peg->name . ' Tidak Memiliki Shift !!');
                        }
                    }
                    if ($hitung_absen) {
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
                            $diff_mins = floor(abs($d1->getTimestamp() - $d2->getTimestamp()) / 60);
                        }
                        // return JadwalAbsen::where('hari', date('N'))->first()->jam_masuk_istirahat;
                        if ($peg->is_shift == 0)
                            $start_datetime = new DateTime(date('Y-m-d') . ' ' . $jadwal->jam_masuk_toleransi);
                        else
                            $start_datetime = new DateTime(date('Y-m-d') . ' ' . $jadwal_pegawai_shift->shift->jam_masuk);
                        $end_datetime = new DateTime(date('Y-m-d') . ' ' . $assigned_time);

                        // echo ($start_datetime->diff($end_datetime));
                        $data_absen[] = [
                            'tgl' => $r->tanggal,
                            'jam_masuk' => ($peg->is_shift == 0) ? $jadwal->jam_masuk_toleransi : $jadwal_pegawai_shift->shift->jam_masuk,
                            'hari' => $r->hari,
                            'masuk' => $r->jam_masuk,
                            'pulang' => $r->jam_pulang,
                            'keluar' => $r->jam_keluar_istirahat ?? $r->jam_pulang_istirahat,
                            'kembali' => $r->jam_masuk_istirahat,
                            'status' => ($r->is_telat == 1) ? 'Terlambat' : (($r->jenis_izin_id != null) ? 'Izin' : (($r->jenis_izin_id == null && $r->jam_masuk == null && $r->jam_pulang == null && $r->hari != 0) ? 'Tanpa Keterangan' : (($r->hari == 0 && $r->status == 1) ? 'Hari Libur' : 'Tepat Waktu'))),
                            'keterangan' => ($r->is_telat == 1 && $r->keterangan != 'Tidak Absen') ? 'Terlambat Absen' : (($r->masuk == null && $r->jenis_izin_id != null) ? $r->jenis_izin->name : (($r->hari == 0 && $r->status == 1) ? $r->keterangan : (($r->keterangan != 'Tidak Absen') ? 'Tepat Waktu' : $r->keterangan))),
                            'd1' => $assigned_time,
                            'd2' => $completed_time,
                            'telat' => ($start_datetime->diff($end_datetime))->format('%H:%i'),
                            'jam_kerja' => $jam_kerja,
                            'menit' => $diff_mins
                        ];
                    }
                }

                $data[] = [
                    'id' => $peg->id,
                    'nip' => $peg->nip,
                    'name' => $peg->name,
                    'data' => $data_absen
                ];
            }
            $data = collect($data);
        } else {
            Cache::put('dTgl', date('01-m-Y'));
            Cache::put('sTgl', date('d-m-Y'));
        }


        return view('pages.absensi.rincian_absen.index', compact('data', 'jenis_izin'))->with($this->data);
    }
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
        $from = date('Y-m-d', strtotime((Cache::has('dTgl')) ? Cache::get('dTgl') : date('Y-m-01')));
        $to = date('Y-m-d', strtotime((Cache::has('sTgl')) ? Cache::get('sTgl') : date('Y-m-d')));
        $pegawai = Pegawai::where('status_pegawai', 1)->orderby('name')->get();
        $jenis_izin = JenisIzin::orderBy('hak', 'asc')->get();
        $data_absen = [];
        $data = [];
        $hitung_absen = true;
        foreach ($pegawai as $peg) {
            $data_absen = [];
            $absen = Absensi::with('jenis_izin')->where('pegawai_id', $peg->id)->whereBetween('tanggal', [$from, $to])->orderBy('tanggal')->get();
            foreach ($absen as $r) {
                $hitung_absen = true;
                $jam_kerja = '0 Jam 0 Menit';
                $diff_mins = 0;
                $assigned_time = null;
                $completed_time = null;
                $jadwal = JadwalAbsen::where('hari', date('N'))->first();
                $jadwal_pegawai_shift = null;
                if ($peg->is_shift == 1) {
                    $jadwal_pegawai_shift = ShiftPegawai::with('shift')->where('pegawai_id', $peg->id)->whereDate('tanggal_mulai', '<=', $r->tanggal)->whereDate('tanggal_selesai', '>=', $r->tanggal)->first();
                    if (!$jadwal_pegawai_shift) {
                        $hitung_absen = false;
                        // alert()->warning('Warning', 'Pegawai ' . $peg->name . ' Tidak Memiliki Shift !!');
                    }
                }
                if ($hitung_absen) {
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
                        $diff_mins = floor(abs($d1->getTimestamp() - $d2->getTimestamp()) / 60);
                    }
                    // return JadwalAbsen::where('hari', date('N'))->first()->jam_masuk_istirahat;
                    if ($peg->is_shift == 0)
                        $start_datetime = new DateTime(date('Y-m-d') . ' ' . $jadwal->jam_masuk_toleransi);
                    else
                        $start_datetime = new DateTime(date('Y-m-d') . ' ' . $jadwal_pegawai_shift->shift->jam_masuk);
                    $end_datetime = new DateTime(date('Y-m-d') . ' ' . $assigned_time);

                    // echo ($start_datetime->diff($end_datetime));
                    $data_absen[] = [
                        'tgl' => $r->tanggal,
                        'jam_masuk' => ($peg->is_shift == 0) ? $jadwal->jam_masuk_toleransi : $jadwal_pegawai_shift->shift->jam_masuk,
                        'hari' => $r->hari,
                        'masuk' => $r->jam_masuk,
                        'pulang' => $r->jam_pulang,
                        'keluar' => $r->jam_keluar_istirahat ?? $r->jam_pulang_istirahat,
                        'kembali' => $r->jam_masuk_istirahat,
                        'status' => ($r->is_telat == 1) ? 'Terlambat' : (($r->jenis_izin_id != null) ? 'Izin' : (($r->jenis_izin_id == null && $r->jam_masuk == null && $r->jam_pulang == null && $r->hari != 0) ? 'Tanpa Keterangan' : (($r->hari == 0 && $r->status == 1) ? 'Hari Libur' : 'Tepat Waktu'))),
                        'keterangan' => ($r->is_telat == 1 && $r->keterangan != 'Tidak Absen') ? 'Terlambat Absen' : (($r->masuk == null && $r->jenis_izin_id != null) ? $r->jenis_izin->name : (($r->hari == 0 && $r->status == 1) ? $r->keterangan : (($r->keterangan != 'Tidak Absen') ? 'Tepat Waktu' : $r->keterangan))),
                        'd1' => $assigned_time,
                        'd2' => $completed_time,
                        'telat' => ($start_datetime->diff($end_datetime))->format('%H:%i'),
                        'jam_kerja' => $jam_kerja,
                        'menit' => $diff_mins
                    ];
                }
            }

            $data[] = [
                'id' => $peg->id,
                'nip' => $peg->nip,
                'name' => $peg->name,
                'data' => $data_absen
            ];
        }


        return view('pages.absensi.rincian_absen.cetak', compact('data'))->with($this->data);
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
