<?php

namespace App\Http\Controllers;

use App\Models\JadwalAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalAbsenController extends Controller
{
    protected   $data = [
        'category_name' => 'pengaturan',
        'page_name' => 'jadwal_absen',
    ];

    public function index()
    {
        $jadwalAbsen = JadwalAbsen::orderBy('hari')->get();

        return view('pages.jadwal_absen.index', compact('jadwalAbsen'))->with($this->data);
    }

    public function store(Request $request)
    {
        // return $request;
        try {
            JadwalAbsen::whereNotNull('id')->delete();
            for ($i = 0; $i < 7; $i++) {
                JadwalAbsen::create([
                    'hari' => $request->hari[$i],
                    'status' => $request->status[$i],
                    'jam_masuk' => $request->jam_masuk[$i],
                    'jam_masuk_toleransi' => $request->jam_masuk_toleransi[$i],
                    'jam_pulang' => $request->jam_pulang[$i],
                    'jam_pulang_toleransi' => $request->jam_pulang_toleransi[$i],
                    'jam_keluar_istirahat' => $request->jam_keluar_istirahat[$i],
                    'jam_masuk_istirahat' => $request->jam_masuk_istirahat[$i],
                    'user' => Auth::user()->name,
                ]);
            }
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('jadwal_absen.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
            // return response()->json($th->getMessage());
        }
    }

    public function show(JadwalAbsen $jadwalAbsen)
    {
        //
    }
    public function edit(JadwalAbsen $jadwalAbsen)
    {
        //
    }
    public function update(Request $request, JadwalAbsen $jadwalAbsen)
    {
        //
    }
    public function destroy(JadwalAbsen $jadwalAbsen)
    {
        //
    }
}
