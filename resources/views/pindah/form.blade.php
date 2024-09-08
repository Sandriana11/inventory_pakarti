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
                <form method="POST" action="{{ route('pindah.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-4">
                                <label for="field-tgl">Tanggal</label>
                                <input type="text"
                                    class="form-control tgl {{ $errors->has('tgl') ? 'is-invalid' : '' }}"
                                    id="field-tgl" name="tgl" placeholder="Masukan Tanggal"
                                    value="{{ ($data->tgl) ?? '' }}">
                                <x-input-error :messages="$errors->get('tgl')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label  for="field-lokasi_id">Lokasi</label>
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
                    </div>
                    <table class="table table-bordered" id="bahan_baku">
                        <thead>
                            <tr>
                                <th width="40%">Inventaris</th>
                                <th>Lokasi Sekarang</th>
                                <th width="100px">
                                    <button type="button" class="btn btn-primary btn-block" onclick="add_row();">
                                        Tambah
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="0" class="bb">
                                <td>
                                    <select class="form-select brg brg-0 {{ $errors->has('barang_id') ? 'is-invalid' : '' }}"
                                        id="field-barang_id" style="width: 100%;" name="line[0][barang_id]" onchange="getBarang(0)"
                                        data-placeholder="Pilih Lokasi">
                                        <option></option>
                                        @foreach ($barang as $p)
                                            <option value="{{ $p->id }}" {{ (old('barang_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nomor }} - {{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" class="form-control lokasi-0" name="line[0][lokasi_id]" value="">
                                    <input type="text" class="form-control lokasi_disp-0" value="" readonly>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
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
        $(document).ready(function() {
            $('.brg').select2();

        });
        $('#field-lokasi_id').select2();

        $(".tgl").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale : "id",
        });

        
        function getBarang(index){
            var kd_brg = $(".brg-"+index).val();
            if(kd_brg){
                $.ajax({
                    url: "/inventaris/"+kd_brg+"/json",
                    type: "GET",
                    data: {
                        id : kd_brg,
                    },
                    success: function (data){
                        $(".lokasi-"+index).val(data.lokasi_id);
                        $(".lokasi_disp-"+index).val(data.lokasi.nama);
                    }
                });
            }
        }

        function add_row()
        {
            $rowno= ($("#bahan_baku tbody tr").length == 1) ? 0 : 1;
            $rowno=$rowno+1;
            $row = "<tr id='row"+$rowno+"' class='bb'>";
            $row += `<td>
                    <select class="form-select brg brg-${ $rowno } {{ $errors->has('barang_id') ? 'is-invalid' : '' }}"
                        id="field-barang_id" style="width: 100%;" name="line[${ $rowno }][barang_id]" onchange="getBarang(${$rowno})"
                        data-placeholder="Pilih Lokasi">
                        <option></option>
                        @foreach ($barang as $p)
                            <option value="{{ $p->id }}" {{ (old('barang_id') == $p->id) ? 'selected="selected"' : '' }}>{{ $p->nomor }} - {{ $p->nama }}</option>
                        @endforeach
                    </select>
                </td>`;
            $row += `<td>
                    <input type="hidden" class="form-control lokasi-${ $rowno }" name="line[${ parseInt($rowno) }][lokasi_id]" value="">
                    <input type="text" class="form-control lokasi_disp-${ $rowno }" value="" readonly>
                </td>`;
            $row += `<td><button type="button" class="btn btn-danger" onclick=delete_row('row${ $rowno }')>Hapus</button></td>`;
            $row += "</tr>"
            $("#bahan_baku tbody tr:last").after($row);
            $('.brg').select2('destroy');
            $('.brg').select2();
        }
        function delete_row(rowno)
        {
            $('#'+rowno).remove();
        }

    </script>
    @endpush
</x-app-layout>

