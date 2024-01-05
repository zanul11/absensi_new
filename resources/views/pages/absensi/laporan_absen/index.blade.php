@extends('layouts.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/dt-global_style.css')}}">
<link href="{{asset('plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 user-profile layout-spacing">
            <div class="widget-content widget-content-area">
                <div class="d-flex justify-content-between">
                    <!-- <h5 class="">Data {{ucwords($page_name)}}</h5> -->
                    <h3 class="">Data {{ucwords(str_replace('_',' ',$page_name))}}</h3>
                    <div>
                        <a target="_blank" href="{{route('laporan_absen.show',1)}}" class="mt-2 edit-profile">
                            <i data-feather="file"></i></a>
                    </div>
                   
                </div>
                <form action="" method="get" data-parsley-validate="true">
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-12 col-xs-12">
                            <input name="tanggal" value="{{(Cache::has('dTgl'))?Cache::get('dTgl').' to '.Cache::get('sTgl'):date('d-m-Y')}}" class="form-control flatpickr-input active basicFlatpickr" type="text" placeholder="Pilih Tanggal.." required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-xs-12">
                            <button type="submit" class="form-control btn-success">Filter Tanggal</button>
                        </div>
                      
                    </div>
                </form>
               
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered table-hover" style="width: 100% !important;">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th>Nip</th>
                                <th>Pegawai </th>
                                <th>Hari Kerja</th>
                                <th>Kehadiran</th>
                                <th>Telat</th>
                                <th>TK</th>
                                @foreach ($jenis_izin as $izin)
                                <th>{{ $izin->name }}</th>
                                @endforeach
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_absen as $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$data['nip']}}</td>
                                <td>{{$data['nama']}}</td>
                                <td>{{$data['jam_kerja']}}</td>
                                <td>{{$data['kehadiran']}}</td>
                                <td>{{$data['telat']}}</td>
                                <td>{{$data['tanpa_keterangan']}}</td>
                                @foreach ($jenis_izin as $izin) 
                                    <td>{{$data[strtolower(str_replace(' ', '_', $izin->name))]}}</td>
                                @endforeach
                                <td>{{($data['kehadiran']/$data['jam_kerja'])*100}}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> -->
<!-- <script src="{{asset('plugins/table/datatable/datatables.js')}}"></script> -->
@push('scripts')
@include('inc.swal-delete')

<script src="{{asset('plugins/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('plugins/table/datatable/datatables.js')}}"></script>
<script>
    $(".basicFlatpickr").flatpickr({
        mode: "range",
        dateFormat: "d-m-Y",

    });
    $('#datatable').DataTable({
        "oLanguage": {
            "oPaginate": {
                "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            },
            "sInfo": "Data _START_ sampai _END_ dari _TOTAL_ data.",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Cari...",
            "sLengthMenu": "Results :  _MENU_",
        },
        processing: false,
        serverSide: false,
        responsive: true,
        lengthChange: true,
        pageLength: 100,
        lengthMenu: [
       
        100, 250, 500, 'All'
    ],
        render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    });
</script>

@endpush