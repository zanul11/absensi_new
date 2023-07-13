<div class="mail-box-container">
    <div class="tab-title">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map">
                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                    <line x1="8" y1="2" x2="8" y2="18"></line>
                    <line x1="16" y1="6" x2="16" y2="22"></line>
                </svg>
                <br><br>
                <h5 class="app-title">Daftar Lokasi</h5>
            </div>

            <div class="todoList-sidebar-scroll">
                <div class="col-md-12 col-sm-12 col-12 mt-4 pl-0">
                    <ul class="nav nav-pills d-block" id="pills-tab" role="tablist">
                        @foreach($lokasis as $key => $dt)
                        <li class="nav-item">
                            <a wire:click="ubahLokasi('{{$dt->id}}')" class="nav-link list-actions {{($locationId==$dt->id)?'active':''}}" id="all-list" data-toggle="pill" href="#pills-inbox" role="tab" aria-selected="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg> {{$dt->name}} <span class="todo-badge badge"></span></a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="todo-inbox" class="todo-inbox">
        <div class="search">
            <input type="text" class="form-control" placeholder="Cari Pegawai..." wire:model="keySearch" wire:change="cariPegawai()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </div>
        <div class="todo-item">
            <div class="row">
                @foreach($pegawais as $key => $dt)
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="todo-item-inner">
                        <div class="n-chk text-center">
                            <label class="new-control new-checkbox checkbox-primary ">
                                <input wire:click="updateLokasiPegawai('{{$dt->id}}')" type="checkbox" class="new-control-input inbox-chkbox" {{($daftar_pegawai_lokasi->contains('id', $dt->id))?'checked':''}}>
                                <span class="new-control-indicator"></span>
                            </label>
                        </div>

                        <div class="todo-content">
                            <h5 class="todo-heading" data-todoHeading="{{$dt->name}}">{{$dt->name}}</h5>
                            <p class="meta-date">{{$dt->nip}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')

@endpush