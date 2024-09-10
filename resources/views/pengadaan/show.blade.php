<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    <!-- Tambahkan ini di bagian <head> atau sebelum </body> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Detail Pengadaan</span>
            <div class="space-x">
                @if ($data->status == 'draft' && in_array(auth()->user()->level, ['admin']))
                    <!-- Form untuk Setuju -->
                    <form id="form-setuju" action="{{ route('pengadaan.confirm', $data->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="setuju">
                        <button class="btn btn-primary btn-sm me-1" type="button" onclick="confirmAction('setuju')">
                            <i class="fa fa-check me-1"></i> Setuju
                        </button>
                    </form>
        
                    <!-- Form untuk Tolak -->
                    <form id="form-tolak" action="{{ route('pengadaan.confirm', $data->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="tolak">
                        <button class="btn btn-danger btn-sm me-1" type="button" onclick="confirmAction('tolak')">
                            <i class="fa fa-close me-1"></i> Tolak
                        </button>
                    </form>
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

   @if(session('success'))
        <script>
            Swal.fire({
                toast: true,
                title: "Berhasil",
                text: "{{ session('success') }}",
                timer: 1500,
                showConfirmButton: false,
                icon: 'success',
                position: 'top-end'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                toast: true,
                title: "Gagal",
                text: "{{ session('error') }}",
                timer: 1500,
                showConfirmButton: false,
                icon: 'error',
                position: 'top-end'
            });
        </script>
    @endif



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

        function confirmAction(status) {
            var title = status === 'setuju' ? 'Setuju Pengadaan Barang?' : 'Tolak Pengadaan Barang?';
            var confirmText = status === 'setuju' ? 'Ya, Setuju!' : 'Ya, Tolak!';
            var formId = status === 'setuju' ? 'form-setuju' : 'form-tolak';

            Swal.fire({
                icon: 'warning',
                text: title,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Tidak, Batal!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();  // Submit form jika pengguna menekan tombol 'Ya'
                }
            });
        }


    </script>
    @endpush
</x-app-layout>

