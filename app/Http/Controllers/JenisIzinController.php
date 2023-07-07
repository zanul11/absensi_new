<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisIzinRequest;
use App\Models\JenisIzin;
use Illuminate\Http\Request;
use  Yajra\Datatables\DataTables;

class JenisIzinController extends Controller
{
    protected   $data = [
        'category_name' => 'data_master',
        'page_name' => 'jenis_izin',
    ];

    public function data()
    {
        $data =  JenisIzin::query()
            ->select(['*']);

        return DataTables::of($data)
            ->addColumn('user_detail', function ($data) {
                return '<small> ' . $data->user . '</br>' . $data->updated_at . '</small>';
            })
            ->addColumn('hak_detail', function ($data) {
                return ($data->hak == 0) ? '<span class="badge badge-danger"> Hitung Tidak Masuk </span>' : '<span class="badge badge-success"> Hitung Masuk </span>';
            })
            ->addColumn('action', function ($data) {
                $edit = '<a href="' . route('jenis_izin.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';

                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('jenis_izin.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action', 'user_detail', 'hak_detail'])
            ->make(true);
    }

    public function index()
    {
        return view('pages.jenis_izin.index')->with($this->data);
    }

    public function create()
    {
        return view('pages.jenis_izin.create')->with($this->data);
    }

    public function store(JenisIzinRequest $request)
    {
        try {
            JenisIzin::create($request->validated());
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('jenis_izin.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }


    public function show(JenisIzin $jenisIzin)
    {
        //
    }


    public function edit(JenisIzin $jenisIzin)
    {
        return view('pages.jenis_izin.create', compact('jenisIzin'))->with($this->data);
    }


    public function update(JenisIzinRequest $request, JenisIzin $jenisIzin)
    {
        try {
            $jenisIzin->update($request->validated());
            alert()->success('Success !!', 'Data berhasil diupdate');
            return redirect()->route('jenis_izin.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }


    public function destroy(JenisIzin $jenisIzin)
    {
        try {
            $jenisIzin->delete();
            alert()->success('Deleted !!', 'Data berhasil dihapus !');
            return response()->json(["success" => "Data berhasil dihapus !"], 200);
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return response()->json(["error" => $th->getMessage()], 501);
        }
    }
}
