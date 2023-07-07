@extends('layouts.app')

@push('style')
<link href="{{asset('plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('plugins/flatpickr/custom-flatpickr.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 user-profile layout-spacing">
            <div class="widget-content widget-content-area">
                <div class="d-flex justify-content-between">
                    <!-- <h5 class="">Data {{ucwords($page_name)}}</h5> -->
                    <h3 class="">Form Data {{ucwords(str_replace('_', ' ',$page_name))}}</h3>
                    <a href="{{route('tanggal_libur.index')}}" class="mt-2 edit-profile">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg></a>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-12 ">
                        @isset($errors)
                        @if($errors->any())
                        <div class="alert alert-danger fade show">
                            <span class="close" data-dismiss="alert">×</span>
                            <strong>Oppss!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @endisset
                        <form action="{{ request()->routeIs('tanggal_libur.create')?route('tanggal_libur.store') : route('tanggal_libur.update',$tanggal_libur) }}" method="post" data-parsley-validate="true">
                            @csrf
                            @if (request()->routeIs('tanggal_libur.create'))
                            @method('post')
                            @else
                            @method('put')
                            @endif
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <p>Tanggal Libur</p>
                                    <input id="" value="{{ old('tgl_libur',date('d-m-Y', strtotime($tanggal_libur->tgl_libur??date('d-m-Y')))) }}" name="tgl_libur" class="form-control flatpickr flatpickr-input active basicFlatpickr" type="text" placeholder="Select Date..">
                                </div>
                                <div class="form-group col-lg-9">
                                    <p>Keterangan</p>
                                    <input id="keterangan" type="text" name="keterangan" class="form-control" value="{{ old('keterangan',$tanggal_libur->keterangan??'') }}" required>
                                </div>
                            </div>


                            <button type="submit" class="mt-4 btn btn-primary">Simpan Data</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="{{asset('plugins/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('plugins/flatpickr/custom-flatpickr.js')}}"></script>
<script>
    $(".basicFlatpickr").flatpickr({
        dateFormat: "d-m-Y",
    });
</script>

@endpush