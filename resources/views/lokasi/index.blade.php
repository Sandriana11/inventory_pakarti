<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Departemen</span>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="block block-rounded">
                    <div class="block-content p-3">
                        <form action="{{ route('lokasi.store') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="val-nama">Nama
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" id="val-nama" name="nama" placeholder="Masukan Nama">
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>
                            <button type="submit" class="btn btn-lg btn-alt-primary fw-medium w-100">
                                Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="block block-rounded">
                    <div class="block-content p-3">
                        <table class="table table-bordered datatable w-100">
                            <thead>
                                <tr>
                                    <th width="60px">No</th>
                                    <th>Nama</th>
                                    <th width="30%">Jumlah Barang</th>
                                    <th width="60px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(function () {
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                dom : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                ajax: "{{ route('lokasi.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nama', name: 'nama'},
                    {data: 'barang_count', name: 'barang_count'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: true, 
                        searchable: true
                    },
                ]
            });
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
                        url: "/lokasi/"+id+"/delete",
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

