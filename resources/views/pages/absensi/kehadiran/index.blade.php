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
                    <h3 class="">Data {{ucwords($page_name)}}</h3>
                    <a href="{{route('kehadiran.create')}}" class="mt-2 edit-profile">
                        <i data-feather="plus"></i></a>
                </div>
                <form action="" method="get" data-parsley-validate="true">
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-12 col-xs-12">
                            <p></p>
                            <input name="tanggal" value="{{(Cache::has('dTgl'))?Cache::get('dTgl').' to '.Cache::get('sTgl'):''}}" class="form-control flatpickr-input active basicFlatpickr" type="text" placeholder="Pilih Tanggal.." required>
                        </div>
                        <div class="form-group col-lg-4 col-md-12 col-xs-12">
                            <p></p>
                            <button type="submit" class="form-control btn-success">Filter Tanggal</button>
                        </div>

                    </div>
                </form>

                <div class="table-responsive" >
                    <table id="datatable" class="table table-hover" >
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th>Tanggal</th>
                                <th>Pegawai </th>
                                <th>Absen</th>
                                <th>Jam</th>
                                <th>Keterangan</th>
                                <th>Foto</th>
                                <th style="width: 5% !important;" class="text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

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
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: true,
        pageLength: 100,
        lengthMenu: [
       
        100, 250, 500, 'All'
    ],
        ajax: "{!! route('kehadiran.data') !!}",
        columns: [{
                data: 'id',
                name: 'id',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'tanggal',
                name: 'tanggal',
               
            }, 
            {
                data: 'pegawai.name',
                name: 'pegawai.name'
            }, {
                data: 'absen',
                name: 'absen'
            },
            {
                data: 'jam',
                name: 'jam'
            }, {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'link',
                name: 'link'
            },
            {
                data: 'action',
                name: 'action'
            }
        ],
        render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    });
</script>

@endpush