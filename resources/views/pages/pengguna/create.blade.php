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
                    <a href="{{route('pengguna.index')}}" class="mt-2 edit-profile">
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


                        <form action="{{ request()->routeIs('pengguna.create')?route('pengguna.store') : route('pengguna.update',$pengguna) }}" method="post" data-parsley-validate="true">
                            @csrf
                            @if (request()->routeIs('pengguna.create'))
                            @method('post')
                            @else
                            @method('put')
                            @endif
                            <div class="form-group">
                                <p>Username (tanpa spasi)</p>
                                <input id="username" type="text" name="username" class="form-control" value="{{ old('username',$pengguna->username??'') }}" @if(request()->route()->getName()=='pengguna.edit') readonly @endif required>
                            </div>
                            <div class="form-group">
                                <p>Nama</p>
                                <input id="name" type="text" name="name" class="form-control" value="{{ old('name',$pengguna->name??'') }}" required>
                            </div>
                            <div class="form-group">
                                <p>Role</p>
                                <select class="form-control select2" data-live-search="false" name="role">
                                    @foreach (config('constants.roles') as $key => $item)
                                    <option value="{{ $key }}" {{ old('role',$pengguna->role??'')==$key ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <p>Password</p>
                                <input id="password" type="text" name="password" class="form-control" required>
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