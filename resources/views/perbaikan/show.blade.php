<x-app-layout>



    @push('styles')

    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">

    @endpush



    <div class="content">

        <div class="content-heading d-flex justify-content-between align-items-center">

            <span>Detail Perbaikan</span>

            <div class="space-x">

                @if ($data->status == 0 && in_array(auth()->user()->level,['admin', 'eksekutor']))
                <button class="btn btn-success btn-sm me-1" type="button" onclick="konfirmasi('selesai')">
                    <i class="si si-check me-1"></i> Perbaikan Selesai
                </button>

                <button class="btn btn-danger btn-sm me-1" type="button" onclick="konfirmasi('rusak')">
                    <i class="si si-close me-1"></i> Tidak Bisa Diperbaiki
                </button>
                @endif

                @if ($data->status == 0 && in_array(auth()->user()->level,['admin']))

                    <a href="{{ route('maintenance.edit', $data->id) }}" class="btn btn-primary btn-sm">

                        <i class="si si-note me-1"></i> Ubah

                    </a>

                    <button class="btn btn-danger btn-sm me-1" type="button" id="hapus">

                        <i class="si si-trash me-1"></i> Hapus

                    </button>

                @endif

            </div>

        </div>

        <div class="block block-rounded">

            <div class="block-content">

                <h2>{{ $data->nomor }}</h2>



                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">No Laporan</label>

                    <div class="col-sm-6">

                        : <a href="{{ route('crash.show', $data->crash_id) }}">{{ $data->kerusakan->nomor }}</a>

                    </div>

                </div>

                
                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">Barang</label>

                    <div class="col-sm-6">

                        : {{ $data->kerusakan->barang->nama }}

                    </div>

                </div>
                
                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">Status Barang</label>

                    <div class="col-sm-6">

                        : {{ $data->kerusakan->barang->status }}

                    </div>

                </div>


                @if (auth()->user()->level != 'eksekutor')

                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">Eksekutor</label>

                    <div class="col-sm-6">

                        :{{ $data->eksekutor->name }}

                    </div>

                </div>

                @endif

                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">Tanggal Mulai</label>

                    <div class="col-sm-6">

                        : <span>{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('l, d F Y') }}</span>

                    </div>

                </div>

                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">Target Selesai</label>

                    <div class="col-sm-6">

                        : <span>{{ \Carbon\Carbon::parse($data->target)->translatedFormat('l, d F Y') }}</span>

                    </div>

                </div>

                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">Status</label>

                    <div class="col-sm-6">

                        : 

                        @if($data->status == 0)

                            <span class="badge bg-warning">Pending</span>

                        @elseif ($data->status == 1)

                            <span class="badge bg-success">Selesai</span>

                        @endif

                    </div>

                </div>

                @if ($data->status == 1)

                <div class="row mb-2">

                    <label class="col-sm-2 fw-medium">Tanggal Selesai</label>

                    <div class="col-sm-6">

                        : <span>{{ \Carbon\Carbon::parse($data->tgl_selesai)->translatedFormat('l, d F Y') }}</span>

                    </div>

                </div>

                @endif



                

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
            Swal.fire({
                icon : 'warning',
                text: 'Konfirmasi Status Perbaikan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: `Tidak, Belum!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('maintenance.confirm', $data->id) }}",
                        type: "POST",
                        data : {
                            status : status
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

                        success: function(data) {

                            if(data.fail == false){

                                Swal.fire({

                                    toast : true,

                                    title: "Berhasil",

                                    text: "Status Perbaikan Berhasil Diperbaharui!",

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

                                    text: "Status Perbaikan Gagal Diperbaharui!",

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



        

        $("#hapus").on('click', function(e){

            Swal.fire({

                icon : 'warning',

                text: 'Hapus Data Perbaikan?',

                showCancelButton: true,

                confirmButtonText: 'Ya, Hapus!',

                cancelButtonText: `Tidak, Jangan!`,

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: "{{ route('maintenance.delete', $data->id) }}",

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

                                    window.location.replace("{{ route('maintenance.index') }}");

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

        });

    </script>

    @endpush

</x-app-layout>



