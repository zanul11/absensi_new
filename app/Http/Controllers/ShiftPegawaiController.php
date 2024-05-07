<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftPegawaiRequest;
use App\Models\JadwalShift;
use App\Models\Pegawai;
use App\Models\ShiftPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use  Yajra\Datatables\DataTables;

class ShiftPegawaiController extends Controller
{
    protected   $data = [
        'category_name' => 'pengaturan',
        'page_name' => 'shift_pegawai',
    ];

    public function data()
    {
        $data =  ShiftPegawai::query()->with('pegawai')->with('shift')
            ->select(['*']);

        return DataTables::of($data)
            ->editColumn('tanggal', function ($data) {
                return date('d-m-Y', strtotime($data->tanggal_mulai)) . ' - ' . date('d-m-Y', strtotime($data->tanggal_selesai));
            })
            ->addColumn('shift', function ($data) {
                return '<span class="badge badge-primary">' . $data->shift->nama . '</span> (' . $data->shift->jam_masuk . ' - ' . $data->shift->jam_pulang . ')';
            })
            ->addColumn('action', function ($data) {
                $edit = '<a href="' . route('shift_pegawai.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';

                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('shift_pegawai.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action', 'shift'])
            ->make(true);
    }

    public function index()
    {
        return view('pages.shift_pegawai.index')->with($this->data);
    }

    public function create()
    {
        $shift = JadwalShift::all();
        $pegawai = Pegawai::where('is_shift', 1)->get();
        return view('pages.shift_pegawai.create', compact('shift', 'pegawai'))->with($this->data);
    }
    public function store(ShiftPegawaiRequest $request)
    {
        // return $request;
        $cek = ShiftPegawai::where('pegawai_id', $request->pegawai_id)->whereDate('tanggal_mulai', '<=', $request->tanggal_mulai)->whereDate('tanggal_selesai', '>=', $request->tanggal_mulai)->first();
        if ($cek) {
            alert()->error('Ooppss!', 'Sudah ada jadwal shift di tanggal yang dipilih!');
            return back()->withInput();
        }
        $cek = ShiftPegawai::where('pegawai_id', $request->pegawai_id)->whereDate('tanggal_mulai', '<=', $request->tanggal_selesai)->whereDate('tanggal_selesai', '>=', $request->tanggal_selesai)->first();
        if ($cek) {
            alert()->error('Ooppss!', 'Sudah ada jadwal shift di tanggal yang dipilih!');
            return back()->withInput();
        }
        try {
            DB::transaction(function () use ($request) {
                $image = ShiftPegawai::create($request->validated());
            });
            alert()->success('Success', 'Data berhasil disimpan!');
            return redirect()->route('shift_pegawai.index');
        } catch (\Exception $e) {
            alert()->error('Ooppss!', 'Proses simpan data gagal!');
            return back()->withInput()->withErrors($e->getMessage());
        }
    }
    public function show(ShiftPegawai $shiftPegawai)
    {
        //
    }


    public function edit(ShiftPegawai $shiftPegawai)
    {
        $shift = JadwalShift::all();
        $pegawai = Pegawai::where('is_shift', 1)->get();
        return view('pages.shift_pegawai.create', compact('shift', 'pegawai', 'shiftPegawai'))->with($this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftPegawaiRequest $request, ShiftPegawai $shiftPegawai)
    {
        try {
            $shiftPegawai->update($request->validated());
            alert()->success('Success !!', 'Data berhasil diupdate');
            return redirect()->route('shift_pegawai.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
            // return response()->json($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShiftPegawai $shiftPegawai)
    {
        try {
            $shiftPegawai->delete();
            alert()->success('Deleted !!', 'Data berhasil dihapus !');
            return response()->json(["success" => "Data berhasil dihapus !"], 200);
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return response()->json(["error" => $th->getMessage()], 501);
        }
    }
}
