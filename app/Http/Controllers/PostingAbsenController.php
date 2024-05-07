<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalAbsen;
use App\Models\JenisIzin;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use App\Models\ShiftPegawai;
use App\Models\TglLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostingAbsenController extends Controller
{
    protected   $data = [
        'category_name' => 'absensi',
        'page_name' => 'posting_absen',
    ];

    public function index()
    {
        return view('pages.absensi.posting_absen.index')->with($this->data);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        if ($request->tanggal == null) {
            alert()->warning('Warning !!', 'Harap Memilih Tanggal');
            return redirect()->back()->with('error', 'Harap Memilih Tanggal');
        }
        // return $getHari = date('w', strtotime("+0 day", strtotime('2023-07-02')));

        $tanggal = (explode("to", str_replace(' ', '', $request->tanggal)));
        // return $tanggal[0];

        $startTimeStamp = strtotime($tanggal[0]);
        $endTimeStamp = strtotime($tanggal[1] ?? $tanggal[0]);
        $timeDiff = abs($endTimeStamp - $startTimeStamp);
        $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
        // // and you might want to convert to integer
        $numberDays = intval($numberDays);


        $from = date('Y-m-d', $startTimeStamp);
        $to = date('Y-m-d', $endTimeStamp);
        Absensi::whereBetween('tanggal', [$from, $to])->delete();
        for ($i = 0; $i <= $numberDays; $i++) {
            $getHari = date('w', strtotime("+" . $i . " day", strtotime($tanggal[0])));
            $getTgl = date('Y-m-d', strtotime("+" . $i . " day", strtotime($tanggal[0])));
            $cekHariKerja = JadwalAbsen::where('hari', $getHari)->first();
            $cekHariLibur = TglLibur::whereDate('tgl_libur', $getTgl)->first();
            $pegawai = Pegawai::all();
            foreach ($pegawai as $peg) {
                if ($peg->is_shift == 0) {
                    //Jika Pegawai Normal
                    if ($cekHariKerja->status == 0) {
                        //jika hari libur
                        $data[] = [
                            "id" => Str::uuid(),
                            "pegawai_id" => $peg->id,
                            "tanggal" => $getTgl,
                            "hari" => 0,
                            "status" => 0,
                            "keterangan" => $this->getNamaHari($getHari),
                            "jenis_izin_id" => null,
                            "jam_masuk" => null,
                            "is_telat" => 0,
                            "jam_pulang" => null,
                            "is_pulang_cepat" => 0,
                            "user" => Auth::user()->name,
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                            "jam_keluar_istirahat" => null,
                            "jam_masuk_istirahat" => null,
                            "is_telat_kembali" => 0,
                            "jadwal_masuk" => null,
                            "jadwal_pulang" => null,
                        ];
                    } else {
                        //jika hari kerja masuk status=1
                        if ($cekHariLibur) {
                            //jika hari kerja adalah hari libur
                            //hari 0 dan status 1 adalah hari libur
                            $data[] = [
                                "id" => Str::uuid(),
                                "pegawai_id" => $peg->id,
                                "tanggal" => $getTgl,
                                "hari" => 0,
                                "status" => 1,
                                "keterangan" => $cekHariLibur->keterangan,
                                "jenis_izin_id" => null,
                                "jam_masuk" => null,
                                "is_telat" => 0,
                                "jam_pulang" => null,
                                "is_pulang_cepat" => 0,
                                "user" => Auth::user()->name,
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                                "jam_keluar_istirahat" => null,
                                "jam_masuk_istirahat" => null,
                                "is_telat_kembali" => 0,
                                "jadwal_masuk" => null,
                                "jadwal_pulang" => null,
                            ];
                        } else {
                            Kehadiran::whereNotNull('deleted_at')
                                ->where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->withTrashed()->update([
                                    'deleted_at' => null
                                ]);
                            //jika hari kerja normal
                            $getAbsenMasuk = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 0)->orderBy('jam', 'asc')->first();
                            //absen masuk jenis=0 atau masuk
                            if ($getAbsenMasuk) {
                                //jika ada absen
                                if (isset($getAbsenMasuk->jenis_izin_id)) {
                                    //jika izin dihari kerja
                                    $izin = JenisIzin::where('id', $getAbsenMasuk->jenis_izin_id)->first();
                                    $data[] = [
                                        "id" => Str::uuid(),
                                        "pegawai_id" => $peg->id,
                                        "tanggal" => $getTgl,
                                        "hari" => 1,
                                        "status" => $izin->hak,
                                        "keterangan" => $getAbsenMasuk->keterangan,
                                        "jenis_izin_id" => $izin->id,
                                        "jam_masuk" => null,
                                        "is_telat" => 0,
                                        "jam_pulang" => null,
                                        "is_pulang_cepat" => 0,
                                        "user" => Auth::user()->name,
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                        "jam_keluar_istirahat" => null,
                                        "jam_masuk_istirahat" => null,
                                        "is_telat_kembali" => 0,
                                        "jadwal_masuk" => null,
                                        "jadwal_pulang" => null,
                                    ];
                                } else {
                                    //jika masuk
                                    $getAbsenPulang = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 1)->orderBy('jam', 'desc')->first();
                                    $getAbsenKeluar = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 2)->first();
                                    $getAbsenKembali = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 3)->first();
                                    $data[] = [
                                        "id" => Str::uuid(),
                                        "pegawai_id" => $peg->id,
                                        "tanggal" => $getTgl,
                                        "hari" => 1,
                                        "status" => 1,
                                        "keterangan" => $getAbsenMasuk->keterangan,
                                        "jenis_izin_id" => null,
                                        "jam_masuk" => $getAbsenMasuk->jam ?? null,
                                        "is_telat" => (isset($getAbsenMasuk->jam)) ? ((strtotime($getAbsenMasuk->jam) <= strtotime($cekHariKerja->jam_masuk_toleransi)) ? 0 : 1) : 1,
                                        "jam_pulang" => $getAbsenPulang->jam ?? null,
                                        "is_pulang_cepat" => (isset($getAbsenPulang->jam)) ? ((strtotime($cekHariKerja->jam_pulang_toleransi) > strtotime($getAbsenPulang->jam)) ? 1 : 0) : 0,
                                        "user" => Auth::user()->name,
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                        "jam_keluar_istirahat" => $getAbsenKeluar->jam ?? null,
                                        "jam_masuk_istirahat" => $getAbsenKembali->jam ?? null,
                                        "is_telat_kembali" => (isset($getAbsenKembali->jam)) ? ((strtotime($cekHariKerja->jam_masuk_istirahat) < strtotime($getAbsenKembali->jam)) ? 1 : 0) : 1, //terhitung telat kembali jika tidak absen
                                        "jadwal_masuk" => $cekHariKerja->jam_masuk_toleransi,
                                        "jadwal_pulang" => $cekHariKerja->jam_pulang_toleransi,
                                    ];
                                }
                            } else {
                                //tidak absen
                                $getAbsenPulang = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 1)->orderBy('tanggal', 'desc')->first();
                                $getAbsenKeluar = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 2)->first();
                                $getAbsenKembali = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 3)->first();
                                if ($getAbsenPulang) {
                                    //tidak absen masuk tapi absen pulang
                                    // return $cekHariKerja->jam_pulang_toleransi;
                                    $data[] = [
                                        "id" => Str::uuid(),
                                        "pegawai_id" => $peg->id,
                                        "tanggal" => $getTgl,
                                        "hari" => 1,
                                        "status" => 1,
                                        "keterangan" => 'Tidak Absen Masuk',
                                        "jenis_izin_id" => null,
                                        "jam_masuk" => null,
                                        "is_telat" => 1,
                                        "jam_pulang" => $getAbsenPulang->jam ?? null,
                                        "is_pulang_cepat" => (isset($getAbsenPulang->jam)) ? ((strtotime($cekHariKerja->jam_pulang_toleransi) > strtotime($getAbsenPulang->jam)) ? 1 : 0) : 0,
                                        "user" => Auth::user()->name,
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                        "jam_keluar_istirahat" => $getAbsenKeluar->jam ?? null,
                                        "jam_masuk_istirahat" => $getAbsenKembali->jam ?? null,
                                        "is_telat_kembali" => (isset($getAbsenKembali->jam)) ? ((strtotime($cekHariKerja->jam_masuk_istirahat) < strtotime($getAbsenKembali->jam)) ? 1 : 0) : 1, //terhitung telat kembali jika tidak absen
                                        "jadwal_masuk" => $cekHariKerja->jam_masuk_toleransi,
                                        "jadwal_pulang" => $cekHariKerja->jam_pulang_toleransi,
                                    ];
                                } else {
                                    $data[] = [
                                        "id" => Str::uuid(),
                                        "pegawai_id" => $peg->id,
                                        "tanggal" => $getTgl,
                                        "hari" => 1,
                                        "status" => 1,
                                        "keterangan" => 'Tidak Absen',
                                        "jenis_izin_id" => null,
                                        "jam_masuk" => null,
                                        "is_telat" => 1,
                                        "jam_pulang" => null,
                                        "is_pulang_cepat" => 1,
                                        "user" => Auth::user()->name,
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                        "jam_keluar_istirahat" => $getAbsenKeluar->jam ?? null,
                                        "jam_masuk_istirahat" => $getAbsenKembali->jam ?? null,
                                        "is_telat_kembali" => (isset($getAbsenKembali->jam)) ? ((strtotime($cekHariKerja->jam_masuk_istirahat) < strtotime($getAbsenKembali->jam)) ? 1 : 0) : 1, //terhitung telat kembali jika tidak absen
                                        "jadwal_masuk" => $cekHariKerja->jam_masuk_toleransi,
                                        "jadwal_pulang" => $cekHariKerja->jam_pulang_toleransi,
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    //Jika Pegawai Shift
                    $jadwal_shift_pegawai = ShiftPegawai::with('shift')->where('pegawai_id', $peg->id)->whereDate('tanggal_mulai', '<=', $getTgl)->whereDate('tanggal_selesai', '>=', $getTgl)->first();
                    if (!$jadwal_shift_pegawai) {
                        //jika hari tidak ada shift
                        $data[] = [
                            "id" => Str::uuid(),
                            "pegawai_id" => $peg->id,
                            "tanggal" => $getTgl,
                            "hari" => 0,
                            "status" => 0,
                            "keterangan" => $this->getNamaHari($getHari),
                            "jenis_izin_id" => null,
                            "jam_masuk" => null,
                            "is_telat" => 0,
                            "jam_pulang" => null,
                            "is_pulang_cepat" => 0,
                            "user" => Auth::user()->name,
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                            "jam_keluar_istirahat" => null,
                            "jam_masuk_istirahat" => null,
                            "is_telat_kembali" => 0,
                            "jadwal_masuk" => null,
                            "jadwal_pulang" => null,
                        ];
                    } else {
                        //jika hari kerja masuk status=1
                        Kehadiran::whereNotNull('deleted_at')
                            ->where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->withTrashed()->update([
                                'deleted_at' => null
                            ]);

                        //jika hari kerja normal
                        $getAbsenMasuk = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 0)->orderBy('jam', 'asc')->first();
                        //absen masuk jenis=0 atau masuk
                        if ($getAbsenMasuk) {
                            //jika ada absen
                            if (isset($getAbsenMasuk->jenis_izin_id)) {
                                //jika izin dihari kerja
                                $izin = JenisIzin::where('id', $getAbsenMasuk->jenis_izin_id)->first();
                                $data[] = [
                                    "id" => Str::uuid(),
                                    "pegawai_id" => $peg->id,
                                    "tanggal" => $getTgl,
                                    "hari" => 1,
                                    "status" => $izin->hak,
                                    "keterangan" => $getAbsenMasuk->keterangan,
                                    "jenis_izin_id" => $izin->id,
                                    "jam_masuk" => null,
                                    "is_telat" => 0,
                                    "jam_pulang" => null,
                                    "is_pulang_cepat" => 0,
                                    "user" => Auth::user()->name,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                    "jam_keluar_istirahat" => null,
                                    "jam_masuk_istirahat" => null,
                                    "is_telat_kembali" => 0,
                                    "jadwal_masuk" => null,
                                    "jadwal_pulang" => null,
                                ];
                            } else {
                                //jika masuk
                                $getAbsenPulang = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 1)->orderBy('jam', 'desc')->first();
                                $getAbsenKeluar = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 2)->first();
                                $getAbsenKembali = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 3)->first();
                                $data[] = [
                                    "id" => Str::uuid(),
                                    "pegawai_id" => $peg->id,
                                    "tanggal" => $getTgl,
                                    "hari" => 1,
                                    "status" => 1,
                                    "keterangan" => $getAbsenMasuk->keterangan,
                                    "jenis_izin_id" => null,
                                    "jam_masuk" => $getAbsenMasuk->jam ?? null,
                                    "is_telat" => (isset($getAbsenMasuk->jam)) ? ((strtotime($getAbsenMasuk->jam) <= strtotime($jadwal_shift_pegawai->shift->jam_masuk)) ? 0 : 1) : 1,
                                    "jam_pulang" => $getAbsenPulang->jam ?? null,
                                    "is_pulang_cepat" => (isset($getAbsenPulang->jam)) ? ((strtotime($jadwal_shift_pegawai->shift->jam_pulang) > strtotime($getAbsenPulang->jam)) ? 1 : 0) : 0,
                                    "user" => Auth::user()->name,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                    "jam_keluar_istirahat" => $getAbsenKeluar->jam ?? null,
                                    "jam_masuk_istirahat" => $getAbsenKembali->jam ?? null,
                                    "is_telat_kembali" => (isset($getAbsenKembali->jam)) ? ((strtotime($jadwal_shift_pegawai->shift->jam_masuk_istirahat) < strtotime($getAbsenKembali->jam)) ? 1 : 0) : 1, //terhitung telat kembali jika tidak absen
                                    "jadwal_masuk" => $jadwal_shift_pegawai->shift->jam_masuk,
                                    "jadwal_pulang" => $jadwal_shift_pegawai->shift->jam_pulang,
                                ];
                            }
                        } else {
                            //tidak absen
                            $getAbsenPulang = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 1)->orderBy('tanggal', 'desc')->first();
                            $getAbsenKeluar = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 2)->first();
                            $getAbsenKembali = Kehadiran::where('pegawai_id', $peg->id)->whereDate('tanggal', $getTgl)->where('jenis', 3)->first();
                            if ($getAbsenPulang) {
                                //tidak absen masuk tapi absen pulang
                                // return $cekHariKerja->jam_pulang_toleransi;
                                $data[] = [
                                    "id" => Str::uuid(),
                                    "pegawai_id" => $peg->id,
                                    "tanggal" => $getTgl,
                                    "hari" => 1,
                                    "status" => 1,
                                    "keterangan" => 'Tidak Absen Masuk',
                                    "jenis_izin_id" => null,
                                    "jam_masuk" => null,
                                    "is_telat" => 1,
                                    "jam_pulang" => $getAbsenPulang->jam ?? null,
                                    "is_pulang_cepat" => (isset($getAbsenPulang->jam)) ? ((strtotime($jadwal_shift_pegawai->shift->jam_pulang) > strtotime($getAbsenPulang->jam)) ? 1 : 0) : 0,
                                    "user" => Auth::user()->name,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                    "jam_keluar_istirahat" => $getAbsenKeluar->jam ?? null,
                                    "jam_masuk_istirahat" => $getAbsenKembali->jam ?? null,
                                    "is_telat_kembali" => (isset($getAbsenKembali->jam)) ? ((strtotime($jadwal_shift_pegawai->shift->jam_masuk_istirahat) < strtotime($getAbsenKembali->jam)) ? 1 : 0) : 1, //terhitung telat kembali jika tidak absen
                                    "jadwal_masuk" => $jadwal_shift_pegawai->shift->jam_masuk,
                                    "jadwal_pulang" => $jadwal_shift_pegawai->shift->jam_pulang,
                                ];
                            } else {
                                $data[] = [
                                    "id" => Str::uuid(),
                                    "pegawai_id" => $peg->id,
                                    "tanggal" => $getTgl,
                                    "hari" => 1,
                                    "status" => 1,
                                    "keterangan" => 'Tidak Absen',
                                    "jenis_izin_id" => null,
                                    "jam_masuk" => null,
                                    "is_telat" => 1,
                                    "jam_pulang" => null,
                                    "is_pulang_cepat" => 1,
                                    "user" => Auth::user()->name,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                    "jam_keluar_istirahat" => $getAbsenKeluar->jam ?? null,
                                    "jam_masuk_istirahat" => $getAbsenKembali->jam ?? null,
                                    "is_telat_kembali" => (isset($getAbsenKembali->jam)) ? ((strtotime($jadwal_shift_pegawai->shift->jam_masuk_istirahat) < strtotime($getAbsenKembali->jam)) ? 1 : 0) : 1, //terhitung telat kembali jika tidak absen
                                    "jadwal_masuk" => $jadwal_shift_pegawai->shift->jam_masuk,
                                    "jadwal_pulang" => $jadwal_shift_pegawai->shift->jam_pulang,
                                ];
                            }
                        }
                    }
                }
            }
        }
        // return $data;
        Absensi::insert($data);
        alert()->success('Success !!', 'Berhasil Posting Absen ');
        return redirect()->route('posting_absen.index');
        // return true;

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


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
