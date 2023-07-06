@extends('layouts.app')

@section('content')

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget ">
                <div class="widget-heading">
                    <h5 class="">Selamat Datang {{Auth::user()->name}}</h5>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection