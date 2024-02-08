<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=21cm, initial-scale=1">
    <meta name="description" content="Sistem Informasi Akademik Universitas Mataram">
    <meta name="author" content="Universitas Mataram">
    <title>Laporan Rincian Absensi</title>
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
                <b style="font-size: 16px;">LAPORAN RINCIAN ABSENSI</b><br>
                <b style="font-size: 10px;">PERIODE {{(Cache::has('dTgl'))?Cache::get('dTgl').' to '.Cache::get('sTgl'):date('d-m-Y')}}</b>
            </center>
            <br>
            
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Nip</th>
                        <th>Pegawai </th>
                        <th>Tanggal</th>
                        <th>Terlambat</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Kembali</th>
                        <th>Pulang</th>
                        <th>Jam Kerja</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($data as $dt)
                    <tr>
                        <td rowspan="{{ count($dt['data'])+1 }}">{{ $dt['nip'] }}</td>
                        <td class="text-nowrap" rowspan="{{ count($dt['data'])+1 }}">{{ $dt['name'] }}</td>
                    </tr>
                    @php
                        $menit=0;
                    @endphp
                    @foreach ($dt['data'] as $d)
                    @php
                        if( $d['status']!='Hari Libur' && $d['status']!='Izin' && $d['hari']!=0 )
                            $menit+= $d['menit'];
                        
                    @endphp
                    @if( $d['status']=='Hari Libur')
                        <tr style="background-color: rgb(235, 170, 170)">
                        <td class="text-center ">{{ $d['tgl'] }}</td>
                        <td colspan="6" class="text-center">{{ $d['status'] }}- <b>{{ $d['keterangan'] }}</b></td>
                        </tr>
                    @elseif($d['status']=='Izin' )
                    <tr style="background-color: rgb(219, 235, 170)">
                    <td class="text-center ">{{ $d['tgl'] }}</td>
                    <td colspan="6" class="text-center">{{ $d['status'] }}- <b>{{ $d['keterangan'] }}</b></td>
                    </tr>
                    @elseif($d['keterangan']=='Tidak Absen' )
                    <tr style="background-color: rgb(239, 116, 82)">
                    <td class="text-center ">{{ $d['tgl'] }}</td>
                    <td colspan="6" class="text-center"><b>{{ $d['keterangan'] }}</b></td>
                    </tr>
                    @elseif($d['hari']==0)
                                <tr style="background-color: amber">
                                <td class="text-center ">{{ $d['tgl'] }}</td>
                                <td colspan="6" class="text-center"><b>Libur</b></td>
                                </tr>
                    @else
                    <tr class="">
                        <td class="text-center">{{ $d['tgl'] }}</td>
                        <td class="text-center">
                            {{ $d['status']=='Terlambat' ? 'Ya' : '-' }}
                            </td>
                            
                            <td class="text-center ">
                                {{ $d['masuk']}}
                            </td>
                            <td class="text-center ">
                                {{ $d['keluar']??'-' }}
                            </td>
                            <td class="text-center ">
                                {{ $d['kembali']??'-' }}
                            </td>
                            <td class="text-center ">
                                {{ $d['pulang']??'-' }}
                            </td>
                            <td class="text-center ">{{ $d['jam_kerja'] }}
                            </td>
                            
                        </tr>
                    @endif
                    @endforeach
                    <tr>
                        <td class="text-center" colspan="8"><b>Total Jam Kerja</b></td>
                        <td class="text-center">{{ floor($menit/60) }} Jam {{ $menit%60 }} Menit</td>
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
        
    </div>

    <script type="text/javascript">
        function cetak() {
            window.print();
        };
    </script>


</body>

</html>