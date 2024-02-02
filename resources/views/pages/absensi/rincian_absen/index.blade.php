@extends('layouts.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/dt-global_style.css')}}">
<link href="{{asset('plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
<style>
   table.table-bordered {
    border: 1px solid black !important;
    margin-top: 20px;
}
table.table-bordered > thead > tr > th {
    border: 1px solid black !important;
}
table.table-bordered > tbody > tr > td {
    border: 1px solid black !important;
    color: black;
    font-size: 8pt;
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
                    <h3 class="">Data {{ucwords(str_replace('_',' ',$page_name))}}</h3>
                    <div>
                        <a target="_blank" href="{{route('rincian_absen.show',1)}}" class="mt-2 edit-profile">
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
                    <table  class="table table-bordered" style="color: black">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nip</th>
                                <th>Pegawai </th>
                                <th>Tanggal</th>
                                <th>Terlambat</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Kembali</th>
                                <th>Pulang</th>
                                <th>Jam Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt)
                                <tr>
                                    <td rowspan="{{ count($dt['data'])+1 }}">{{ $dt['nip'] }}</td>
                                    <td class="text-nowrap" rowspan="{{ count($dt['data'])+1 }}">{{ $dt['name'] }}</td>
                                </tr>
                                @php
                                    $menit=0;
                                @endphp
                                @foreach ($dt['data'] as $d)
                                @php
                                    if( $d['status']!='Hari Libur' && $d['status']!='Izin')
                                        $menit+= $d['menit'];
                                    
                                @endphp
                                @if( $d['status']=='Hari Libur')
                                    <tr style="background-color: rgb(235, 170, 170)">
                                    <td class="text-center ">{{ $d['tgl'] }}</td>
                                    <td colspan="6" class="text-center">{{ $d['status'] }}- <b>{{ $d['keterangan'] }}</b></td>
                                    </tr>
                                @elseif($d['status']=='Izin' )
                                <tr style="background-color: rgb(219, 235, 170)">
                                <td class="text-center ">{{ $d['tgl'] }}</td>
                                <td colspan="6" class="text-center">{{ $d['status'] }}- <b>{{ $d['keterangan'] }}</b></td>
                                </tr>
                                @elseif($d['keterangan']=='Tidak Absen' )
                                <tr style="background-color: rgb(239, 116, 82)">
                                <td class="text-center ">{{ $d['tgl'] }}</td>
                                <td colspan="6" class="text-center">{{ $d['status'] }}- <b>{{ $d['keterangan'] }}</b></td>
                                </tr>
                                @else
                                <tr class="">
                                    <td class="text-center">{{ $d['tgl'] }}</td>
                                    <td class="text-center" style="background-color: {{ ($d['status']=='Terlambat')?'yellow':'' }};">
                                        <!-- {{ $d['status']=='Terlambat' ? 'Ya' : '-' }} -->
                                        @if($d['status']=='Terlambat')
                                        {{ $d['telat'] }}
                                        
                                        @else - @endif
                                        </td>
                                        
                                        <td class="text-center ">
                                            {{ $d['masuk']??'-'}}
                                        </td>
                                        <td class="text-center ">
                                            {{ $d['keluar']??'-' }}
                                        </td>
                                        <td class="text-center ">
                                            {{ $d['kembali']??'-' }}
                                        </td>
                                        <td class="text-center ">
                                            {{ $d['pulang']??'-' }}
                                        </td>
                                        <td class="text-center ">{{ $d['jam_kerja'] }}
                                        </td>
                                        
                                    </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="8"><b>Total Jam Kerja</b></td>
                                    <td class="text-center">{{ floor($menit/60) }} Jam {{ $menit%60 }} Menit</td>
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
    
</script>

@endpush