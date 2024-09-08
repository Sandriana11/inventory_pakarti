<html>

<head>
    <link rel="stylesheet" href="/css/bootstrap.css">
</head>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td>
                    <img src="/images/logo_jabar.png" width="80pt"/>
                </td>
                <td class="text-center">
                    <h1 class="text-center" style="font-weight: bold;font-size: 12pt;">PEMERINTAH DAERAH PROVINSI JAWA BARAT</h1>
                    <h1 class="h4 text-center" style="font-weight: bold;font-size: 14pt;">DINAS KOMUNIKASI DAN INFORMATIKA</h1>
                    <p class="mb-0" style="font-size: 10pt;margin-bottom:10px">Jalan Tamansari No.55 Tlp. (022) 2502898. Faksimili (022) 2511505</p>
                    <p class="mb-0" style="font-size: 10pt;">Website: diskominfo.jabarprov.go.id email: diskominfo@jabarprov.go.id
                        Bandung 40132
                        </p>
                </td>
            </tr>
        </table>
        <hr/>
        <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">LAPORAN PERBAIKAN</h2>
        <h2 class="h4 text-center" style="font-weight: bold; margin-top:0px">
            Periode : {{ \Carbon\Carbon::parse($tgl[0])->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tgl[1])->translatedFormat('d F Y') }}
        </h2>
        <table class="table table-bordered datatable w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No Perbaikan</th>
                    <th>No Laporan</th>
                    <th>Tanggal</th>
                    <th>Eksekutor</th>
                    <th>Target</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($data as $a)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $a->nomor }}</td>
                        <td>{{ $a->kerusakan->nomor }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->tgl)->translatedFormat('d F Y') }}</td>
                        <td>{{ $a->eksekutor->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->target)->translatedFormat('d F Y') }}</td>
                        <td>
                            @if ($a->status == 0)
                            Proses
                            @else
                            Selesai
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>