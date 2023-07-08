<?php

namespace App\Http\Controllers;

use App\Http\Requests\KehadiranRequest;
use App\Models\JenisIzin;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Cache;
use  Yajra\Datatables\DataTables;

class KehadiranController extends Controller
{
    protected   $data = [
        'category_name' => 'absensi',
        'page_name' => 'kehadiran',
    ];

    public function data()
    {
        if (Cache::has('dTgl')) {
            $from = date('Y-m-d', strtotime(Cache::get('dTgl')));
            $to = date('Y-m-d', strtotime(Cache::get('sTgl')));
            $data =  Kehadiran::query()->with('pegawai', 'jenis_izin')
                ->whereBetween('tanggal', [$from, $to])
                ->select(['*']);
        } else {
            $data =  Kehadiran::query()->with('pegawai', 'jenis_izin')
                ->select(['*']);
        }


        return DataTables::of($data)
            ->addColumn('absen', function ($data) {
                return ($data->jenis_izin) ? $data->jenis_izin?->name : (($data->jenis == 0) ? 'Masuk' : 'Pulang');
            })
            ->addColumn('action', function ($data) {
                $edit = '<a href="' . route('kehadiran.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';
                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('kehadiran.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action',  'absen'])
            ->make(true);
    }

    public function index()
    {
        if (isset(request()->tanggal)) {

            $tanggal = (explode("to", str_replace(' ', '', request()->tanggal)));
            Cache::put('dTgl', $tanggal[0]);
            Cache::put('sTgl', $tanggal[1] ?? $tanggal[0]);
        } else {
            Cache::forget('dTgl');
            Cache::forget('sTgl');
        }
        return view('pages.absensi.kehadiran.index')->with($this->data);
    }

    public function create()
    {
        $pegawai = Pegawai::all();
        $jenis_izin = JenisIzin::all();
        return view('pages.absensi.kehadiran.create', compact('pegawai', 'jenis_izin'))->with($this->data);
    }

    public function store(KehadiranRequest $request)
    {
        try {
            Kehadiran::create($request->validated());
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('kehadiran.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function show(Kehadiran $kehadiran)
    {
        return $kehadiran;
    }

    public function edit(Kehadiran $kehadiran)
    {
        $pegawai = Pegawai::all();
        $jenis_izin = JenisIzin::all();
        return view('pages.absensi.kehadiran.create', compact('pegawai', 'jenis_izin', 'kehadiran'))->with($this->data);
    }

    public function update(KehadiranRequest $request, Kehadiran $kehadiran)
    {
        try {
            $kehadiran->update($request->validated());
            alert()->success('Success !!', 'Data berhasil diupdate');
            return redirect()->route('kehadiran.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kehadiran $kehadiran)
    {
        //
    }
}
