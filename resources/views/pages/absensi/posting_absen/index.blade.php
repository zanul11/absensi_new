@extends('layouts.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/dt-global_style.css')}}">
<link href="{{asset('plugins/loaders/custom-loader.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 user-profile layout-spacing">
            <div class="widget-content widget-content-area">
                <div class="d-flex justify-content-between">
                    <!-- <h5 class="">Data {{ucwords($page_name)}}</h5> -->
                    <h3 class="">{{ucwords(str_replace('_', ' ',$page_name))}}</h3>
                    <a href="{{route('home')}}" class="mt-2 edit-profile">
                        <i data-feather="home"></i></a>
                </div><br>
                <!-- <button class="btn btn-primary btn-lg mb-3 mr-3"><span class="spinner-border text-white mr-2 align-self-center loader-sm "></span> Loading</button> -->
                 <form action="{{route('posting_absen.store')}}" method="post" data-parsley-validate="true">
                    @csrf
                <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-xs-12">
                        <p>Tanggal Kehadiran</p>
                        <input name="tanggal" id="tanggal" value="{{(Cache::has('dTgl'))?Cache::get('dTgl').' to '.Cache::get('sTgl'):''}}" class="form-control flatpickr-input active basicFlatpickr" type="text" placeholder="Pilih Tanggal.." required>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-xs-12">
                        <p></p>
                        <button  type="submit" class="form-control btn-primary">
                            {{-- <span id="showLoader" class="spinner-border text-white mr-2 align-self-center loader-sm "> --}}
                                </span>Posting Absen</button>
                    </div>

                </div>
                </form>
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
    $("#showLoader").hide();
    $("#buttonPosting").click(function() {
        $("#showLoader").show();
        var tanggalValue = $('#tanggal').val();
        if (tanggalValue == '') {
            swal({
                title: 'Warning!',
                text: "Pilih Tanggal Kehadiran!",
                type: 'warning',
                padding: '2em'
            })
            $("#showLoader").hide(); 
        } else {
            token = '{{csrf_token()}}';
            $.ajax({
                url: "{{route('posting_absen.store')}}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "_method": 'POST',
                    "_token": token,
                    "tanggal": tanggalValue,
                },
                success: function(data) {
                    console.log(data);
                    // swal({
                    //     title: 'Berhasil!',
                    //     text: "Sukses Posting Absen!",
                    //     type: 'success',
                    //     padding: '2em'
                    // })
                    $("#showLoader").hide();
                },
                error: function(xhr, err) {
                    $("#showLoader").hide();
                    console.log(xhr);
                },

            });
        }
        // console.log(tanggalValue);
    });
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
        ajax: "{!! route('tanggal_libur.data') !!}",
        columns: [{
                data: 'id',
                name: 'id',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                data: 'tgl',
                name: 'tgl'
            }, {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'user_detail',
                name: 'user'
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