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

    </script>
    @endpush

</x-app-layout>
