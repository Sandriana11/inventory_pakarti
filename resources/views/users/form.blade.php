<x-app-layout>

    @push('styles')
    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
    @endpush

    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>{{ isset($data) ? 'Edit Pengguna' : 'Tambah Pengguna' }}</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ isset($data) ? route('user.update', $data->id) : route('user.store') }}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nip">NIP</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nip') ? 'is-invalid' : '' }}"
                                id="field-nip" name="nip" placeholder="Masukan NIP" value="{{ old('nip') }}">
                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Lengkap</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                id="field-nama" name="nama" placeholder="Masukan Nama Lengkap"
                                value="{{ old('nama') }}">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-username">Username</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                id="field-username" name="username" placeholder="Masukan Username" value="{{ old('username') }}">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-email">Email</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                id="field-email" name="email" placeholder="Masukan email" value="{{ old('email') }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-bidang_id">Bidang</label>
                        <div class="col-sm-6">
                            <select class="form-select  {{ $errors->has('bidang_id') ? 'is-invalid' : '' }}" id="field-bidang_id"
                                style="width: 100%;" name="bidang_id" data-placeholder="Pilih Bidang">
                                <option></option>
                                @foreach ($bidang as $p)
                                <option value="{{ $p->id }}" {{ (old('bidang_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('bidang_id')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-password">Password</label>
                        <div class="col-sm-6">
                            <input type="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                id="field-password" name="password" placeholder="Masukan Password"
                                value="{{ ($data->password) ?? '' }}">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-password_confirmation ">Konfirmasi
                            Password</label>
                        <div class="col-sm-6">
                            <input type="password"
                                class="form-control {{ $errors->has('password_confirmation ') ? 'is-invalid' : '' }}"
                                id="field-password_confirmation " name="password_confirmation"
                                placeholder="Masukan Konfirmasi Password"
                                value="{{ ($data->password_confirmation ) ?? '' }}">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-level">Level</label>
                        <div class="col-sm-6">
                            <select class="form-control {{ $errors->has('level ') ? 'is-invalid' : '' }}" name="level">
                                <option value="">Pilih</option>
                                <option value="admin" {{ (old('level') == "admin") ? 'selected="selected"' : '' }}>Admin</option>
                                <option value="pegawai" {{ (old('level') == "pegawai") ? 'selected="selected"' : '' }}>Pegawai</option>
                                <option value="eksekutor" {{ (old('level') == "eksekutor") ? 'selected="selected"' : '' }}>Eksekutor</option>
                            </select>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
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
        // $(document).ready(function() {
            $('#field-bidang').select2();
        // });
    </script>
    @endpush
</x-app-layout>

