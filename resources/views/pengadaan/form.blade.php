<x-app-layout>

    @push('styles')

    <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">

    @endpush

    
    <div class="content">
        <div class="content-heading d-flex justify-content-between align-items-center">
            <span>Tambah Pengadaan</span>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <form method="POST" action="{{ route('pengadaan.store') }}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-tipe">Jenis</label>
                        <div class="col-sm-6">
                            <select class="form-control {{ $errors->has('tipe ') ? 'is-invalid' : '' }}" name="tipe" id="field-tipe" onchange="pilihTipe()">
                                <option value="">Pilih</option>
                                <option value="beli" {{ (old('tipe') == "beli") ? 'selected="selected"' : '' }}>Beli</option>
                                <option value="sewa" {{ (old('tipe') == "sewa") ? 'selected="selected"' : '' }}>Sewa</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
                        </div>
                    </div>
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
                        <label class="col-sm-3 col-form-label" for="field-supplier">Supplier Barang</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('supplier') ? 'is-invalid' : '' }}"
                                id="field-supplier" name="supplier" placeholder="Masukan Supplier Barang"
                                value="{{ old('supplier') ?? '' }}">
                            <x-input-error :messages="$errors->get('supplier')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="field-nama">Nama Barang</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                id="field-nama" name="nama" placeholder="Masukan Nama Barang"
                                value="{{ old('nama') ?? '' }}">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4" id="harga">
                        <label class="col-sm-3 col-form-label" for="field-harga">Harga</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control {{ $errors->has('harga') ? 'is-invalid' : '' }}" onchange="hitung()"
                                id="field-harga" name="harga" placeholder="Masukan Harga Barang"
                                value="{{ old('harga') ?? '' }}">
                            <x-input-error :messages="$errors->get('harga')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4" id="jml">
                        <label class="col-sm-3 col-form-label" for="field-jumlah">Jumlah Barang</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}" onchange="hitung()"
                                id="field-jumlah" name="jumlah" placeholder="Masukan Jumlah Barang"
                                value="{{ old('jumlah') ?? '' }}">
                            <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-4" id="total">
                        <label class="col-sm-3 col-form-label" for="field-total">Total Harga</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control {{ $errors->has('total') ? 'is-invalid' : '' }}"
                                id="field-total" name="total" placeholder="Masukan Total Harga Barang" readonly
                                value="{{ old('total') ?? '' }}">
                            <x-input-error :messages="$errors->get('total')" class="mt-2" />
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
                                <option value="{{ $p->id }}"  {{ (old('kategori_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
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
                                <option value="{{ $p->id }}" {{ (old('lokasi_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nama }}</option>
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
                                placeholder="Masukan Deskripsi">{{ old('deskripsi') }}</textarea>
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

        $('#field-kategori_id').select2();

        $('#field-lokasi_id').select2();

        // function pilihTipe(){
        //     var tipe = $("#field-tipe").val();
        //     if(tipe == 'beli'){
        //         $('#harga').show();
        //         $('#total').show();
        //     }else{
        //         $('#harga').hide();
        //         $('#total').hide();
        //     }
        // }

        function hitung(){
            var tipe = $("#field-tipe").val();
            if(tipe == 'beli'){
                var hrg = parseInt($("#field-harga").val());
                var jml = parseInt($("#field-jumlah").val());
                var total = hrg*jml;
                $("#field-total").val(total);
                console.log(total);
            }
        }

        $(".tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",

        });



    </script>

    @endpush

</x-app-layout>



