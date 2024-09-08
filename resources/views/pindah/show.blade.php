<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Detail Pemindahan Inventaris</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <h2>{{ $data->nomor }}</h2>
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-2">
                            <label class="col-sm-4 fw-medium">Tanggal</label>
                            <div class="col-sm-6">
                                : <span>{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-4 fw-medium">Lokasi</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->lokasi->nama }}</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 fw-medium">Jumlah Inventaris</label>
                            <div class="col-sm-6">
                                : <span>{{ count($lines) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Inventaris</th>
                            <th>Nama Barang</th>
                            <th>Lokasi Asal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($lines as $l)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $l->barang->nomor }}</td>
                                <td>{{ $l->barang->nama }}</td>
                                <td>{{ $l->lokasi->nama }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

        
       function konfirmasi(status){
            if(status == 'setuju'){
                var title = 'Setuju Pengadaan Barang?';
                var confirmText = 'Ya, Setuju!';
            }else{
                var title = 'Tolak Pengadaan Barang?';
                var confirmText = 'Ya, Tolak!';
            }
            Swal.fire({
                icon : 'warning',
                text: title,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: `Tidak, Batal!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pengadaan.confirm', $data->id )}}",
                        type: "POST",
                        data : {
                            "_token": "{{ csrf_token() }}",
                            "status" : status,
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            if(data.fail == false){
                                Swal.fire({
                                    toast : true,
                                    title: "Berhasil",
                                    text: "Data Berhasil Disimpan!",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    icon: 'success',
                                    position : 'top-end'
                                }).then((result) => {
                                    location.reload();
                                });
                            }else{
                                Swal.fire({
                                    toast : true,
                                    title: "Gagal",
                                    text: "Data Gagal Disimpan!",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    icon: 'error',
                                    position : 'top-end'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                                Swal.fire({
                                    toast : true,
                                    title: "Gagal",
                                    text: "Terjadi Kesalahan Di Server!",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    icon: 'error',
                                    position : 'top-end'
                                });
                        }
                    });
                }
            })
        }
    </script>
    @endpush
</x-app-layout>

