@extends('layouts.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/dt-global_style.css')}}">
<link href="{{asset('plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
<style>
            .zoom-without-container {
                transition: transform .2s;
                /* Animation */
                margin: 0 auto;
            }

            .zoom-without-container img {
                width: 100%;
                height: auto;
            }

            .zoom-without-container:active {
                transform: scale(10);
                /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                z-index: 3000;
                position: absolute;
                padding-top: 30px;
                padding-bottom: 20px;
            }
        </style>
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 user-profile layout-spacing">
            <div class="widget-content widget-content-area">
                <div class="d-flex justify-content-between">
                    <!-- <h5 class="">Data {{ucwords($page_name)}}</h5> -->
                    <h3 class="">Data Request Absen</h3>
                    <a href="{{route('request_absen_pulang.create')}}" class="mt-2 edit-profile">
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

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered table-hover" style="width: 100% !important;">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th>Tanggal</th>
                                <th>Jenis </th>
                                <th>Pegawai </th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th style="width: 10% !important;" class="text-nowrap">Aksi</th>
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
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: true,
        pageLength: 100,
        lengthMenu: [
       
        100, 250, 500, 'All'
    ],
        ajax: "{!! route('request_absen_pulang.data') !!}",
        columns: [{
                data: 'id',
                name: 'id',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'jenis',
                name: 'jenis'
            },
            {
                data: 'pegawai.name',
                name: 'pegawai.name'
            },
            {
                data: 'lokasi',
                name: 'lokasi'
            }, {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'status',
                name: 'status'
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