<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalAbsen;
use App\Models\JenisIzin;
use App\Models\Kehadiran;
use App\Models\Location;
use App\Models\Log;
use App\Models\Pegawai;
use App\Models\RequestAbsenPulang;
use App\Models\ShiftPegawai;
use App\Models\TidakMasuk;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        // return $user = Pegawai::where('username', $request->username)->with('lokasi')->first();
        $login = Auth::guard('pegawai')->attempt($request->all());
        if ($login) {
            $user = Pegawai::where('username', $request->username)->with('lokasi')->first();
            return response()->json([
                'response_code' => 200,
                'message' => 'Login Berhasil',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'response_code' => 404,
                'message' => 'Username atau Password Tidak Ditemukan!',
                'data' => null
            ]);
        }
    }

    public function getLokasiUser($id)
    {
        $user = Pegawai::where('id', $id)->with('lokasi')->first();
        return response()->json([
            'response_code' => 200,
            'message' => 'success',
            'data' => $user
        ]);
    }

    public function getJenisIzin()
    {
        return response()->json([
            'response_code' => 200,
            'message' => 'success',
            'data' => JenisIzin::all()
        ]);
    }

    public function getRincianAbsen($id)
    {
        // return date('N');
        $hari = date('N') == 7 ? 0 : date('N');
        $jadwal = JadwalAbsen::where('hari', $hari)->first();
        $pegawai = Pegawai::where('id', $id)->with('lokasi')->first();
        if ($pegawai->is_shift == 0) {
            $keterangan_jam = ($jadwal->status == 0) ? 'Hari Libur' : date('H:i', strtotime($jadwal->jam_masuk)) . ' - ' . date('H:i', strtotime($jadwal->jam_pulang));
            $keterangan_jam_istirahat = ($jadwal->status == 0) ? 'Hari Libur' : date('H:i', strtotime($jadwal->jam_keluar_istirahat)) . ' - ' . date('H:i', strtotime($jadwal->jam_masuk_istirahat));
        } else {
            $jadwal_pegawai = ShiftPegawai::with('shift')->where('pegawai_id', $id)->whereDate('tanggal_mulai', '<=', date('Y-m-d'))->whereDate('tanggal_selesai', '>=', date('Y-m-d'))->first();
            $keterangan_jam = ($jadwal_pegawai) ?  (date('H:i', strtotime($jadwal_pegawai->shift->jam_masuk)) . ' - ' . date('H:i', strtotime($jadwal_pegawai->shift->jam_pulang))) : 'Tidak Ada Shift';
            $keterangan_jam_istirahat = ($jadwal_pegawai) ? (date('H:i', strtotime($jadwal_pegawai->shift->jam_keluar_istirahat)) . ' - ' . date('H:i', strtotime($jadwal_pegawai->shift->jam_masuk_istirahat))) : '';
        }
        $absen = Kehadiran::with('pegawai')->where('pegawai_id', $id)->whereDate('tanggal', date('Y-m-d'))->get();
        $data = [];
        foreach ($absen as $r) {
            $data[] = [
                'kehadiran_id' => $r->id,
                'pegawai_id' => $r->pegawai_id,
                'kehadiran_tgl' => $r->tanggal . ' ' . $r->jam,
                'kehadiran_kode' => (int)$r->jenis,
                'kehadiran_status' => ($r->location) ? 'Aplikasi' : 'Mesin',
                'kehadiran_lokasi' => ($r->location) ? $r->location : '',
            ];
        }
        return response()->json([
            'status' => 200,
            'error' => false,
            'data' => [
                [
                    "id" => $id,
                    "nik" => $pegawai->nip,
                    "nama" => $pegawai->name,
                    'jadwal' => $keterangan_jam,
                    'istirahat' => $keterangan_jam_istirahat,
                    "data_absen" => $data,
                    "hari" => Absensi::where('pegawai_id', $id)->where('hari', true)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count(),
                    "telat" => Absensi::where('pegawai_id', $id)->where('hari', true)->where('is_telat', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count(),
                    "masuk" => Absensi::where('pegawai_id', $id)->where('hari', true)->where('status', true)->where('keterangan', '!=', 'Tidak Absen')->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count(),
                    "tidak_masuk" => Absensi::where('pegawai_id', $id)->where('hari', true)->where('status', true)->whereNotNull('jenis_izin_id',)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count(),
                    "tanpaketerangan" => Absensi::where('pegawai_id', $id)->where('keterangan', 'Tidak Absen')->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count()
                ]
            ]
        ]);
    }

    public function getLokasi()
    {
        $data = Location::all();
        return response()->json([
            'response_code' => 200,
            'message' => 'Success',
            'data' => $data
        ]);
    }

    public function getAbsenPegawai($id)
    {
        $user = Kehadiran::where('pegawai_id', $id)->whereDate('tanggal', date('Y-m-d'))->get();
        return response()->json([
            'response_code' => 200,
            'message' => 'Success',
            'data' => (count($user) > 0) ? $user : null
        ]);
    }

    public function insertAbsen(Request $request, $id, $location)
    {
        // return $location;
        try {
            $pegawai = Pegawai::with('lokasi')->where('id', $id)->first();
            if ($pegawai->is_shift == 0) {
                //JIKA PEGAWAI NORMAL
                $jadwal = JadwalAbsen::where('hari', date('N'))->first();
                if ($jadwal->status == 0) {
                    return response()->json([
                        'status' => 200,
                        'error' => true,
                        'data' => 'Hari Libur, Tidak Bisa Absen',
                    ]);
                }
                if ($pegawai->lokasi->name != $location) {
                    return response()->json([
                        'status' => 200,
                        'error' => true,
                        'data' => 'Lokasi Tidak Sesuai, Tidak Bisa Absen',
                    ]);
                }
                if (date('H') > 6 && date('H:i') < date('H:i', strtotime($jadwal->jam_keluar_istirahat))) {
                    //absen masuk
                    $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 0)->first();
                    if (!$cek_sudah_absen) {
                        $hadir = Kehadiran::create([
                            'pegawai_id' => $id,
                            'tanggal' => date('Y-m-d'),
                            'jenis' => 0,
                            'keterangan' => 'Absen Mobile',
                            'jam' => date('H:i:s'),
                            'location' => $location,
                            'user' => Pegawai::where('id', $id)->first()->name
                        ]);
                        if ($request->hasFile('file')) {
                            // $hadir->getFirstMedia('absen')?->delete();
                            $hadir
                                ->addMediaFromRequest('file')
                                ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                ->toMediaCollection('absen');
                        }
                        return response()->json([
                            'status' => 200,
                            'error' => false,
                            'data' => 'Absen Masuk Berhasil',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'error' => true,
                            'data' => 'Sudah Absen Masuk!',
                        ]);
                    }
                } else if (date('H:i') >= date('H:i', strtotime($jadwal->jam_keluar_istirahat)) && date('H:i') < date('H:i', strtotime('-30 minutes', strtotime($jadwal->jam_masuk_istirahat)))) {
                    //absen keluar istirahat
                    $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 2)->first();
                    if (!$cek_sudah_absen) {
                        $hadir =  Kehadiran::create([
                            'pegawai_id' => $id,
                            'tanggal' => date('Y-m-d'),
                            'jenis' => 2,
                            'keterangan' => 'Absen Mobile',
                            'jam' => date('H:i:s'),
                            'location' => $location,
                            'user' => Pegawai::where('id', $id)->first()->name
                        ]);
                        if ($request->hasFile('file')) {
                            // $hadir->getFirstMedia('absen')?->delete();
                            $hadir
                                ->addMediaFromRequest('file')
                                ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                ->toMediaCollection('absen');
                        }
                        return response()->json([
                            'status' => 200,
                            'error' => false,
                            'data' => 'Absen Keluar Berhasil!',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'error' => true,
                            'data' => 'Sudah Absen Keluar!',
                        ]);
                    }
                } else if (date('H:i') >= date('H:i', strtotime('-30 minutes', strtotime($jadwal->jam_masuk_istirahat))) && date('H:i') <= date('H:i', strtotime($jadwal->jam_masuk_istirahat))) {
                    //absen masuk istirahat
                    $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 3)->first();
                    if (!$cek_sudah_absen) {
                        $hadir =  Kehadiran::create([
                            'pegawai_id' => $id,
                            'tanggal' => date('Y-m-d'),
                            'jenis' => 3,
                            'keterangan' => 'Absen Mobile',
                            'jam' => date('H:i:s'),
                            'location' => $location,
                            'user' => Pegawai::where('id', $id)->first()->name
                        ]);
                        if ($request->hasFile('file')) {
                            // $hadir->getFirstMedia('absen')?->delete();
                            $hadir
                                ->addMediaFromRequest('file')
                                ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                ->toMediaCollection('absen');
                        }
                        return response()->json([
                            'status' => 200,
                            'error' => false,
                            'data' => 'Absen Kembali Berhasil!',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'error' => true,
                            'data' => 'Sudah Absen Kembali!',
                        ]);
                    }
                } else if (date('H:i') > date('H:i', strtotime($jadwal->jam_masuk_istirahat)) && date('H') < 15) {
                    return response()->json([
                        'status' => 200,
                        'error' => true,
                        'data' => 'Belum waktunya absen pulang!',
                    ]);
                } else if (date('H') >= 15 && date('H') <= 23) {
                    //absen pulang
                    $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 1)->first();
                    if (!$cek_sudah_absen) {
                        $hadir = Kehadiran::create([
                            'pegawai_id' => $id,
                            'tanggal' => date('Y-m-d'),
                            'jenis' => 1,
                            'keterangan' => 'Absen Mobile',
                            'jam' => date('H:i:s'),
                            'location' => $location,
                            'user' => Pegawai::where('id', $id)->first()->name
                        ]);
                        if ($request->hasFile('file')) {
                            // $hadir->getFirstMedia('absen')?->delete();
                            $hadir
                                ->addMediaFromRequest('file')
                                ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                ->toMediaCollection('absen');
                        }
                        return response()->json([
                            'status' => 200,
                            'error' => false,
                            'data' => 'Absen Pulang Berhasil',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'error' => true,
                            'data' => 'Sudah Absen Pulang!',
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 200,
                        'error' => true,
                        'data' => 'Di luar jam absen!',
                    ]);
                }
            } else {
                //JIKA PEGAWAI SHIFT
                $shift_pegawai = ShiftPegawai::with('shift')->where('pegawai_id', $id)->whereDate('tanggal_mulai', '<=', date('Y-m-d'))->whereDate('tanggal_selesai', '>=', date('Y-m-d'))->first();
                if (!$shift_pegawai) {
                    return response()->json([
                        'status' => 200,
                        'error' => true,
                        'data' => 'Jadwal Shift Tidak Ditemukan',
                    ]);
                } else {
                    if (date('H') > date('H', strtotime($shift_pegawai->shift->jam_masuk . '-1 hour')) && date('H:i') < date('H:i', strtotime($shift_pegawai->shift->jam_keluar_istirahat))) {
                        //absen masuk
                        $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 0)->first();
                        if (!$cek_sudah_absen) {
                            $hadir = Kehadiran::create([
                                'pegawai_id' => $id,
                                'tanggal' => date('Y-m-d'),
                                'jenis' => 0,
                                'keterangan' => 'Absen Mobile',
                                'jam' => date('H:i:s'),
                                'location' => $location,
                                'user' => Pegawai::where('id', $id)->first()->name
                            ]);
                            if ($request->hasFile('file')) {
                                // $hadir->getFirstMedia('absen')?->delete();
                                $hadir
                                    ->addMediaFromRequest('file')
                                    ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                    ->toMediaCollection('absen');
                            }
                            return response()->json([
                                'status' => 200,
                                'error' => false,
                                'data' => 'Absen Masuk Berhasil',
                            ]);
                        } else {
                            return response()->json([
                                'status' => 200,
                                'error' => true,
                                'data' => 'Sudah Absen Masuk!',
                            ]);
                        }
                    } else if (date('H:i') >= date('H:i', strtotime($shift_pegawai->shift->jam_keluar_istirahat)) && date('H:i') < date('H:i', strtotime('-30 minutes', strtotime($shift_pegawai->shift->jam_masuk_istirahat)))) {
                        //absen keluar istirahat
                        $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 2)->first();
                        if (!$cek_sudah_absen) {
                            $hadir =  Kehadiran::create([
                                'pegawai_id' => $id,
                                'tanggal' => date('Y-m-d'),
                                'jenis' => 2,
                                'keterangan' => 'Absen Mobile',
                                'jam' => date('H:i:s'),
                                'location' => $location,
                                'user' => Pegawai::where('id', $id)->first()->name
                            ]);
                            if ($request->hasFile('file')) {
                                // $hadir->getFirstMedia('absen')?->delete();
                                $hadir
                                    ->addMediaFromRequest('file')
                                    ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                    ->toMediaCollection('absen');
                            }
                            return response()->json([
                                'status' => 200,
                                'error' => false,
                                'data' => 'Absen Keluar Berhasil!',
                            ]);
                        } else {
                            return response()->json([
                                'status' => 200,
                                'error' => true,
                                'data' => 'Sudah Absen Keluar!',
                            ]);
                        }
                    } else if (date('H:i') >= date('H:i', strtotime('-30 minutes', strtotime($shift_pegawai->shift->jam_masuk_istirahat))) && date('H:i') <= date('H:i', strtotime($shift_pegawai->shift->jam_masuk_istirahat))) {
                        //absen masuk istirahat
                        $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 3)->first();
                        if (!$cek_sudah_absen) {
                            $hadir =  Kehadiran::create([
                                'pegawai_id' => $id,
                                'tanggal' => date('Y-m-d'),
                                'jenis' => 3,
                                'keterangan' => 'Absen Mobile',
                                'jam' => date('H:i:s'),
                                'location' => $location,
                                'user' => Pegawai::where('id', $id)->first()->name
                            ]);
                            if ($request->hasFile('file')) {
                                // $hadir->getFirstMedia('absen')?->delete();
                                $hadir
                                    ->addMediaFromRequest('file')
                                    ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                    ->toMediaCollection('absen');
                            }
                            return response()->json([
                                'status' => 200,
                                'error' => false,
                                'data' => 'Absen Kembali Berhasil!',
                            ]);
                        } else {
                            return response()->json([
                                'status' => 200,
                                'error' => true,
                                'data' => 'Sudah Absen Kembali!',
                            ]);
                        }
                    } else if (date('H:i') > date('H:i', strtotime($shift_pegawai->shift->jam_masuk_istirahat)) && date('H') < date('H', strtotime($shift_pegawai->shift->jam_pulang . '-1 hour'))) {
                        return response()->json([
                            'status' => 200,
                            'error' => true,
                            'data' => 'Belum waktunya absen pulang!',
                        ]);
                    } else if (date('H') >= date('H', strtotime($shift_pegawai->shift->jam_pulang . '-1 hour')) && date('yyyy-mm-dd H') <= date('yyyy-mm-dd H', strtotime($shift_pegawai->shift->jam_pulang . '+3 hour'))) {
                        //absen pulang
                        $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 1)->first();
                        if (!$cek_sudah_absen) {
                            $hadir = Kehadiran::create([
                                'pegawai_id' => $id,
                                'tanggal' => date('Y-m-d'),
                                'jenis' => 1,
                                'keterangan' => 'Absen Mobile',
                                'jam' => date('H:i:s'),
                                'location' => $location,
                                'user' => Pegawai::where('id', $id)->first()->name
                            ]);
                            if ($request->hasFile('file')) {
                                // $hadir->getFirstMedia('absen')?->delete();
                                $hadir
                                    ->addMediaFromRequest('file')
                                    ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                                    ->toMediaCollection('absen');
                            }
                            return response()->json([
                                'status' => 200,
                                'error' => false,
                                'data' => 'Absen Pulang Berhasil',
                            ]);
                        } else {
                            return response()->json([
                                'status' => 200,
                                'error' => true,
                                'data' => 'Sudah Absen Pulang!',
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => 200,
                            'error' => true,
                            'data' => 'Di luar shift / jam absen!',
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'error' => true,
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function getHistoriAbsen($id, $tgl1, $tgl2)
    {
        $absen = Absensi::with('jenis_izin')->where('pegawai_id', $id)->whereBetween('tanggal', [$tgl1, $tgl2])->orderBy('tanggal')->get();
        $data = [];
        $peg = Pegawai::where('id', $id)->first();
        $jadwal = JadwalAbsen::where('hari', date('N'))->first();

        foreach ($absen as $r) {
            $jadwal_pegawai_shift = null;
            if ($peg->is_shift == 1) {
                $jadwal_pegawai_shift = ShiftPegawai::with('shift')->where('pegawai_id', $peg->id)->whereDate('tanggal_mulai', '<=', $r->tanggal)->whereDate('tanggal_selesai', '>=', $r->tanggal)->first();
                if (!$jadwal_pegawai_shift) {
                    alert()->warning('Warning', 'Pegawai ' . $peg->name . ' Tidak Memiliki Shift !!');
                }
            }
            $jam_kerja = '-';
            $diff_mins = 0;
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

            $data[] = [
                'tgl' => $r->tanggal,
                'masuk' => $r->jam_masuk ?? $r->jam_keluar_istirahat ?? $r->jam_masuk_istirahat ?? JadwalAbsen::where('hari', date('N'))->first()->jam_masuk_istirahat,
                'pulang' => $r->jam_pulang,
                'status' => ($r->is_telat == 1) ? 'Terlambat' : (($r->jenis_izin_id != null) ? 'Izin' : (($r->jenis_izin_id == null && $r->jam_masuk == null && $r->jam_pulang == null && $r->hari != 0) ? 'Tanpa Keterangan' : (($r->hari == 0 && $r->status == 1) ? 'Hari Libur' : 'Tepat Waktu'))),
                'keterangan' => ($r->is_telat == 1) ? 'Terlambat Absen' : (($r->masuk == null && $r->jenis_izin_id != null) ? $r->jenis_izin->name : (($r->hari == 0 && $r->status == 1) ? $r->keterangan : 'Tepat Waktu')),
                'jam_kerja' => $jam_kerja,
            ];
        }
        return response()->json([
            'status' => 200,
            'error' => false,
            'data' => $data
        ]);
    }

    public function getRequestAbsenPulang($id)
    {
        return response()->json([
            'response_code' => 200,
            'message' => 'Success',
            'data' => RequestAbsenPulang::with('media')->where('pegawai_id', $id)->orderBy('tanggal', 'desc')->get()
        ]);
    }


    public function insertAbsenPulang(Request $request, $id)
    {
        // return $request;
        $cek_sudah_absen = RequestAbsenPulang::whereDate('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', $request->jenis)->first();
        if (!$cek_sudah_absen) {
            $hadir =  RequestAbsenPulang::create([
                'pegawai_id' => $id,
                'tanggal' => date('Y-m-d H:i:s'),
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
                'user' => $id
            ]);
            if ($request->hasFile('file')) {
                $hadir->getFirstMedia('absen_pulang')?->delete();
                $hadir
                    ->addMediaFromRequest('file')
                    ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                    ->toMediaCollection('absen_pulang');
            }
            return response()->json([
                'status' => 200,
                'error' => false,
                'data' => 'Berhasil Request Absen ' .  (($request->jenis == 1) ? 'Pulang' : ($request->jenis == 2 ? 'Keluar' : (($request->jenis == 0 ? 'Masuk' : 'Kembali')))),
            ]);
        } else {
            // return 'a';
            return response()->json([
                'status' => 200,
                'error' => true,
                'data' => 'Sudah Request Absen ' .  (($request->jenis == 1) ? 'Pulang' : ($request->jenis == 2 ? 'Keluar' : (($request->jenis == 0 ? 'Masuk' : 'Kembali')))),
            ]);
        }
    }

    public function insertAbsenPulangWithLokasi(Request $request, $id)
    {
        // return $request;
        $cek_sudah_absen = RequestAbsenPulang::whereDate('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', $request->jenis)->first();
        if (!$cek_sudah_absen) {
            $hadir =  RequestAbsenPulang::create([
                'pegawai_id' => $id,
                'tanggal' => date('Y-m-d H:i:s'),
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
                'lokasi' => $request->lokasi,
                'user' => $id
            ]);
            if ($request->hasFile('file')) {
                $hadir->getFirstMedia('absen_pulang')?->delete();
                $hadir
                    ->addMediaFromRequest('file')
                    ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                    ->toMediaCollection('absen_pulang');
            }
            return response()->json([
                'status' => 200,
                'error' => false,
                'data' => 'Berhasil Request Absen ' .  (($request->jenis == 1) ? 'Pulang' : ($request->jenis == 2 ? 'Keluar' : (($request->jenis == 0 ? 'Masuk' : 'Kembali')))),
            ]);
        } else {
            // return 'a';
            return response()->json([
                'status' => 200,
                'error' => true,
                'data' => 'Sudah Request Absen ' .  (($request->jenis == 1) ? 'Pulang' : ($request->jenis == 2 ? 'Keluar' : (($request->jenis == 0 ? 'Masuk' : 'Kembali')))),
            ]);
        }
    }


    public function getTidakMasuk($id)
    {
        return response()->json([
            'response_code' => 200,
            'message' => 'Success',
            'data' => TidakMasuk::with('media')->withWhereHas('jenis_izin', function ($q) {
                return $q->where('name', "!=", 'Cuti');
            })->where('pegawai_id', $id)->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function getCuti($id)
    {
        return response()->json([
            'response_code' => 200,
            'message' => 'Success',
            'data' => TidakMasuk::with('media')->withWhereHas('jenis_izin', function ($q) {
                return $q->where('name', 'Cuti');
            })->where('pegawai_id', $id)->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function insertTidakMasuk(Request $request, $id)
    {
        $cek_sudah_absen = TidakMasuk::whereDate('tanggal_mulai', '>=', $request->tanggal_mulai)->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai)->where('pegawai_id', $id)->where('jenis_izin_id', $request->jenis_izin_id)->first();
        if (!$cek_sudah_absen) {
            $hadir =  TidakMasuk::create([
                'pegawai_id' => $id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jenis_izin_id' => $request->jenis_izin_id,
                'keterangan' => $request->keterangan,
                'user' => $id
            ]);
            if ($request->hasFile('file')) {
                $hadir->getFirstMedia('tidak_masuk')?->delete();
                $hadir
                    ->addMediaFromRequest('file')
                    ->usingFileName($hadir->id  . "." . $request->file('file')->extension())
                    ->toMediaCollection('tidak_masuk');
            }
            return response()->json([
                'status' => 200,
                'error' => false,
                'data' => 'Request Absen Tidak Masuk Berhasil!',
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'error' => true,
                'data' => 'Sudah Request Tidak Masuk',
            ]);
        }
    }


    public function insertLog(Request $request)
    {
        Log::create([
            'user' => $request->user,
            'action' => $request->action,
            'log' => $request->log,
        ]);

        return response()->json([
            'status' => 200,
            'error' => false,
            'data' => 'Berhasil',
        ]);
    }
}
