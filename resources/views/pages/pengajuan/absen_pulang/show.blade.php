@extends('layouts.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.css')}}">
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
                    <h3 class="">Verifikasi Request Absen Pulang (Luar Lokasi)</h3>
                    <a href="{{route('request_absen_pulang.index')}}" class="mt-2 edit-profile">
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


                        <form action="{{  route('request_absen_pulang.verifikasi') }}" method="post" data-parsley-validate="true" >
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-12 col-xs-12">
                                    <p>Tanggal</p>
                                    <input value="{{ old('tanggal',date('d-m-Y', strtotime($requestAbsenPulang->tanggal??date('d-m-Y')))) }}" name="tanggal" class="form-control flatpickr flatpickr-input active basicFlatpickr" type="text" placeholder="Select Date..">
                                </div>
                                <div class="form-group col-lg-2 col-md-12 col-xs-12">
                                    <p>Jam </p>
                                    <input class="form-control flatpickr flatpickr-input basicFlatpickrJam" value="{{ old('jam',date('H:i', strtotime($requestAbsenPulang->tanggal??date('H:i')))) }}" type="text" name="jam">
                                </div>
                                <div class="form-group col-lg-3 col-md-12 col-xs-12">
                                    <p>Jenis Request</p>
                                    <select class="form-control " data-live-search="false" name="jenis" readonly>
                                        <option value="1" {{ old('jenis',$requestAbsenPulang->jenis??'')==1 ? 'selected' : '' }}>
                                            Absen Pulang
                                        </option>
                                        <option value="2" {{ old('jenis',$requestAbsenPulang->jenis??'')==2 ? 'selected' : '' }}>
                                            Absen Keluar
                                        </option>
                                        <option value="3" {{ old('jenis',$requestAbsenPulang->jenis??'')==3 ? 'selected' : '' }}>
                                            Absen Kembali
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-xs-12">
                                    <p>Pegawai</p>
                                    <select class="form-control " data-live-search="true" name="pegawai_id" readonly>
                                        <option value="">Pilih Pegawai</option>

                                        @foreach ($pegawai as $key => $dt)
                                        <option value="{{ $dt->id }}" {{ old('pegawai_id',$requestAbsenPulang->pegawai_id??'')==$dt->id ? 'selected' : '' }}>
                                            {{ $dt->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-8 col-md-12 col-xs-12">
                                    <p>Keterangan</p>
                                    <textarea id="keterangan" name="keterangan" class="form-control" disabled>{{ old('keterangan',$requestAbsenPulang->keterangan??'') }}</textarea>
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-xs-12">
                                    <p>Foto</p>
                                    <a data-fancybox='gallery' href="{{$requestAbsenPulang->getFirstMediaUrl('absen_pulang')?asset($requestAbsenPulang->getFirstMediaUrl('absen_pulang')):''}}">
                                   <img  class="text-center" src="{{$requestAbsenPulang->getFirstMediaUrl('absen_pulang')?asset($requestAbsenPulang->getFirstMediaUrl('absen_pulang')):''}}" width="25%">
                                </a>
                                </div>
                                <input type="hidden" name="id" value="{{$requestAbsenPulang->id}}">
                                <div class="form-group col-lg-4 col-md-12 col-xs-12">
                                    <p>Status</p>
                                    <select class="form-control " data-live-search="false" name="status">
                                        <option value="1" {{(old('jenis',$requestAbsenPulang->status??'')==1)?"selected":""}}>Terima</option>
                                        <option value="2" {{(old('jenis',$requestAbsenPulang->status??'')==2)?"selected":""}}>Tolak</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-8 col-md-12 col-xs-12">
                                    <p>Alasan</p>
                                    <textarea id="alasan" name="alasan" class="form-control" required>{{ old('alasan',$requestAbsenPulang->alasan??'') }}</textarea>
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
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('plugins/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('plugins/flatpickr/custom-flatpickr.js')}}"></script>
<script>
    var ss = $(".select2").select2({});
    $(".basicFlatpickr").flatpickr({
        dateFormat: "d-m-Y",
    });
    $(".basicFlatpickrJam").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
</script>
@endpush