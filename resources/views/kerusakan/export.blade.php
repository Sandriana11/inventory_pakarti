<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerusakan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        h1, h2 {
            margin: 0;
        }

        hr {
            border: 0;
            border-top: 2px solid #000;
            margin-top: 10px;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .w-100 {
            width: 100%;
        }
    </style>
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
        <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">LAPORAN KERUSAKAN</h2>
        <h2 class="h4 text-center" style="font-weight: bold; margin-top:0px">
            Periode : {{ \Carbon\Carbon::parse($tgl[0])->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tgl[1])->translatedFormat('d F Y') }}
        </h2>
        <table class="table table-bordered datatable w-100">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>No Laporan</th>
                    <th>Tanggal</th>
                    <th>Pelapor</th>
                    <th>Barang</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Keterangan</th>
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
                        <td>{{ \Carbon\Carbon::parse($a->tgl)->translatedFormat('d F Y') }}</td>
                        <td>{{ $a->pelapor->name }}</td>
                        <td>{{ $a->barang->nama }}</td>
                        <td>{{ $a->barang->lokasi->nama }}</td>
                        <td>{{ $a->status }}</td>
                        <td>{{ $a->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>