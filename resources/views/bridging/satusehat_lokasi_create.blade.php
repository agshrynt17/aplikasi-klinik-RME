@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .satusehat-lokasi-create-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }
    .konhakes-title {
        font-size: 19px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #337ab7 !important;
        text-decoration: none;
        cursor: pointer;
    }
    .konhakes-breadcrumb a:hover {
        color: #23527c !important;
        text-decoration: underline;
    }

    /* Card Box with Yellow/Orange Top Border */
    .card-create-box {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #f39c12 !important;
        border-radius: 3px;
        padding: 20px 20px 25px 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .form-group-custom {
        margin-bottom: 16px;
    }
    .form-group-custom label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }
    .form-group-custom .wajib-text {
        color: #dd4b39;
        font-weight: normal;
        font-style: italic;
    }
    .form-control-custom {
        width: 100%;
        height: 32px;
        padding: 4px 10px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #d2d6de;
        border-radius: 2px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }
    .form-control-custom:focus {
        border-color: #3c8dbc;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(60,141,188,.6);
    }
    .form-control-custom[readonly],
    .form-control-custom[disabled] {
        background-color: #eeeeee !important;
        cursor: not-allowed;
        color: #555;
    }

    .btn-submit-ok {
        background-color: #00a65a;
        border: 1px solid #008d4c;
        color: #fff !important;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        text-decoration: none;
    }
    .btn-submit-ok:hover {
        background-color: #008d4c;
    }

    .btn-batal-orange {
        background-color: #f39c12;
        border: 1px solid #e08e0b;
        color: #fff !important;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        text-decoration: none;
        margin-left: 5px;
    }
    .btn-batal-orange:hover {
        background-color: #d68407;
    }
</style>

<div class="satusehat-lokasi-create-page">
    {{-- Header & Breadcrumbs --}}
    <div class="konhakes-header">
        <h3 class="konhakes-title">Tambah Data Ruangan - Bridging Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <a href="/satusehat-lokasi/index">Satusehat Lokasis</a>
            <span style="color:#777; margin:0 5px;">&gt;</span>
            <span>Tambah Data Ruangan - Bridging Satu Sehat</span>
        </div>
    </div>

    {{-- Alert Error --}}
    @if ($errors->any())
    <div class="alert alert-danger py-2 px-3 small my-2" role="alert">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Card Form --}}
    <div class="card-create-box">
        <form method="POST" action="/satusehat-lokasi/store">
            @csrf
            <div class="row">
                {{-- Column 1 (Left) --}}
                <div class="col-md-4 col-sm-12">
                    {{-- Nama Tipe --}}
                    <div class="form-group-custom">
                        <label>Nama Tipe</label>
                        <input type="text" name="nama_tipe" class="form-control-custom" value="Room" readonly>
                    </div>

                    {{-- Pilih Ruangan Dari Aplikasi --}}
                    <div class="form-group-custom">
                        <label>Pilih Ruangan Dari Aplikasi <span class="wajib-text">*wajib diisi!</span></label>
                        <select name="ruangan_id" id="ruangan_id" class="form-control-custom" required>
                            <option value="">-- Pilih --</option>
                            @foreach($ruangans as $r)
                                <option value="{{ $r->id }}" data-nama="{{ $r->nama_ruangan }}">{{ $r->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="referensi_ruangan" id="referensi_ruangan" value="">
                    </div>

                    {{-- Nama Ruangan Untuk (Satu Sehat) --}}
                    <div class="form-group-custom">
                        <label>Nama Ruangan Untuk (Satu Sehat) <span class="wajib-text">*wajib diisi!</span></label>
                        <input type="text" name="nama_ruangan_satusehat" id="nama_ruangan_satusehat" class="form-control-custom" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="form-group-custom">
                        <label>Deskripsi</label>
                        <input type="text" name="deskripsi" class="form-control-custom" placeholder="Contoh: Ruang A, Lantai 1 Gedung Utama">
                    </div>

                    {{-- Telp & Fax --}}
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group-custom">
                                <label>Telp</label>
                                <input type="text" name="telp" class="form-control-custom" value="(0264) 8303763">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group-custom">
                                <label>Fax</label>
                                <input type="text" name="fax" class="form-control-custom" value="">
                            </div>
                        </div>
                    </div>

                    {{-- Email & Url --}}
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group-custom">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control-custom" value="kliniktelagasari24jam@gmail.com">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group-custom">
                                <label>Url</label>
                                <input type="text" name="url" class="form-control-custom" value="">
                            </div>
                        </div>
                    </div>

                    {{-- Submit & Batal Buttons --}}
                    <div class="pt-2 d-flex align-items-center">
                        <button type="submit" class="btn-submit-ok">
                            <i class="fas fa-check"></i> OK, Kirim Data
                        </button>
                        <a href="/satusehat-lokasi/index" class="btn-batal-orange">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>

                {{-- Column 2 (Middle) --}}
                <div class="col-md-5 col-sm-12">
                    {{-- Status & Alamat --}}
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group-custom">
                                <label>Status</label>
                                <select name="status" class="form-control-custom">
                                    <option value="Aktif" selected>Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="form-group-custom">
                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control-custom" value="Jln. Stasiun No 30 Cikampek Kota">
                            </div>
                        </div>
                    </div>

                    {{-- RT & RW --}}
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group-custom">
                                <label>RT</label>
                                <input type="text" name="rt" class="form-control-custom" value="0">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group-custom">
                                <label>RW</label>
                                <input type="text" name="rw" class="form-control-custom" value="0">
                            </div>
                        </div>
                    </div>

                    {{-- Provinsi --}}
                    <div class="form-group-custom">
                        <label>Provinsi</label>
                        <select name="provinsi" class="form-control-custom">
                            <option value="JAWA BARAT" selected>JAWA BARAT</option>
                            <option value="DKI JAKARTA">DKI JAKARTA</option>
                            <option value="BANTEN">BANTEN</option>
                            <option value="JAWA TENGAH">JAWA TENGAH</option>
                            <option value="JAWA TIMUR">JAWA TIMUR</option>
                        </select>
                    </div>

                    {{-- Kabupaten/Kota --}}
                    <div class="form-group-custom">
                        <label>Kabupaten/Kota</label>
                        <select name="kabupaten" class="form-control-custom">
                            <option value="KABUPATEN KARAWANG" selected>KABUPATEN KARAWANG</option>
                            <option value="KABUPATEN BEKASI">KABUPATEN BEKASI</option>
                            <option value="KOTA BEKASI">KOTA BEKASI</option>
                            <option value="KABUPATEN PURWAKARTA">KABUPATEN PURWAKARTA</option>
                            <option value="KABUPATEN SUBANG">KABUPATEN SUBANG</option>
                        </select>
                    </div>

                    {{-- Kecamatan --}}
                    <div class="form-group-custom">
                        <label>Kecamatan</label>
                        <select name="kecamatan" class="form-control-custom">
                            <option value="CIKAMPEK" selected>CIKAMPEK</option>
                            <option value="KOTABARU">KOTABARU</option>
                            <option value="JATISARI">JATISARI</option>
                            <option value="PURWASARI">PURWASARI</option>
                            <option value="KLARI">KLARI</option>
                        </select>
                    </div>

                    {{-- Desa/Kelurahan & Kode Pos --}}
                    <div class="row">
                        <div class="col-md-8 col-sm-8">
                            <div class="form-group-custom">
                                <label>Desa/Kelurahan</label>
                                <select name="desa" class="form-control-custom">
                                    <option value="CIKAMPEK KOTA" selected>CIKAMPEK KOTA</option>
                                    <option value="CIKAMPEK TIMUR">CIKAMPEK TIMUR</option>
                                    <option value="CIKAMPEK BARAT">CIKAMPEK BARAT</option>
                                    <option value="CIKAMPEK SELATAN">CIKAMPEK SELATAN</option>
                                    <option value="DAWUAN TENGAH">DAWUAN TENGAH</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group-custom">
                                <label>Kode Pos</label>
                                <input type="text" name="kode_pos" class="form-control-custom" value="41373">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 3 (Right) --}}
                <div class="col-md-3 col-sm-12">
                    {{-- Kordinat Longitude --}}
                    <div class="form-group-custom">
                        <label>Kordinat Longitude</label>
                        <input type="text" name="longitude" class="form-control-custom" value="0">
                    </div>

                    {{-- Kordinat Latitude --}}
                    <div class="form-group-custom">
                        <label>Kordinat Latitude</label>
                        <input type="text" name="latitude" class="form-control-custom" value="0">
                    </div>

                    {{-- Kordinat Altitude --}}
                    <div class="form-group-custom">
                        <label>Kordinat Altitude</label>
                        <input type="text" name="altitude" class="form-control-custom" value="0">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var selectRuangan = document.getElementById('ruangan_id');
    var inputRef = document.getElementById('referensi_ruangan');
    var inputSS = document.getElementById('nama_ruangan_satusehat');

    if (selectRuangan) {
        selectRuangan.addEventListener('change', function() {
            var opt = selectRuangan.options[selectRuangan.selectedIndex];
            if (opt && opt.value) {
                var nama = opt.getAttribute('data-nama') || opt.text;
                inputRef.value = nama;
                if (!inputSS.value) {
                    inputSS.value = nama;
                }
            } else {
                inputRef.value = '';
            }
        });
    }
});
</script>
@endsection

