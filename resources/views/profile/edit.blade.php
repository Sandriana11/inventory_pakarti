<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Ubah Profil</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ route('profile.update')}}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nip">NIP</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nip') ? 'is-invalid' : '' }}"
                                id="field-nip" name="nip" placeholder="Masukan NIP" value="{{ ($data->nip) ?? '' }}">
                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Lengkap</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                id="field-nama" name="nama" placeholder="Masukan Nama Lengkap">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-bidang">Bidang</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('bidang') ? 'is-invalid' : '' }}"
                                id="field-bidang" name="bidang" placeholder="Masukan Bidang/Jabatan"
                                value="{{ ($data->bidang_id) ?? '' }}">
                            <x-input-error :messages="$errors->get('bidang')" class="mt-2" />
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
                        <label class="col-sm-3 col-form-label" for="field-username">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                id="field-username" name="username" placeholder="Masukan Username">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

