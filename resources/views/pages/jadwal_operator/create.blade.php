@extends('layouts.app')

@push('style')
<link href="{{asset('plugins/leaflet/leaflet.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/leaflet/draw/leaflet.draw.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/leaflet/pegman/leaflet-pegman.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<link href="{{asset('plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 user-profile layout-spacing">
            <div class="widget-content widget-content-area">
                <div class="d-flex justify-content-between">
                    <!-- <h5 class="">Data {{ucwords($page_name)}}</h5> -->
                    <h3 class="">Form Data {{ucwords(str_replace('_', ' ',$page_name))}}</h3>
                    <a href="{{route('jadwal_operator.index')}}" class="mt-2 edit-profile">
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


                        <form action="{{ request()->routeIs('jadwal_operator.create')?route('jadwal_operator.store') : route('jadwal_operator.update',$jadwal_operator) }}" method="post" data-parsley-validate="true">
                            @csrf
                            @if (request()->routeIs('jadwal_operator.create'))
                            @method('post')
                            @else
                            @method('put')
                            @endif
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <p>Nama Jadwal Operator</p>
                                    <input id="nama" type="text" name="nama" class="form-control" value="{{ old('nama',$jadwal_operator->nama??'') }}" required>
                                </div>
                                <div class="form-group col-lg-4">
                                    <p>Jam Masuk</p>
                                    <input class="form-control flatpickr flatpickr-input basicFlatpickr" value="{{(isset($jadwal_operator))?date('H:i', strtotime($jadwal_operator->jam_masuk)):''}}" type="text" name="jam_masuk" required>
                                </div>
                                <div class="form-group col-lg-4">
                                    <p>Jam Pulang</p>
                                    <input class="form-control flatpickr flatpickr-input basicFlatpickr" type="text" value="{{(isset($jadwal_operator))?date('H:i', strtotime($jadwal_operator->jam_pulang)):''}}" name="jam_pulang" required>
                                </div>
                                <div class="form-group col-lg-4 col-md-12 col-xs-12">
                                    <p>Beda Hari?</p>
                                    <select class="form-control" name="is_beda_hari">
                                        <option value="0" {{ old('is_beda_hari',$jadwal_operator->is_beda_hari??'')==0 ? 'selected' : '' }}>
                                            TIDAK
                                        </option>
                                        <option value="1" {{ old('is_beda_hari',$jadwal_operator->is_beda_hari??'')==1 ? 'selected' : '' }}>
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
<script src="{{asset('plugins/leaflet/leaflet.js')}}"></script>
<script src="{{asset('plugins/leaflet/pegman/leaflet-pegman.min.js')}}"></script>
<script src="{{asset('plugins/leaflet/draw/leaflet.draw.js')}}"></script>
<script src="{{asset('plugins/leaflet/ajax/leaflet.ajax.js')}}"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="{{asset('plugins/flatpickr/flatpickr.js')}}"></script>
<script>
    $(".basicFlatpickr").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
</script>
<script>
    let firstZoom = 9;
    let map;
    let markerHandle = "{!! old('longlat',$jadwal_operator->longlat??'') !!}";
    if (!'{{request()->routeIs("jadwal_operator.create")}}')
        firstZoom = 18

    initMap();

    function initMap() {
        console.log('L', L);
        // var searchLayer = L.geoJson().addTo(map);
        map = L.map('map-container', {
            scrollWheelZoom: true,
            minZoom: 9,

        }).setView(JSON.parse("{!! old('longlat',$jadwal_operator->longlat??'[-8.711051242084778, 116.85531381363474]') !!}"), firstZoom);
        L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        var geocoder = L.Control.geocoder({
                defaultMarkGeocode: false
            })
            .on('markgeocode', function(e) {
                var bbox = e.geocode.bbox;
                var poly = L.polygon([
                    bbox.getSouthEast(),
                    bbox.getNorthEast(),
                    bbox.getNorthWest(),
                    bbox.getSouthWest()
                ]).addTo(map);
                map.fitBounds(poly.getBounds());
            })
            .addTo(map);
        var baseLayers = {};

        //buat circle radius
        if (markerHandle != '')
            L.circle([JSON.parse(markerHandle)[0], JSON.parse(markerHandle)[1]], 100).addTo(map);

        baseLayers["ESRI World Imagery"] = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://www.esri.com/">Esri</a>',
        });
        baseLayers["Google"] = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 17,
            attribution: 'Map data: &copy; <a href="http://www.google.com">Google</a> ',
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });
        baseLayers["OpenStreetMap"] = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        });

        let layersControl = L.control.layers(baseLayers, null, {
            position: 'topleft',
            collapsed: true,
        });

        let pegmanControl = new L.Control.Pegman({
            position: 'bottomright', // position of control inside the map
            theme: "leaflet-pegman-v3-default", // or "leaflet-pegman-v3-default"
            apiKey: 'AIzaSyC_ZJNBkDDBXnyIOlQcubvqH0lqNst1X-M' // CHANGE: with your google maps api key
        });


        pegmanControl.addTo(map);
        layersControl.addTo(map);

        baseLayers["OpenStreetMap"].addTo(map);

        let editableLayers = new L.FeatureGroup();
        map.addLayer(editableLayers);



        let options = {
            position: 'topright',
            draw: {
                polygon: false,
                circle: false,
                rectangle: false,
                circlemarker: false,
                polyline: false,
                marker: true
            },
            edit: {
                featureGroup: editableLayers, //REQUIRED!!
                remove: true,
                edit: false
            }
        };
        let drawControl = new L.Control.Draw(options);
        map.addControl(drawControl);

        map.on("draw:drawstart", function(e) {
            editableLayers.clearLayers()
        });

        map.on("draw:deletestop", function(e) {
            $("#longlat").val('');
        });

        map.on('draw:created', function(e) {
            editableLayers.addLayer(e.layer);
            coord = editableLayers.toGeoJSON().features[0].geometry.coordinates;
            let newPath = [coord[1], coord[0]];
            JSONString = JSON.stringify(newPath);
            $("#longlat").val(JSONString);
        });
        displayMarker(editableLayers);
    }

    function displayMarker(Layer) {
        if (markerHandle === "") {
            return;
        }

        longlat = JSON.parse(markerHandle);
        console.log('ini : ' + longlat);
        let marker = new L.Marker(longlat);
        Layer.addLayer(marker);

        // map.flyTo(longlat).zoom(17);


    }
</script>
@endpush