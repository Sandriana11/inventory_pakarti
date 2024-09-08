<x-app-layout>
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>{{ isset($data) ? 'Edit Pegawai' : 'Tambah Pegawai' }}</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ isset($data) ? route('pegawai.update', $data->nip) : route('pegawai.store') }}">
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
                                id="field-nama" name="nama" placeholder="Masukan Nama Lengkap"
                                value="{{ ($data->nama) ?? '' }}">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-bidang">Bidang</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="bidang">
                                <option value="">Pilih</option>
                                <option value="SEKRETARIAT" {{ (isset($data) && $data->bidang == 'SEKRETARIAT') ? 'selected="selected"' : '' }}>SEKRETARIAT</option>
                                <option value="SUBAGIAN TATA USAHA" {{ (isset($data) && $data->bidang == 'SUBAGIAN TATA USAHA') ? 'selected="selected"' : '' }}>SUBAGIAN TATA USAHA</option>
                                <option value="STATISTIK" {{ (isset($data) && $data->bidang == 'STATISTIK') ? 'selected="selected"' : '' }}>STATISTIK</option>
                                <option value="PERSANDIAN DAN KEAMANAN INFORMASI" {{ (isset($data) && $data->bidang == 'PERSANDIAN DAN KEAMANAN INFORMASI') ? 'selected="selected"' : '' }}>PERSANDIAN DAN KEAMANAN INFORMASI</option>
                                <option value="INFORMASI KOMUNIKASI PUBLIK" {{ (isset($data) && $data->bidang == 'INFORMASI KOMUNIKASI PUBLIK') ? 'selected="selected"' : '' }}>INFORMASI KOMUNIKASI PUBLIK</option>
                                <option value="APLIKASI INFORMATIKA" {{ (isset($data) && $data->bidang == 'APLIKASI INFORMATIKA') ? 'selected="selected"' : '' }}>APLIKASI INFORMATIKA</option>
                                <option value="UNIT PELAYANAN TEKNIS DAERAH PUSAT LAYANAN DIGITAL DATA DAN INFORMASI" {{ (isset($data) && $data->bidang == 'UNIT PELAYANAN TEKNIS DAERAH PUSAT LAYANAN DIGITAL DATA DAN INFORMASI') ? 'selected="selected"' : '' }}>UNIT PELAYANAN TEKNIS DAERAH PUSAT LAYANAN DIGITAL DATA DAN INFORMASI</option>
                                <option value="E-GOVERNMENT" {{ (isset($data) && $data->bidang == 'E-GOVERNMENT') ? 'selected="selected"' : '' }}>E-GOVERNMENT</option>
                                <option value="GEOSPASIAL" {{ (isset($data) && $data->bidang == 'GEOSPASIAL') ? 'selected="selected"' : '' }}>GEOSPASIAL</option>
                                <option value="SUB BAGIAN KEUANGAN DAN ASET" {{ (isset($data) && $data->bidang == 'SUB BAGIAN KEUANGAN DAN ASET') ? 'selected="selected"' : '' }}>SUB BAGIAN TATA USAHA</option>
                            </select>
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
                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>

