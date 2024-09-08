<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>List Maintener</span>
            <div class="space-x-1">
                <a href="{{ route('maintener.create') }}" class="btn btn-sm btn-primary">
                    <i class="si si-plus me-1"></i>
                    Tambah Maintener
                </a>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content p-3">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    
    @push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
       function hapus(id){
            Swal.fire({
                icon : 'warning',
                text: 'Hapus Data Perbaikan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: `Tidak, Jangan!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/maintener/"+id+"/delete",
                        type: "DELETE",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            if(data.fail == false){
                                Swal.fire({
                                    toast : true,
                                    title: "Berhasil",
                                    text: "Data Perbaikan Berhasil Dihapus!",
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
                                    text: "Data Perbaikan Gagal Dihapus!",
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

