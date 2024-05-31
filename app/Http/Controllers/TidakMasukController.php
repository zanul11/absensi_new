<?php

namespace App\Http\Controllers;

use App\Http\Requests\TidakMasukRequest;
use App\Models\JenisIzin;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Cache;
use App\Models\TidakMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use  Yajra\Datatables\DataTables;
use Illuminate\Support\Arr;



class TidakMasukController extends Controller
{
    protected   $data = [
        'category_name' => 'pengajuan',
        'page_name' => 'tidak_masuk',
    ];
    public function data()
    {
        if (Cache::has('dTgl')) {
            $from = date('Y-m-d', strtotime(Cache::get('dTgl')));
            $to = date('Y-m-d', strtotime(Cache::get('sTgl')));
            $data =  TidakMasuk::query()->with(['pegawai','jenis_izin'])
                ->whereBetween(DB::raw('DATE(created_at)'), array($from, $to))
                ->select(['*'])->orderBy('created_at', 'desc')->orderBy('status', 'asc');
        } else {
            $data =  TidakMasuk::query()->with(['pegawai','jenis_izin'])
                ->select(['*'])->orderBy('created_at', 'desc')->orderBy('status', 'asc');;
        }
        return DataTables::of($data)
        ->editColumn('tanggal', function ($data) {
            return date('d-m-Y', strtotime($data->tanggal_mulai)).' - '.date('d-m-Y', strtotime($data->tanggal_selesai));
        })
            ->editColumn('status', function ($data) {
                return ($data->status == 0) ? '<span class="badge badge-warning"> Menunggu Verifikasi </span>' : (($data->status == 1) ? '<span class="badge badge-success"> Diterima </span>' : '<span class="badge badge-danger"> Ditolak </span>');
            })
            ->addColumn('link', function ($data) {
                return "<a data-fancybox='gallery' href='" . $data->getFirstMediaUrl('tidak_masuk') . "' class='text-success' title='Lihat Image' target='_blank'><img src='{$data->getFirstMediaUrl("tidak_masuk")}' alt='' width='100' height='70'></a>";
            })
            ->addColumn('action', function ($data) {
                if ($data->status == 0) {
                    $verif = '<a href="' . route('tidak_masuk.show', $data->id) . '" class="text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </a>';
                    $edit = '<a href="' . route('tidak_masuk.edit', $data->id) . '" class="text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a>';

                    $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('tidak_masuk.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
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


        return view('pages.pengajuan.tidak_masuk.index')->with($this->data);
    }


    public function create()
    {
        $pegawai = Pegawai::where('status_pegawai', 1)->get();
        $jenis = JenisIzin::all();
        return view('pages.pengajuan.tidak_masuk.create', compact('pegawai', 'jenis'))->with($this->data);
    }
    public function store(TidakMasukRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $image = TidakMasuk::create(Arr::except($request->validated(), 'file'));
                if ($request->hasFile('file'))
                    $image->addMediaFromRequest('file')->toMediaCollection('tidak_masuk');
            });
            alert()->success('Success', 'Data berhasil disimpan!');
            return redirect()->route('tidak_masuk.index');
        } catch (\Exception $e) {
            alert()->error('Ooppss!', 'Proses simpan data gagal!');
            return back()->withInput()->withErrors($e->getMessage());
        }
    }

    public function show(TidakMasuk $tidakMasuk)
    {
        $pegawai = Pegawai::where('status_pegawai', 1)->get();
        $jenis = JenisIzin::all();
        return view('pages.pengajuan.tidak_masuk.show', compact('pegawai', 'jenis','tidakMasuk'))->with($this->data);
    }

  
    public function edit(TidakMasuk $tidakMasuk)
    {
        $pegawai = Pegawai::where('status_pegawai', 1)->get();
        $jenis = JenisIzin::all();
        return view('pages.pengajuan.tidak_masuk.create', compact('pegawai', 'jenis','tidakMasuk'))->with($this->data);
    }

   
    public function update(TidakMasukRequest $request, TidakMasuk $tidakMasuk)
    {
        try {
            DB::transaction(function () use ($request, $tidakMasuk) {
                $tidakMasuk->update(Arr::except($request->validated(), 'file'));
                if ($request->hasFile('file')) {
                    $tidakMasuk->getFirstMedia('tidak_masuk')?->delete();
                    $tidakMasuk->addMediaFromRequest('file')->toMediaCollection('tidak_masuk');
                }
            });

            alert()->success('Success', 'Data berhasil diupdate!');
            return redirect()->route('tidak_masuk.index');
        } catch (\Exception $e) {
            alert()->error('Ooppss!', 'Proses simpan data gagal!');
            return back()->withInput()->withErrors($e->getMessage());
        }
    }

    
    public function destroy(TidakMasuk $tidakMasuk)
    {
        $tidakMasuk->delete();
    }
    public function verifikasi(Request $req)
    {
        // return $req;
       
        $data = TidakMasuk::where('id', $req->id)->first();
        $data->status = $req->status;
        $data->alasan = $req->alasan;
        $data->verified_at = date('Y-m-d H:i:s');
        $data->user = auth()->user()->name;
        if ($data->status == 1) {
            $startTimeStamp = strtotime($req->tanggal_mulai);
            $endTimeStamp = strtotime($req->tanggal_selesai);
            $timeDiff = abs($endTimeStamp - $startTimeStamp);
            $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
             $numberDays = intval($numberDays);
    
    
            $from = date('Y-m-d', $startTimeStamp);
            $to = date('Y-m-d', $endTimeStamp);
            Kehadiran::whereBetween('tanggal', [$from, $to])->delete();
            for ($i = 0; $i <= $numberDays; $i++) {
                $getTgl = date('Y-m-d', strtotime("+" . $i . " day", strtotime($req->tanggal_mulai)));
                $hadir = Kehadiran::create([
                    'pegawai_id' => $data->pegawai_id,
                    'tanggal' => $getTgl,
                    'jenis_izin_id' => $data->jenis_izin_id,
                    'keterangan' => $data->keterangan.' (Request)',
                    'jam' => '00:00:00',
                    'location' => null,
                    'user' => auth()->user()->name
                ]);
            }
            
        }
        $data->save();
        alert()->success('Success', 'Data berhasil diupdate!');
        return redirect()->route('tidak_masuk.index');
    }
}
