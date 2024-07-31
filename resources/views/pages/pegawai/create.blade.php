@extends('layouts.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.css')}}">
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 user-profile layout-spacing">
            <div class="widget-content widget-content-area">
                <div class="d-flex justify-content-between">
                    <!-- <h5 class="">Data {{ucwords($page_name)}}</h5> -->
                    <h3 class="">Form Data {{ucwords($page_name)}}</h3>
                    <a href="{{route('pegawai.index')}}" class="mt-2 edit-profile">
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


                        <form action="{{ request()->routeIs('pegawai.create')?route('pegawai.store') : route('pegawai.update',$pegawai) }}" method="post" data-parsley-validate="true">
                            @csrf
                            @if (request()->routeIs('pegawai.create'))
                            @method('post')
                            @else
                            @method('put')
                            @endif
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Username (tanpa spasi)</p>
                                    <input id="username" type="text" name="username" class="form-control" value="{{ old('username',$pegawai->username??'') }}" @if(request()->route()->getName()=='pegawai.edit') readonly @endif required>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Password</p>
                                    <input id="password" type="text" name="password" class="form-control" {{request()->routeIs('pegawai.create')?'required':''}}>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Nip</p>
                                    <input id="nip" type="text" name="nip" class="form-control" value="{{ old('nip',$pegawai->nip??'') }}" required>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Nama</p>
                                    <input id="name" type="text" name="name" class="form-control" value="{{ old('name',$pegawai->name??'') }}" required>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Alamat</p>
                                    <input id="alamat" type="text" name="alamat" class="form-control" value="{{ old('alamat',$pegawai->alamat??'') }}" required>
                                </div>
                                <div class="form-group col-lg-2 col-md-12 col-xs-12">
                                    <p>Status Pegawai</p>
                                    <select class="form-control" name="status_pegawai">
                                        <option value="1" {{ old('status_pegawai',$pegawai->status_pegawai??'')==1 ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="0" {{ old('status_pegawai',$pegawai->status_pegawai??'')==0 ? 'selected' : '' }}>
                                            Non Aktif
                                        </option>

                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-xs-12">
                                    <p>Pegawai Shift</p>
                                    <select class="form-control" name="is_shift">
                                        <option value="0" {{ old('is_shift',$pegawai->is_shift??'')==0 ? 'selected' : '' }}>
                                            TIDAK
                                        </option>
                                        <option value="1" {{ old('is_shift',$pegawai->is_shift??'')==1 ? 'selected' : '' }}>
                                            IYA
                                        </option>

                                    </select>
                                </div>

                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>No Hp</p>
                                    <input id="nohp" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="12" type="number" name="nohp" class="form-control" value="{{ old('nohp',$pegawai->nohp??'') }}" required>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Lokasi Absen </p>
                                    <select class="form-control select2" data-live-search="false" name="location_id">
                                        @foreach ($locations as $key => $dt)
                                        <option value="{{ $dt->id }}" {{ old('location_id',$pegawai->location_id??'')==$dt->id ? 'selected' : '' }}>
                                            {{ $dt->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Jadwal Operator</p>
                                    <select class="form-control" data-live-search="false" name="jadwal_operator_id">
                                        <option value="">Pilih Jadwal</option>
                                        @foreach ($jadwal_operator as $key => $dt)
                                        <option value="{{ $dt->id }}" {{ old('jadwal_operator_id',$pegawai->jadwal_operator_id??'')==$dt->id ? 'selected' : '' }}>
                                            {{ $dt->nama }} | {{ $dt->jam_masuk.' - '.$dt->jam_pulang }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>Operator</p>
                                    <select class="form-control" name="is_operator">
                                        <option value="0" {{ old('is_operator',$pegawai->is_operator??'')==0 ? 'selected' : '' }}>
                                            TIDAK
                                        </option>
                                        <option value="1" {{ old('is_operator',$pegawai->is_operator??'')==1 ? 'selected' : '' }}>
                                            IYA
                                        </option>

                                    </select>
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
<script>
    var ss = $(".select2").select2({});
</script>

@endpush