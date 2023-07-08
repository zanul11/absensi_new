@extends('layouts.app')

@push('style')
<link href="{{asset('plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 user-profile layout-spacing">
            <div class="widget-content widget-content-area">
                <div class="d-flex justify-content-between">
                    <!-- <h5 class="">Data {{ucwords($page_name)}}</h5> -->
                    <h3 class="">Data {{ucwords(str_replace('_', ' ',$page_name))}}</h3>
                    <a href="{{route('home')}}" class="mt-2 edit-profile">
                        <i data-feather="home"></i></a>
                </div><br>
                <form action="{{ route('jadwal_absen.store') }}" method="post" data-parsley-validate="true">
                    @csrf
                    <div class="row">
                        @foreach (config('constants.hari') as $key => $item)
                        @if(count($jadwalAbsen)>0)
                        @php
                        $status = $jadwalAbsen[$key]->status;
                        $jam_masuk = $jadwalAbsen[$key]->jam_masuk;
                        $jam_masuk_toleransi = $jadwalAbsen[$key]->jam_masuk_toleransi;
                        $jam_pulang = $jadwalAbsen[$key]->jam_pulang;
                        $jam_pulang_toleransi = $jadwalAbsen[$key]->jam_pulang_toleransi;
                        @endphp
                        @else
                        @php
                        $status = '';
                        $jam_masuk = '';
                        $jam_masuk_toleransi = '';
                        $jam_pulang = '';
                        $jam_pulang_toleransi = '';
                        @endphp
                        @endif
                        <div class="form-group col-lg-2">
                            <p>Hari</p>
                            <input type="text" value="{{$item}}" class="form-control" disabled>
                            <input id="hari" type="hidden" name="hari[]" value="{{$key}}" class="form-control">
                        </div>
                        <div class="form-group col-lg-2">
                            <p>Status Masuk</p>
                            <select class="form-control select2" data-live-search="false" name="status[]">
                                <option value="0" {{($status==0)?"selected":""}}>Tidak</option>
                                <option value="1" {{($status==1)?"selected":""}}>Masuk</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <p>Jam Masuk</p>
                            <input class="form-control flatpickr flatpickr-input basicFlatpickr" value="{{(isset($jadwalAbsen))?date('H:i', strtotime($jam_masuk)):''}}" type="text" name="jam_masuk[]" required>
                        </div>
                        <div class="form-group col-lg-2">
                            <p>Jam Masuk Toleransi</p>
                            <input class="form-control flatpickr flatpickr-input basicFlatpickr" value="{{(isset($jadwalAbsen))?date('H:i', strtotime($jam_masuk_toleransi)):''}}" type="text" name="jam_masuk_toleransi[]" required>
                        </div>
                        <div class="form-group col-lg-2">
                            <p>Jam Pulang</p>
                            <input class="form-control flatpickr flatpickr-input basicFlatpickr" type="text" value="{{(isset($jadwalAbsen))?date('H:i', strtotime($jam_pulang)):''}}" name="jam_pulang[]" required>
                        </div>
                        <div class="form-group col-lg-2">
                            <p>Jam Pulang Toleransi</p>
                            <input class="form-control flatpickr flatpickr-input basicFlatpickr" type="text" value="{{(isset($jadwalAbsen))?date('H:i', strtotime($jam_pulang_toleransi)):''}}" name="jam_pulang_toleransi[]" required>
                        </div>
                        @endforeach

                    </div>
                    <button type="submit" class="mt-4 btn btn-primary">Simpan Data</button>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection
@push('scripts')
<script src="{{asset('plugins/flatpickr/flatpickr.js')}}"></script>
<script>
    $(".basicFlatpickr").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
</script>
@endpush