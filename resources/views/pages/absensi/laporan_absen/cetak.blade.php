<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=21cm, initial-scale=1">
    <meta name="description" content="Sistem Informasi Akademik Universitas Mataram">
    <meta name="author" content="Universitas Mataram">
    <title>Laporan Rekap Absensi</title>
    <!-- <link rel="stylesheet" href="{{asset('cetak/b.min.css')}}"> -->
    <link rel="stylesheet" href="{{asset('cetak/f.min.css')}}">
    <link rel="stylesheet" href="{{asset('cetak/style.css')}}">

    <link rel="shortcut icon" type="image/png" href="{{asset('assets/img/logo.png')}}" sizes="16x16">
    <link rel="apple-touch-icon" href="{{asset('assets/img/logo.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('assets/img/logo.png')}}">

    <style>

    </style>
</head>

<body class="view mahasiswa halaman" onload="cetak()">
    <div class="">
        <div class="row">
            {{-- <hr class="garis"> --}}
            <center style="margin-top: -10px;">
                <b style="font-size: 16px;">LAPORAN REKAP ABSENSI</b><br>
                <b style="font-size: 10px;">PERIODE {{(Cache::has('dTgl'))?Cache::get('dTgl').' to '.Cache::get('sTgl'):date('d-m-Y')}}</b>
            </center>
            <br>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>Nip</th>
                        <th>Pegawai </th>
                        <th>Hari Kerja</th>
                        <th>Kehadiran</th>
                        <th>Telat</th>
                        <th>TK</th>
                        @foreach ($jenis_izin as $izin)
                        <th>{{ $izin->name }}</th>
                        @endforeach
                        <th>Jam Kerja</th>
                        <th>%</th>
                    </tr>

                </thead>
                <tbody>
                    @php
                    $hari_kerja = 0;
                    $kehadiran = 0;
                    $telat = 0;
                    $tanpa_keterangan = 0;
                    $total_persen = 0;
                    @endphp

                    @foreach ($jenis_izin as $izin)
                    @php
                    $persen_kehadiran = 0 ;
                    ${$izin->name} = 0;
                    @endphp
                    @endforeach
                    @foreach($data_absen as $data)

                    @php
                    $hari_kerja += $data['jam_kerja'];
                    $kehadiran += $data['kehadiran'];
                    $telat += $data['telat'];
                    $tanpa_keterangan += $data['tanpa_keterangan'];
                    

                    if($data['jam_kerja']>0) {
                    $total_persen += ((($data['kehadiran']/$data['jam_kerja'])*100));
                    $persen_kehadiran = round(($data['kehadiran']/$data['jam_kerja'])*100,2);
                    }

                    if($persen_kehadiran < 50){
                        $warna='black' ;
                        $warna_tulisan='white' ;
                        } else if ($persen_kehadiran < 85) {
                        $warna='red' ;
                        $warna_tulisan='white' ;
                        } else if ($persen_kehadiran < 90) {
                        $warna='yellow' ;
                        $warna_tulisan='black' ;
                        }else {
                        $warna='green' ;
                        $warna_tulisan='white' ;
                        }
                        @endphp
                        <tr style="background-color: {{$warna}}!important; -webkit-print-color-adjust: exact;">
                        <td align="center" style="color: {{$warna_tulisan}};">{{$loop->iteration}}</td>
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data['nip']}}</td>
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data['nama']}}</td>
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data['jam_kerja']}}</td>
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data['kehadiran']}}</td>
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data['telat']}}</td>
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data['tanpa_keterangan']}}</td>
                        @foreach ($jenis_izin as $izin)
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data[strtolower(str_replace(' ', '_', $izin->name))]}}</td>
                        @endforeach
                        <td align="center" style="color: {{$warna_tulisan}};">{{$data['jam']}} Jam {{$data['menit']}} Menit</td>
                        <td align="center" style="color: {{$warna_tulisan}};">{{$persen_kehadiran}}%</td>
                        </tr>
                        @endforeach
                        <tr style="font-weight: 700; background-color: {{$warna}};">
                            <td colspan="3" style="text-align: center">Total</td>
                            <td>{{$hari_kerja}}</td>
                            <td>{{$kehadiran}}</td>
                            <td>{{$telat}}</td>
                            <td>{{$tanpa_keterangan}}</td>
                            @foreach ($jenis_izin as $izin)
                            <td>{{ ${$izin->name} }}</td>
                            @endforeach
                            <td colspan="2" style="text-align: center">{{round($total_persen/count($data_absen),2)}}%</td>

                        </tr>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
            <hr style="height:3px;  background-color:black">
            <div class="pull-left ttd">
                <br><b>Keterangan : </b>
                <br>- TK = Tanpa Keterangan
            </div>
            <div class="pull-right ttd">
                <br>Mataram, {{date('d F Y')}}<br>
                <br>Pembuat,
                <br><br><br><br><br>
                <span class="nama">{{ Auth::user()->nama }}</span>
            </div>

        </div>
        <br>
        {{-- <div class=" ttd text-center">
            <br>Mengetahui
            <br>Orang Tua / Wali,
            <br><br><br><br><br>
            <span class="nama">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div> --}}
    </div>

    <script type="text/javascript">
        function cetak() {
            window.print();
        };
    </script>


</body>

</html>
