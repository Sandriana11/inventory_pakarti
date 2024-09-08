<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Detail Laporan Kerusakan</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <h2>{{ $data->nomor }}</h2>
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Pelapor</label>
                            <div class="col-sm-6">
                                : {{ $data->pelapor->name }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Tanggal</label>
                            <div class="col-sm-6">
                                : <span>{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Status</label>
                            <div class="col-sm-6">
                                : @if($data->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($data->status == 'proses')
                                    <span class="badge bg-primary">Sedang Perbaikan</span>
                                @elseif ($data->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Nama Barang</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->barang->nama }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Kategori</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->barang->kategori->nama }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">No Inventaris</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->barang->nomor }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Lokasi</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->barang->lokasi->nama }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 fw-medium">Keterangan</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->keterangan }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>


    @push('scripts')
    <script src="/js/plugins/select2/js/select2.full.min.js"></script>
    <script src="/js/plugins/flatpickr/flatpickr.min.js"></script>
    <script src="/js/plugins/flatpickr/l10n/id.js"></script>
    <script>
        // $(document).ready(function() {
            $('#field-pegawai_id').select2();
        // });
        $(".tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
        });
    </script>
    @endpush
</x-app-layout>

