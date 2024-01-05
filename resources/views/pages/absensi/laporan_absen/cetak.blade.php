<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=21cm, initial-scale=1">
    <meta name="description" content="Sistem Informasi Akademik Universitas Mataram">
    <meta name="author" content="Universitas Mataram">
    <title>Laporan Rekap Absensi</title>
    <link rel="stylesheet" href="{{asset('cetak/b.min.css')}}">
    <link rel="stylesheet" href="{{asset('cetak/f.min.css')}}">
    <link rel="stylesheet" href="{{asset('cetak/style.css')}}">

    <link rel="shortcut icon" type="image/png" href="{{asset('assets/img/logo.png')}}" sizes="16x16">
    <link rel="apple-touch-icon" href="{{asset('assets/img/logo.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('assets/img/logo.png')}}">

    <style>
        @media print {
            .garis {
                background-color: black !important;
                height: 3px;
            }
            table th, table td {
                border:1px solid #fe1616;
                padding:0.5em;
            }
        }
        
        @media screen {
            .garis {
                background-color: black !important;
                height: 3px;
            }
        }
    </style>
</head>

<body class="view mahasiswa halaman" onload="cetak()">
    <div class="container-fluid cetak krs">
        <div class="row">
            {{-- <hr class="garis"> --}}
            <center style="margin-top: -10px;">
                <b style="font-size: 16px;">LAPORAN REKAP ABSENSI</b><br>
                <b style="font-size: 10px;">PERIODE {{(Cache::has('dTgl'))?Cache::get('dTgl').' to '.Cache::get('sTgl'):date('d-m-Y')}}</b>
            </center>
            <br>
            
            <table class="table table-hover table-bordered">
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
                    @foreach($data_absen as $data)
                    <tr >
                        <td align="center">{{$loop->iteration}}</td>
                        <td align="center">{{$data['nip']}}</td>
                        <td align="center">{{$data['nama']}}</td>
                        <td align="center">{{$data['jam_kerja']}}</td>
                        <td align="center">{{$data['kehadiran']}}</td>
                        <td align="center">{{$data['telat']}}</td>
                        <td align="center">{{$data['tanpa_keterangan']}}</td>
                        @foreach ($jenis_izin as $izin) 
                            <td align="center">{{$data[strtolower(str_replace(' ', '_', $izin->name))]}}</td>
                        @endforeach
                        <td align="center">{{$data['jam']}} Jam {{$data['menit']}} Menit</td>
                        <td align="center">{{($data['kehadiran']/$data['jam_kerja'])*100}}%</td>
                    </tr>
                    @endforeach
                </tbody>
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
                <span class="nama">{{ Auth::user()->name }}</span>
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