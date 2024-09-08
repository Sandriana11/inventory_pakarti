<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    @endpush
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Tambah Inventaris</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ route('inventaris.update', $data->id) }}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Barang</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                id="field-nama" name="nama" placeholder="Masukan Nama Barang"
                                value="{{ old('nama', $data->nama) ?? '' }}">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-kategori_id">Kategori</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}"
                                id="field-kategori_id" style="width: 100%;" name="kategori_id"
                                data-placeholder="Pilih Kategori">
                                <option></option>
                                @foreach ($kategori as $p)
                                <option value="{{ $p->id }}"  {{ (old('kategori_id', $data->kategori_id) == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
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
                                <option value="{{ $p->id }}" {{ (old('lokasi_id', $data->lokasi_id) == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('lokasi_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-deskripsi">Deskripsi</label>
                        <div class="col-sm-6">
                            <textarea class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                                id="field-deskripsi" name="deskripsi"
                                placeholder="Masukan Deskripsi">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
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
    <script src="/js/plugins/select2/js/select2.full.min.js"></script>
    <script>
        var pegawai = $('#field-nip').select2();

        $('input[type=radio][name=tipe]').change(function() {
            pegawai.val(null).trigger('change');
            $('input[name=nama]').val('');
            $('input[name=hp]').val('');
            if (this.value == 'internal') {
                $('#pegawai-row').removeClass('d-none');
                $('input[name=nama]').prop('readonly', true);
                $('input[name=hp]').prop('readonly', true);
            }else{
                $('#pegawai-row').addClass('d-none');
                $('input[name=nama]').prop('readonly', false);
                $('input[name=hp]').prop('readonly', false);
            }
        });

        pegawai.on('select2:select', function (e) {
            var data = e.params.data;
            
            $.ajax({
                url: "/pegawai/"+ data.id,
                type: 'GET',
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    $('input[name=nama]').val(response.nama);
                    $('input[name=hp]').val(response.hp);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

    </script>
    @endpush
</x-app-layout>

