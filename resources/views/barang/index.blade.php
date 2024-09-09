<x-app-layout>

    <div class="content">

        <div class="content-heading d-flex justify-content-between align-items-center">

            <span>Data Inventaris</span>

            <div class="space-x-1">
                <a href="{{ route('inventaris.create') }}" class="btn btn-sm btn-primary">
                    <i class="si si-plus me-1"></i>
                    Tambah Inventaris
                </a>
                <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-normal">
                    <i class="si si-download me-1"></i>
                    Download
                </button>
            </div>

        </div>

        <div class="block block-rounded">
            <div class="block-content p-3">
                <div class="row">
                    <div class="col-4">
                        <div class="mb-4">
                            <label for="field-kategori_id">Filter Kategori</label>
                            <select class="form-select {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}"
                                id="field-kategori_id" style="width: 100%;" name="kategori_id"
                                data-placeholder="Pilih Kategori">
                                <option></option>
                                @foreach ($kategori as $p)
                                <option value="{{ $p->id }}"  {{ (old('kategori_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                
                    <div class="col-4">
                        <div class="mb-4">
                            <label for="field-lokasi_id">Filter Lokasi</label>
                            <select class="form-select {{ $errors->has('lokasi_id') ? 'is-invalid' : '' }}"
                                id="field-lokasi_id" style="width: 100%;" name="lokasi_id"
                                data-placeholder="Pilih Lokasi">
                                <option></option>
                                @foreach ($lokasi as $p)
                                <option value="{{ $p->id }}" {{ (old('lokasi_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                
                    <div class="col-4">
                        <div class="mb-4">
                            <label for="field-tahun">Filter Tahun</label>
                            <select class="form-select {{ $errors->has('tahun') ? 'is-invalid' : '' }}"
                                id="field-tahun" style="width: 100%;" name="tahun"
                                data-placeholder="Pilih Tahun">
                                <option></option>
                                @foreach ($tahun as $p)
                                <option value="{{ $p }}" {{ (old('tahun') == $p) ? 'selected="selected"' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                

                <table class="table table-bordered datatable w-100">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>No Inventaris</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th width="60px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

    {{-- Modal --}}
    <div class="modal" id="modal-normal" tabindex="-1" aria-labelledby="modal-normal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('inventaris.export') }}" method="GET" target="_blank">
                    
                    <div class="block block-rounded shadow-none mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Download Laporan</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content fs-sm">
                            <div class="mb-4">
                                <label for="field-tgl">Tanggal Laporan</label>
                                <input type="text" class="form-control tgl" id="field-tgl" name="tgl" placeholder="Masukan Tanggal" value="">
                            </div>
                            <div class="mb-4">
                                <label for="field-status">Status Laporan</label>
                                <select class="form-control" id="field-status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="diperbaiki">Diperbaiki</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="field-lokasi_id">Filter Lokasi</label>
                                <select class="form-select {{ $errors->has('lokasi_id') ? 'is-invalid' : '' }}"
                                    id="field-lokasi_id" style="width: 100%;" name="lokasi_id"
                                    data-placeholder="Pilih Lokasi">
                                    <option value="">Semua Lokasi</option>
                                    @foreach ($lokasi as $p)
                                    <option value="{{ $p->id }}" {{ (old('lokasi_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="field-kategori_id">Filter Kategori</label>
                                <select class="form-select {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}"
                                    id="field-kategori_id" style="width: 100%;" name="kategori_id"
                                    data-placeholder="Pilih Kategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategori as $p)
                                    <option value="{{ $p->id }}"  {{ (old('kategori_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="field-tahun">Filter Tahun</label>
                                <select class="form-select {{ $errors->has('tahun') ? 'is-invalid' : '' }}"
                                    id="field-tahun" style="width: 100%;" name="tahun"
                                    data-placeholder="Pilih Tahun">
                                    {{-- <option>Semua Tahun</option> --}}
                                    @foreach ($tahun as $p)
                                    <option value="{{ $p }}" {{ (old('tahun') == $p) ? 'selected="selected"' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm text-end border-top">
                            <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-alt-primary" data-bs-dismiss="modal">Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    

    @push('scripts')

    <script>
        $('#field-tahun').select2({
            allowClear: true,
        });
        $('#field-lokasi_id').select2({
        allowClear: true,
    });
    $('#field-kategori_id').select2({
        allowClear: true,
    });
    
    $("#field-lokasi_id").on("change", function(){
        table.draw();
    });
    $("#field-tahun").on("change", function(){
        table.draw();
    });
    
    $("#field-kategori_id").on("change", function(){
        table.draw();
    });

    $("#field-tgl").flatpickr({
        altInput: true,
        altFormat: "j F Y",
        dateFormat: "Y-m-d",
        locale: "id",
        defaultDate: new Date(), // Default ke hari ini
        mode: "single" // Hanya memilih satu tanggal
    });



    var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        ajax: {
            url: "{{ route('inventaris.index') }}",
            data: function(data) {
                data.tahun = $('#field-tahun').val(); // Kirim tahun ke server
                data.lokasi_id = $('#field-lokasi_id').val();
                data.kategori_id = $('#field-kategori_id').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nomor', name: 'nomor'},
            {data: 'nama', name: 'nama'},
            {data: 'kategori.nama', name: 'kategori.nama'},
            {data: 'lokasi.nama', name: 'lokasi.nama'},
            {data: 'tahun', name: 'tahun'},
            {data: 'status', name: 'status'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true
            },
        ]
    });

       function hapus(id){
            Swal.fire({
                icon : 'warning',
                text: 'Hapus Data?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: `Tidak, Jangan!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/inventaris/"+id+"/delete",
                        type: "DELETE",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            if(data.fail == false){
                                Swal.fire({
                                    toast : true,
                                    title: "Berhasil",
                                    text: "Data Berhasil Dihapus!",
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
                                    text: "Data Gagal Dihapus!",
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



