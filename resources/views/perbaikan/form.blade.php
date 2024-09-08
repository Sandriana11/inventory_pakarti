<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>{{ 'Tambah Perbaikan' }}</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ route('maintenance.store') }}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-crash_id">Laporan Kerusakan</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('crash_id') ? 'is-invalid' : '' }}" id="field-crash_id" style="width: 100%;" name="crash_id" data-placeholder="Pilih Laporan">
                                <option></option>
                                @foreach ($kerusakan as $p)
                                    <option value="{{ $p->id }}" {{ (isset($data) && $data->crash_id == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nomor }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('crash_id')" class="mt-2" />
                        </div>
                      </div>
                      <div class="row mb-4">
                          <label class="col-sm-3 col-form-label" for="field-eksekutor_id">Eksekutor</label>
                          <div class="col-sm-6">
                              <select class="form-select  {{ $errors->has('eksekutor_id') ? 'is-invalid' : '' }}" id="field-eksekutor_id" style="width: 100%;" name="eksekutor_id" data-placeholder="Pilih Eksekutor">
                                  <option></option>
                                  @foreach ($eksekutor as $p)
                                      <option value="{{ $p->id }}" {{ (isset($data) && $data->eksekutor_id == $p->id) ? 'selected="selected"' : '' }}>{{ $p->name }}</option>
                                  @endforeach
                              </select>
                              <x-input-error :messages="$errors->get('eksekutor_id')" class="mt-2" />
                          </div>
                        </div>
                      <div class="row mb-4">
                          <label class="col-sm-3 col-form-label" for="field-tgl">Tanggal Mulai</label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control tgl {{ $errors->has('tgl') ? 'is-invalid' : '' }}"
                                  id="field-tgl" name="tgl" placeholder="Masukan Tanggal"
                                  value="{{ ($data->tgl) ?? '' }}">
                              <x-input-error :messages="$errors->get('tgl')" class="mt-2" />
                          </div>
                      </div>
                      <div class="row mb-4">
                          <label class="col-sm-3 col-form-label" for="field-target">Target Selesai</label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control tgl {{ $errors->has('target') ? 'is-invalid' : '' }}"
                                  id="field-target" name="target" placeholder="Masukan Target Selesai"
                                  value="{{ ($data->target) ?? '' }}">
                              <x-input-error :messages="$errors->get('target')" class="mt-2" />
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
    <script src="/js/plugins/flatpickr/flatpickr.min.js"></script>
    <script src="/js/plugins/flatpickr/l10n/id.js"></script>
    <script>
        $('#field-crash_id').select2();
        $('#field-eksekutor_id').select2();
        $(".tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
        });
    </script>
    @endpush
</x-app-layout>

