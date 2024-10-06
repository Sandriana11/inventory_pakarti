<x-app-layout>

    <div class="content">
        <div class="row">
            <div class="col-4">
                <div class="mb-4">
                    <label for="field-filter">Filter Tanggal</label>
                    <input type="text"
                        class="form-control filter" id="field-filter" name="filter" placeholder="Masukan Tanggal" value="">
                </div>
            </div>
        </div>
        @if (auth()->user()->level == 'admin')
        <div class="row">
            <!-- Row #1 -->
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-hammer fa-2x text-primary-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-primary">{{ $ovr['crash'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Total Kerusakan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="si si-reload fa-2x text-earth-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-earth">{{ $ovr['proses'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Sedang Perbaikan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="si si-check fa-2x text-elegance-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-elegance">{{ $ovr['selesai'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Selesai Perbaikan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-3">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-user-doctor fa-2x text-pulse"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-pulse">{{ $ovr['eksekutor'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Maintener</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- END Row #1 -->
        </div>
        @else
        <div class="row">
            <!-- Row #1 -->
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fa fa-hammer fa-2x text-primary-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-primary">{{ $ovr['crash'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Total Kerusakan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="si si-reload fa-2x text-earth-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-earth">{{ $ovr['proses'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Sedang Perbaikan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-4">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="si si-check fa-2x text-elegance-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-elegance">{{ $ovr['selesai'] }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Selesai Perbaikan</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="content">

                <div class="content-heading d-flex justify-content-between align-items-center">
        
                    <span>Data Inventaris</span>       
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
                                    <th>Deskripsi</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Pengguna</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
        
                    </div>
        
                </div>
        
            </div>
            <!-- END Row #1 -->
        </div>
        @endif
    </div>


    
    @push('scripts')

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

        
        $("#field-filter").on("change", function(){
            var val = $(this).val().split(" - ");
            if(val.length > 1){
                window.location.href = "{{ route('dashboard') }}"+ `?start=${val[0]}&end=${val[1]}`;
            }
        });

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
            url: "{{ route('dashboard') }}",
            data: function(data) {
                data.tahun = $('#field-tahun').val(); // Kirim tahun ke server
                data.lokasi_id = $('#field-lokasi_id').val();
                data.kategori_id = $('#field-kategori_id').val();
                data.status = $('#field-status').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nomor', name: 'nomor'},
            {data: 'nama', name: 'nama'},
            {data: 'deskripsi', name: 'deskripsi'},
            {data: 'kategori.nama', name: 'kategori.nama'},
            {data: 'lokasi.nama', name: 'lokasi.nama'},
            {data: 'user', name: 'user'},
            {data: 'tahun', name: 'tahun'},
            {data: 'status', name: 'status'},
        ]
    });

    </script>
    @endpush

</x-app-layout>
