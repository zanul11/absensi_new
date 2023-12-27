<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kehadiran;
use App\Models\Location;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
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
            'message' => 'Login Berhasil',
            'data' => $user
        ]);
    }

    public function getRincianAbsen($id)
    {
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
                    "nik" => $absen[0]->pegawai->nip,
                    "nama" => $absen[0]->pegawai->name,
                    "data_absen" => $data,
                    "hari" => 13,
                    "telat" => 0,
                    "masuk" => 3,
                    "tidak_masuk" => [],
                    "tanpaketerangan" => 0
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

    public function insertAbsen($id, $location)
    {
        // return Pegawai::where('id', $id)->first()->name;
        if (date('H') > 6 && date('H') < 10) {
            $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 0)->first();
            if (!$cek_sudah_absen) {
                Kehadiran::create([
                    'pegawai_id' => $id,
                    'tanggal' => date('Y-m-d'),
                    'jenis' => 0,
                    'keterangan' => 'Absen Mobile',
                    'jam' => date('H:i:s'),
                    'location' => $location,
                    'user' => Pegawai::where('id', $id)->first()->name
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'error' => true,
                    'data' => 'Sudah Absen Masuk!',
                ]);
            }
        } else if (date('H') >= 15 && date('H') <= 22) {
            $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 1)->first();
            if (!$cek_sudah_absen) {
                Kehadiran::create([
                    'pegawai_id' => $id,
                    'tanggal' => date('Y-m-d'),
                    'jenis' => 1,
                    'keterangan' => 'Absen Mobile',
                    'jam' => date('H:i:s'),
                    'location' => $location,
                    'user' => Pegawai::where('id', $id)->first()->name
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
        return response()->json([
            'status' => 200,
            'error' => false,
            'data' => 'Absen Berhasil',
        ]);
    }

    public function getHistoriAbsen($id, $tgl1, $tgl2)
    {
        $absen = Absensi::with('jenis_izin')->where('pegawai_id', $id)->whereBetween('tanggal', [$tgl1, $tgl2])->orderBy('tanggal')->get();
        $data = [];
        foreach ($absen as $r) {
            $data[] = [
                'tgl' => $r->tanggal,
                'masuk' => $r->jam_masuk,
                'pulang' => $r->jam_pulang,
                'status' => ($r->is_telat == 1) ? 'Terlambat' : (($r->jam_masuk == null && $r->jenis_izin_id != null) ? 'Izin' : (($r->jenis_izin_id == null && $r->jam_masuk == null && $r->jam_pulang == null && $r->hari != 0) ? 'Tanpa Keterangan' : 'Tepat Waktu')),
                'keterangan' => ($r->is_telat == 1) ? 'Terlambat Absen' : (($r->masuk == null && $r->jenis_izin_id != null) ? $r->jenis_izin->name : 'Tepat Waktu'),

            ];
        }
        return response()->json([
            'status' => 200,
            'error' => false,
            'data' => $data
        ]);
    }
}
