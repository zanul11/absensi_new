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


                        <form action="{{ route('pegawai.import.store') }}" method="post" data-parsley-validate="true" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-12 col-xs-12">
                                    <p>File</p>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                            </div>


                            <button type="submit" class="mt-4 btn btn-primary">Import</button>
                        </form>
                        <a href="{{ route('pegawai.template') }}" class="mt-4 btn btn-success">Download Template Pegawai</a>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')


@endpush