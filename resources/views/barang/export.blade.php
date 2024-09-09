<html>

<head>
    <link rel="stylesheet" href="/css/bootstrap.css">
</head>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td>
                <img src="images/logo1_pakarti.png" width="80px"/>
                </td>
                <td class="text-center">
                    <h1 class="text-center" style="font-weight: bold;font-size: 12pt;">PT PAKARTI TIRTOAGUNG</h1>
                    <p class="mb-0" style="font-size: 10pt;margin-bottom:10px">Jl. Raya Tengah No. 4 - Ps. Rebo Jakarta Timur Tlp. (021) 87783422 / 87783423. Faksimili (021)8403133</p>
                    <p class="mb-0" style="font-size: 10pt;">Website: www.pakarti.com Email: Info@pakarti.com
                        </p>
                </td>
            </tr>
        </table>
        <hr/>
        <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">LAPORAN INVENTARIS</h2>
        <h2 class="h4 text-center" style="font-weight: bold; margin-top:0px">
            Tanggal : {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}
        </h2>
        <table class="table table-bordered datatable w-100">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>No Inventaris</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Tahun</th>
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
                        <td>{{ $a->nama }}</td>
                        <td>{{ $a->kategori->nama }}</td>
                        <td>{{ $a->lokasi->nama }}</td>
                        <td>{{ $a->tahun }}</td>
                        <td>{{ $a->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>