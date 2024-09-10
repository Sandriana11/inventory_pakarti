<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris</title>
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
                <td width="80px">
                    <img src="images/logo1_pakarti.png" width="80px" />
                </td>
                <td class="text-center">
                    <h1 style="font-weight: bold; font-size: 12pt;">PT PAKARTI TIRTOAGUNG</h1>
                    <p class="mb-0" style="font-size: 10pt;">Jl. Raya Tengah No. 4 - Ps. Rebo Jakarta Timur Tlp. (021) 87783422 / 87783423. Faksimili (021)8403133</p>
                    <p class="mb-0" style="font-size: 10pt;">Website: www.pakarti.com Email: Info@pakarti.com</p>
                </td>
            </tr>
        </table>
        <hr />
        <h2 class="text-center" style="font-weight: bold; margin-top: 10px;">LAPORAN INVENTARIS</h2>
        @if ($status === 'rusak')
        <h2 class="text-center" style="font-weight: bold;">BARANG RUSAK</h2>
        @elseif ($status === 'diperbaiki')
        <h2 class="text-center" style="font-weight: bold;">BARANG DIPERBAIKI</h2>
        @elseif($status === 'tersedia')
        <h2 class="text-center" style="font-weight: bold;">BARANG TERSEDIA</h2>
        @endif

        <h2 class="text-center" style="font-weight: bold; margin-top: 10; margin-bottom: 10;">
            Tanggal: {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}
        </h2>
        <table class="table table-bordered w-100">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>No Inventaris</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Tahun</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $a)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $a->nomor }}</td>
                        <td>{{ $a->nama }}</td>
                        <td>{{ $a->kategori->nama }}</td>
                        <td>{{ $a->lokasi->nama }}</td>
                        <td>{{ $a->tahun }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
