@extends('layouts.app')
@push('style')
<link href="{{asset('assets/css/apps/todolist.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        @livewire('lokasi-pegawai', ['lokasi' => $lokasi->id])
    </div>
</div>

</div>

</div>

@endsection

@push('scripts')
<script src="{{asset('assets/js/apps/todoList.js')}}"></script>
@endpush