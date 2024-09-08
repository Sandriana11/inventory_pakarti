<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Detail Pengadaan</span>
            <div class="space-x">
                @if ($data->status == 'draft' && in_array(auth()->user()->level,['admin']))
                    <button class="btn btn-primary btn-sm me-1" type="button" onclick="konfirmasi('setuju')">
                        <i class="fa fa-check me-1"></i> Setuju
                    </button>
                    <button class="btn btn-danger btn-sm me-1" type="button" onclick="konfirmasi('tolak')">
                        <i class="fa fa-close me-1"></i> Tolak
                    </button>
                @endif
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-4">
                <h2>{{ $data->nomor }}</h2>
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Tanggal</label>
                            <div class="col-sm-6">
                                : <span>{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Status</label>
                            <div class="col-sm-6">
                                : 
                                @if($data->status == 'draft')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($data->status == 'setuju')
                                    <span class="badge bg-primary">Setuju</span>
                                @elseif ($data->status == 'tolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Nama Barang</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->nama }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Kategori</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->kategori->nama }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Jumlah</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->qty }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Harga</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->harga }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 fw-medium">Lokasi</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->lokasi->nama }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 fw-medium">Deskripsi</label>
                            <div class="col-sm-6">
                                : <span>{{ $data->deskripsi }}</span>
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

