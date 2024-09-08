<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Ubah Kategori</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form action="{{ route('kategori.update', $data->id) }}" method="post">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label" for="val-nama">Nama
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" id="val-nama" name="nama" placeholder="Masukan Nama"
                        value="{{ old('nama', $data->nama)}}">
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="val-kode">Kode
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}" id="val-kode" name="kode" placeholder="Masukan Kode"
                        value="{{ old('kode', $data->kode)}}">
                        <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-lg btn-alt-primary fw-medium w-100">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>

