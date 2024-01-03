<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenPulangRequest;
use App\Models\Pegawai;
use App\Models\RequestAbsenPulang;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use  Yajra\Datatables\DataTables;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class RequestAbsenPulangController extends Controller
{
    protected   $data = [
        'category_name' => 'pengajuan',
        'page_name' => 'request_absen_pulang',
    ];
    public function data()
    {
        if (Cache::has('dTgl')) {
            $from = date('Y-m-d', strtotime(Cache::get('dTgl')));
            $to = date('Y-m-d', strtotime(Cache::get('sTgl')));
            $data =  RequestAbsenPulang::query()->with('pegawai')
                // ->whereBetween('tanggal', [$from, $to])
                ->whereBetween(DB::raw('DATE(tanggal)'), array($from, $to))
                ->select(['*']);
        } else {
            $data =  RequestAbsenPulang::query()->with('pegawai')
                ->select(['*']);
        }
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return ($data->status == 0) ? '<span class="badge badge-warning"> Menunggu Verifikasi </span>' : (($data->status == 1) ? '<span class="badge badge-success"> Diterima </span>' : '<span class="badge badge-danger"> Ditolak </span>');
            })
            ->addColumn('link', function ($data) {
                return "<a href='" . $data->getFirstMediaUrl('absen_pulang') . "' class='text-success' title='Lihat Image' target='_blank'><img src='{$data->getFirstMediaUrl("absen_pulang")}' alt='' width='100' height='70'></a>";
            })
            ->addColumn('action', function ($data) {
                if ($data->status == 0) {
                    $verif = '<a href="' . route('request_absen_pulang.show', $data->id) . '" class="text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </a>';
                    $edit = '<a href="' . route('request_absen_pulang.edit', $data->id) . '" class="text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a>';

                    $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('request_absen_pulang.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";
                } else {
                    $verif = 'Verified by : <small> ' . $data->user . '</br>' . $data->verified_at . '</small>';
                    $edit = '';
                    $delete = '';
                }

                return  $verif . '  ' . $edit . '  ' . $delete;
            })
            ->rawColumns(['action', 'status', 'link'])
            ->make(true);
    }
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


        return view('pages.pengajuan.absen_pulang.index')->with($this->data);
    }


    public function create()
    {
        $pegawai = Pegawai::all();
        return view('pages.pengajuan.absen_pulang.create', compact('pegawai'))->with($this->data);
    }


    public function store(AbsenPulangRequest $request)
    {
        // return $request;
        try {
            DB::transaction(function () use ($request) {
                $image = RequestAbsenPulang::create(Arr::except($request->validated(), 'file'));
                if ($request->hasFile('file'))
                    $image->addMediaFromRequest('file')->toMediaCollection('absen_pulang');
            });


            alert()->success('Success', 'Data berhasil disimpan!');
            return redirect()->route('request_absen_pulang.index');
        } catch (\Exception $e) {
            alert()->error('Ooppss!', 'Proses simpan data gagal!');
            return back()->withInput()->withErrors($e->getMessage());
        }
    }





    public function show(RequestAbsenPulang $requestAbsenPulang)
    {
        // return $requestAbsenPulang;
        $pegawai = Pegawai::all();
        return view('pages.pengajuan.absen_pulang.show', compact('pegawai', 'requestAbsenPulang'))->with($this->data);
    }


    public function edit(RequestAbsenPulang $requestAbsenPulang)
    {
        $pegawai = Pegawai::all();
        return view('pages.pengajuan.absen_pulang.create', compact('pegawai', 'requestAbsenPulang'))->with($this->data);
    }


    public function update(AbsenPulangRequest $request, RequestAbsenPulang $requestAbsenPulang)
    {
        try {
            DB::transaction(function () use ($request, $requestAbsenPulang) {
                $requestAbsenPulang->update(Arr::except($request->validated(), 'file'));
                if ($request->hasFile('file')) {
                    $requestAbsenPulang->getFirstMedia('absen_pulang')?->delete();
                    $requestAbsenPulang->addMediaFromRequest('file')->toMediaCollection('absen_pulang');
                }
            });

            alert()->success('Success', 'Data berhasil diupdate!');
            return redirect()->route('request_absen_pulang.index');
        } catch (\Exception $e) {
            alert()->error('Ooppss!', 'Proses simpan data gagal!');
            return back()->withInput()->withErrors($e->getMessage());
        }
    }
    public function verifikasi(Request $req)
    {
        // return $req;
        $data = RequestAbsenPulang::where('id', $req->id)->first();
        $data->status = $req->status;
        $data->alasan = $req->alasan;
        $data->verified_at = date('Y-m-d H:i:s');
        $data->save();
        alert()->success('Success', 'Data berhasil diupdate!');
        return redirect()->route('request_absen_pulang.index');
    }

    public function destroy(RequestAbsenPulang $requestAbsenPulang)
    {
        $requestAbsenPulang->delete();
    }
}
