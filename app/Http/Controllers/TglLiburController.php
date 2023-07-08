<?php

namespace App\Http\Controllers;

use App\Http\Requests\TanggalLiburRequest;
use App\Models\TglLibur;
use  Yajra\Datatables\DataTables;

class TglLiburController extends Controller
{
    protected   $data = [
        'category_name' => 'absensi',
        'page_name' => 'tanggal_libur',
    ];

    public function data()
    {
        $data =  TglLibur::query()
            ->select(['*']);

        return DataTables::of($data)
            ->addColumn('tgl', function ($data) {
                return date('d-m-Y', strtotime($data->tgl_libur));
            })
            ->addColumn('user_detail', function ($data) {
                return '<small> ' . $data->user . '</br>' . $data->updated_at . '</small>';
            })
            ->addColumn('action', function ($data) {
                $edit = '<a href="' . route('tanggal_libur.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';

                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('tanggal_libur.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action', 'user_detail'])
            ->make(true);
    }

    public function index()
    {
        return view('pages.absensi.tanggal_libur.index')->with($this->data);
    }

    public function create()
    {
        return view('pages.absensi.tanggal_libur.create')->with($this->data);
    } //

    public function store(TanggalLiburRequest $request)
    {
        // return $request;
        try {
            TglLibur::create($request->validated());
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('tanggal_libur.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function show(TglLibur $tglLibur)
    {
        //
    }

    public function edit(TglLibur $tanggal_libur)
    {
        return view('pages.absensi.tanggal_libur.create', compact('tanggal_libur'))->with($this->data);
    }

    public function update(TanggalLiburRequest $request, TglLibur $tanggal_libur)
    {
        try {
            $tanggal_libur->update($request->validated());
            alert()->success('Success !!', 'Data berhasil diupdate');
            return redirect()->route('tanggal_libur.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(TglLibur $tanggal_libur)
    {
        try {
            $tanggal_libur->delete();
            alert()->success('Deleted !!', 'Data berhasil dihapus !');
            return response()->json(["success" => "Data berhasil dihapus !"], 200);
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return response()->json(["error" => $th->getMessage()], 501);
        }
    }
}
