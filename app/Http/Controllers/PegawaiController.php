<?php

namespace App\Http\Controllers;

use App\Exports\TemplatePegawai;
use App\Http\Requests\PegawaiRequest;
use App\Imports\ImportPegawai;
use App\Models\Location;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use  Yajra\Datatables\DataTables;

class PegawaiController extends Controller
{

    protected   $data = [
        'category_name' => 'data_master',
        'page_name' => 'pegawai',
    ];

    public function data()
    {
        $data =  Pegawai::query()->with('lokasi')
            ->select(['*']);

        return DataTables::of($data)

            ->addColumn('action', function ($data) {
                $edit = '<a href="' . route('pegawai.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';

                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('pegawai.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index()
    {
        return view('pages.pegawai.index')->with($this->data);
    }

    public function create()
    {
        $locations = Location::all();
        return view('pages.pegawai.create', compact('locations'))->with($this->data);
    }

    public function import()
    {
        return view('pages.pegawai.import')->with($this->data);
    }

    public function template()
    {
        return Excel::download(new TemplatePegawai(), 'template_pegawai_' . time() . '.xlsx');
    }

    public function import_post(Request $request)
    {
        // return $request;
        try {
            Excel::import(new ImportPegawai, $request->file('file'));
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('pegawai.import');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function store(PegawaiRequest $request)
    {
        try {
            Pegawai::create($request->validated());
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('pegawai.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
            // return response()->json($th->getMessage());
        }
    }


    public function show(Pegawai $pegawai)
    {
        //
    }


    public function edit(Pegawai $pegawai)
    {
        $locations = Location::all();
        return view('pages.pegawai.create', compact('locations', 'pegawai'))->with($this->data);
    }


    public function update(PegawaiRequest $request, Pegawai $pegawai)
    {
        try {
            $pegawai->update($request->validated());
            alert()->success('Success !!', 'Data berhasil diupdate');
            return redirect()->route('pegawai.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }


    public function destroy(Pegawai $pegawai)
    {
        try {
            $pegawai->delete();
            alert()->success('Deleted !!', 'Data berhasil dihapus !');
            return response()->json(["success" => "Data berhasil dihapus !"], 200);
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return response()->json(["error" => $th->getMessage()], 501);
        }
    }
}
