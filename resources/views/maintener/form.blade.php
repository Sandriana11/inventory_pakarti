<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    @endpush
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>{{ isset($data) ? 'Edit Maintener' : 'Tambah Maintener' }}</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ isset($data) ? route('maintener.update', $data->id) : route('maintener.store') }}">
                    @csrf
                    <div class="row mb-4">
                      <label class="col-sm-3 col-form-label" for="field-tipe">Tipe</label>
                      <div class="col-sm-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="field-type1" name="tipe" value="internal" {{ (isset($data) && $data->tipe == 'internal') ? 'checked="checked"' : '' }}>
                            <label class="form-check-label" for="field-type1">Internal</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="field-type2" name="tipe" value="external" {{ (isset($data) && $data->tipe == 'external') ? 'checked="checked"' : '' }}>
                            <label class="form-check-label" for="field-type2">Eksternal</label>
                          </div>
                      </div>
                    </div>
                    <div class="row mb-4 {{ (isset($data) && $data->tipe == 'external') ? 'd-none' : '' }}" id="pegawai-row">
                        <label class="col-sm-3 col-form-label" for="field-nip">Pegawai</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('nip') ? 'is-invalid' : '' }}" id="field-nip" style="width: 100%;" name="nip" data-placeholder="Pilih Pegawai">
                                <option></option>
                                @foreach ($pegawai as $p)
                                    <option value="{{ $p->nip }}">{{ $p->nip }} - {{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                        </div>
                      </div>
                      <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Lengkap</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" 
                          id="field-nama" name="nama" placeholder="Masukan Nama Lengkap"
                          value="{{ ($data->nama) ?? '' }}">
                          <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                      </div>
                      <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-hp">No Handphone</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control {{ $errors->has('hp') ? 'is-invalid' : '' }}"
                           id="field-hp" name="hp" placeholder="Masukan No Handphone"
                           value="{{ ($data->hp) ?? '' }}">
                          <x-input-error :messages="$errors->get('hp')" class="mt-2" />
                        </div>
                      </div>
                      
                      <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-alamat">Alamat</label>
                        <div class="col-sm-6">
                           <textarea class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}"id="field-alamat" name="alamat" placeholder="Masukan Alamat">{{ ($data->alamat) ?? '' }}</textarea>
                          <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>
                      </div><div class="mb-4">
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

