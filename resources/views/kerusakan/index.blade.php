<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Laporan Kerusakan</span>
            <div class="space-x-1">
                @if (in_array(auth()->user()->level,['admin', 'pegawai']))
                    
                <a href="{{ route('crash.create') }}" class="btn btn-sm btn-primary me-1">
                    <i class="si si-plus me-1"></i>
                    Tambah Laporan Kerusakan
                </a>
                @endif
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
                            <label for="field-filter">Filter Tanggal</label>
                            <input type="text"
                                class="form-control filter" id="field-filter" name="filter" placeholder="Masukan Tanggal" value="">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered datatable w-100">
                    <thead>
                        <tr>
                            <th width="60px" class="text-center">#</th>
                            <th>No Laporan</th>
                            <th>Tanggal</th>
                            <th>Pelapor</th>
                            <th>Barang</th>
                            <th>Keterangan</th>
                            <th width="100px">Status</th>
                            <th width="60px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-normal" tabindex="-1" aria-labelledby="modal-normal" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('crash.export') }}" method="GET" target="_blank">
                    
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
                                <label for="field-tgl">Periode Laporan</label>
                                <input type="text"
                                    class="form-control tgl" id="field-tgl" name="tgl" placeholder="Masukan Tanggal" value="">
                            </div>
                            <div class="mb-4">
                                <label for="field-tgl">Status Laporan</label>
                                <select class="form-control" id="field-status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="proses">Proses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm text-end border-top">
                            <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-alt-primary" data-bs-dismiss="modal">
                                Download
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="/js/plugins/flatpickr/plugins/momentPlugin.js"></script>
    <script type="text/javascript">
        var tgl = "";
        
        $("#field-filter").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            defaultDate: [new Date(Date.now() - 7 * 24 * 60 * 60 * 1000), new Date()],
            mode: "range"
        });

        
        $("#field-tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
            defaultDate: [new Date(Date.now() - 7 * 24 * 60 * 60 * 1000), new Date()],
            mode: "range"
        });

        $("#field-filter").on("change", function(){
            var val = $(this).val().split(" - ");
            // console.log(val);
            // table.draw();
            if(val.length > 1){
                table.draw();
            }
        });

        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            buttons : false,
            dom : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            ajax: {
              url : "{{ route('crash.index') }}",
              data : function(data){
                    var tgl = $('#field-filter').val().split(" - ");

                    data.tgl = tgl;
                    // data.selesai = tgl[1];
              }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'nomor', name: 'nomor'},
                {data: 'tgl', name: 'tgl'},
                {data: 'pelapor.name.', name: 'pelapor.name'},
                {data: 'barang.nama', name: 'barang.nama'},
                {data: 'keterangan', name: 'keterangan'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
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

