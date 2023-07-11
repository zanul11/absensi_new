<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
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
                'conntent' => $user
            ]);
        } else {
            return response()->json([
                'response_code' => 404,
                'message' => 'Username atau Password Tidak Ditemukan!',
                'conntent' => null
            ]);
        }
    }

    public function getLokasiUser($id)
    {
        $user = Pegawai::where('id', $id)->with('lokasi')->first();
        return response()->json([
            'response_code' => 200,
            'message' => 'Login Berhasil',
            'conntent' => $user
        ]);
    }

    public function getAbsenPegawai($id)
    {
        $user = Kehadiran::where('pegawai_id', $id)->whereDate('tanggal', date('Y-m-d'))->get();
        return response()->json([
            'response_code' => 200,
            'message' => 'Success',
            'conntent' => (count($user) > 0) ? $user : null
        ]);
    }

    public function absenPegawai($id)
    {
        // return date('H');

        if (date('H') > 6 && date('H') < 14) {
            $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 0)->first();
            if (!$cek_sudah_absen) {
                // return 'belum absen';
                Kehadiran::create([
                    'pegawai_id' => $id,
                    'tanggal' => date('Y-m-d'),
                    'jenis' => 0,
                    'keterangan' => 'Absen Mobile',
                    'jam' => date('H:i:s'),
                    'user' => Pegawai::where('id', $id)->first()->name
                ]);
            }
        } else if (date('H') >= 14 && date('H') <= 22) {
            $cek_sudah_absen = Kehadiran::where('tanggal', date('Y-m-d'))->where('pegawai_id', $id)->where('jenis', 1)->first();
            if (!$cek_sudah_absen) {
                // return 'belum absen';
                Kehadiran::create([
                    'pegawai_id' => $id,
                    'tanggal' => date('Y-m-d'),
                    'jenis' => 1,
                    'keterangan' => 'Absen Mobile',
                    'jam' => date('H:i:s'),
                    'user' => Pegawai::where('id', $id)->first()->name
                ]);
            }
        }
        return response()->json([
            'response_code' => 200,
            'message' => 'Absen Berhasil',
            // 'conntent' => $user
        ]);
    }
}
