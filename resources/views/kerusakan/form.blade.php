<x-app-layout>

    @push('styles')
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>{{ isset($data) ? 'Edit Laporan Kerusakan' : 'Tambah Laporan Kerusakan' }}</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ isset($data) ? route('crash.update', $data->id) : route('crash.store') }}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-tgl">Tanggal</label>
                        <div class="col-sm-6">
                            <input type="text"
                                class="form-control tgl {{ $errors->has('tgl') ? 'is-invalid' : '' }}"
                                id="field-tgl" name="tgl" placeholder="Masukan Tanggal"
                                value="{{ ($data->tgl) ?? '' }}">
                            <x-input-error :messages="$errors->get('tgl')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-lokasi_id">Lokasi</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('lokasi_id') ? 'is-invalid' : '' }}"
                                id="field-lokasi_id" style="width: 100%;" name="lokasi_id"
                                data-placeholder="Pilih Lokasi">
                                <option></option>
                                @foreach ($lokasi as $p)
                                <option value="{{ $p->id }}"
                                    {{ (old('lokasi_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('lokasi_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-kategori_id">Kategori</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}" 
                                id="field-kategori_id" style="width: 100%;" name="kategori_id" disabled>
                            </select>
                            <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-barang_id">Barang</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('barang_id') ? 'is-invalid' : '' }}" 
                                id="field-barang_id" style="width: 100%;" name="barang_id" disabled>
                            </select>
                            <x-input-error :messages="$errors->get('barang_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-keterangan">Keterangan</label>
                        <div class="col-sm-6">
                            <textarea class="form-control {{ $errors->has('keterangan') ? 'is-invalid' : '' }}"
                                id="field-keterangan" name="keterangan"
                                placeholder="Masukan Keterangan">{{ ($data->keterangan) ?? '' }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        // $(document).ready(function() {
        $('#field-lokasi_id').select2();
        
        $('#field-lokasi_id').on('change', function(e){
            if($(this).val() != ""){
                $('#field-kategori_id').prop('disabled', false);
            }else{
                $('#field-kategori_id').prop('disabled', true);
            }
            $("#field-kategori_id").val('').trigger('change');
            $("#field-barang_id").val('').trigger('change');
        });

        
        $('#field-kategori_id').on('change', function(e){
            if($(this).val() != ""){
                $('#field-barang_id').prop('disabled', false);
            }else{
                $('#field-barang_id').prop('disabled', true);
            }
            $("#field-barang_id").val('').trigger('change');
        });

        $('#field-barang_id').select2({
            placeholder: 'Pilih Barang',
            ajax: {
                url: '{{ route("inventaris.select") }}',
                dataType: 'JSON',
                delay: 250,
                data: function (params) {
                    var lokasi_id = $('#field-lokasi_id').val();
                    var kategori_id = $('#field-kategori_id').val();
                    var query = {
                        search: params.term,
                        id: lokasi_id,
                        kategori : kategori_id,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        
        $('#field-kategori_id').select2({
            placeholder: 'Pilih Kategori',
            ajax: {
                url: '{{ route("kategori.select") }}',
                dataType: 'JSON',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });
        // });
        $(".tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
        }); 
    </script>
    @endpush
</x-app-layout>

