<x-app-layout>

    <div class="content">

        <div class="content-heading d-flex justify-content-between align-items-center">

            <span>Data Inventaris</span>

            <div class="space-x-1">

                <a href="{{ route('inventaris.create') }}" class="btn btn-sm btn-primary">

                    <i class="si si-plus me-1"></i>

                    Tambah Inventaris

                </a>

            </div>

        </div>

        <div class="block block-rounded">

            <div class="block-content p-3">

                <div class="row">

                    <div class="col-4">

                        <div class="mb-4">

                            <label for="field-kategori_id">Filter Kategori</label>

                            <select class="form-select  {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}"

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

                            <select class="form-select  {{ $errors->has('lokasi_id') ? 'is-invalid' : '' }}"

                                id="field-lokasi_id" style="width: 100%;" name="lokasi_id"

                                data-placeholder="Pilih Lokasi">

                                <option></option>

                                @foreach ($lokasi as $p)

                                <option value="{{ $p->id }}" {{ (old('lokasi_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>

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

    

    @push('scripts')

    <script>

        $('#field-lokasi_id').select2({

            allowClear : true,

        });

        $('#field-kategori_id').select2({

            allowClear : true,

        });



        $("#field-lokasi_id").on("change", function(){

            var val = $(this).val();

            table.draw();

        });



        

        $("#field-kategori_id").on("change", function(){

            var val = $(this).val();

            table.draw();

        });



        var table = $('.datatable').DataTable({

                processing: true,

                serverSide: true,

                dom : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                ajax: {

                    url : "{{ route('inventaris.index') }}",

                    data : function(data){

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



