<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenPulangRequest;
use App\Models\Pegawai;
use App\Models\RequestAbsenPulang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use  Yajra\Datatables\DataTables;
use Illuminate\Support\Arr;

class RequestAbsenPulangController extends Controller
{
    protected   $data = [
        'category_name' => 'pengajuan',
        'page_name' => 'absen_pulang',
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
                $edit = '<a href="' . route('request_absen_pulang.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';

                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('request_absen_pulang.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
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
            return redirect()->route('absen_pulang.index');
        } catch (\Exception $e) {
            alert()->error('Ooppss!', 'Proses simpan data gagal!');
            return back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestAbsenPulang $requestAbsenPulang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestAbsenPulang $requestAbsenPulang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestAbsenPulang $requestAbsenPulang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestAbsenPulang $requestAbsenPulang)
    {
        //
    }
}
