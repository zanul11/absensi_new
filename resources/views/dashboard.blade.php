@extends('layouts.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.css')}}">
@endpush
@section('content')

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget ">
                <div class="widget-heading">
                    <h5 class="">Selamat Datang {{Auth::user()->name}}</h5>
                </div>
                {{-- @livewire('counter', ['title'=>'ini Title bos']) --}}
            </div>
        </div>

    </div>

</div>

@endsection
@push('scripts')
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script>
    $('#location_id').select2();
    $('#pegawai_id').select2({
        placeholder: 'Pilih Lokasi Dahulu!'
    });
</script>

@endpush