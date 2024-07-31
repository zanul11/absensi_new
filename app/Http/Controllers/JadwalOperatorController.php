<?php

namespace App\Http\Controllers;

use App\Http\Requests\JadwalOperatorRequest;
use App\Models\JadwalOperator;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JadwalOperatorController extends Controller
{
    protected   $data = [
        'category_name' => 'pengaturan',
        'page_name' => 'jadwal_operator',
    ];
    public function data()
    {
        $data =  JadwalOperator::query();
        return DataTables::of($data)
            ->addColumn('user_detail', function ($data) {
                return '<small> ' . $data->user . '</br>' . $data->updated_at . '</small>';
            })
            ->addColumn('beda_hari', function ($data) {
                return ($data->is_beda_hari == 0) ? '<span class="badge badge-danger"> Tidak </span>' : '<span class="badge badge-success"> Iya </span>';
            })
           
            ->addColumn('action', function ($data) {
                $edit = '<a href="' . route('jadwal_operator.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';

                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('jadwal_operator.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action', 'user_detail', 'beda_hari'])
            ->make(true);
    }

    public function index()
    {
        return view('pages.jadwal_operator.index')->with($this->data);
    }

    public function create()
    {
        return view('pages.jadwal_operator.create')->with($this->data);
    }
    public function store(JadwalOperatorRequest $request)
    {
        // return $request;
        try {
            JadwalOperator::create($request->validated());
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('jadwal_operator.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    
    public function show(JadwalOperator $jadwal_operator)
    {
        //
    }

    public function edit(JadwalOperator $jadwal_operator)
    {
        return view('pages.jadwal_operator.create', compact('jadwal_operator'))->with($this->data);
    }

    public function update(JadwalOperatorRequest $request, JadwalOperator $jadwal_operator)
    {
        try {
            $jadwal_operator->update($request->validated());
            alert()->success('Success !!', 'Data berhasil diupdate');
            return redirect()->route('jadwal_operator.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
            // return response()->json($th->getMessage());
        }
    }

   
    public function destroy(JadwalOperator $jadwal_operator)
    {
        try {
            $jadwal_operator->delete();
            alert()->success('Deleted !!', 'Data berhasil dihapus !');
            return response()->json(["success" => "Data berhasil dihapus !"], 200);
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return response()->json(["error" => $th->getMessage()], 501);
        }
    }
}
