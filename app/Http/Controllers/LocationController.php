<?php

namespace App\Http\Controllers;

use App\Http\Requests\LokasiRequest;
use App\Models\Location;
use App\Models\Pegawai;
use  Yajra\Datatables\DataTables;

class LocationController extends Controller
{
    protected   $data = [
        'category_name' => 'pengaturan',
        'page_name' => 'lokasi',
    ];
    public function data()
    {
        $data =  Location::query()->with('pegawai')->withCount('pegawai');
        return DataTables::of($data)
            ->addColumn('user_detail', function ($data) {
                return '<small> ' . $data->user . '</br>' . $data->updated_at . '</small>';
            })
            ->addColumn('internet', function ($data) {
                return ($data->is_connected == 0) ? '<span class="badge badge-danger"> Tidak Ada </span>' : '<span class="badge badge-success"> Ada </span>';
            })
            ->addColumn('jmlPegawai', function ($data) {

                return  "<a href='" . route('lokasi.show', $data->id) . "' class='btn btn-sm btn-outline-primary' title='Lihat Data'>[ {$data->pegawai_count} ] - Pegawai</a>";
            })
            ->addColumn('action', function ($data) {
                $edit = '<a href="' . route('lokasi.edit', $data->id) . '" class="text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>';

                $delete = "<a href='#' onclick='fn_deleteData(" . '"' . route('lokasi.destroy', $data->id) . '"' . ")' class='text-danger' title='Hapus Data'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>";

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action', 'user_detail', 'jmlPegawai', 'internet'])
            ->make(true);
    }

    public function index()
    {
        // $daftar_pegawai_lokasi = Pegawai::query()->where('location_id', '9995e198-b896-4942-a735-d263567bd83d')->orderby('name')->get();
        // return $daftar_pegawai_lokasi->contains('id', '99a20c57-d74d-444b-9eb7-6135a349ba0fs');
        return view('pages.lokasi.index')->with($this->data);
    }

    public function create()
    {
        return view('pages.lokasi.create')->with($this->data);
    }
    public function store(LokasiRequest $request)
    {
        try {
            Location::create($request->validated());
            alert()->success('Success !!', 'Data berhasil disimpan');
            return redirect()->route('lokasi.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
            // return response()->json($th->getMessage());
        }
    }


    public function show(Location $lokasi)
    {

        return view('pages.lokasi.show', compact('lokasi'))->with($this->data);
    }


    public function edit(Location $lokasi)
    {
        return view('pages.lokasi.create', compact('lokasi'))->with($this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LokasiRequest $request, Location $lokasi)
    {
        try {
            $lokasi->update($request->validated());
            alert()->success('Success !!', 'Data berhasil diupdate');
            return redirect()->route('lokasi.index');
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
            // return response()->json($th->getMessage());
        }
    }

    public function destroy(Location $lokasi)
    {
        try {
            $lokasi->delete();
            alert()->success('Deleted !!', 'Data berhasil dihapus !');
            return response()->json(["success" => "Data berhasil dihapus !"], 200);
        } catch (\Throwable $th) {
            alert()->error('Oppss !!', $th->getMessage());
            return response()->json(["error" => $th->getMessage()], 501);
        }
    }
}
