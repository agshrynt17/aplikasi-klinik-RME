@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', isset($barang) ? 'Edit Barang' : 'Tambah Barang')

@section('content')
<div style="background: #ffffff; margin: -20px; padding: 20px 25px; min-height: calc(100vh - 56px);">
    <!-- Header Title & Breadcrumb (1:1 PROFASKES Screenshot 2) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="font-size: 19px; font-weight: 500; color: #333; margin: 0;">
            {{ isset($barang) ? 'Edit Barang' : 'Tambah Barang' }}
        </h4>
        <div style="font-size: 11.5px; color: #777;">
            <a href="/" style="color: #777; text-decoration: none;">Beranda</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <a href="/barang/index" style="color: #777; text-decoration: none;">Barang</a>
            <span class="mx-1" style="color: #999;">&gt;</span>
            <span style="color: #999;">{{ isset($barang) ? 'Edit Barang' : 'Tambah Barang' }}</span>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 10px 15px; border-radius: 3px;">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px;"></button>
    </div>
    @endif

    <!-- Form Container (1:1 PROFASKES Screenshot 2) -->
    <form action="{{ isset($barang) ? url('/barang/' . $barang->id) : url('/barang/store') }}" method="POST">
        @csrf
        @if(isset($barang))
            @method('PUT')
        @endif

        <div class="row g-4 mb-4">
            <!-- Left Section (60%) -->
            <div class="col-lg-7 col-md-12">
                <!-- Nama Barang / Obat *wajib diisi! -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">
                        Nama Barang / Obat <span style="color: #dd4b39; font-style: italic; font-weight: normal; font-size: 11.5px;">*wajib diisi!</span>
                    </label>
                    <input type="text" name="nama_barang" class="form-control" 
                        value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" 
                        required 
                        placeholder="Ketik Nama Barang"
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                </div>

                <!-- Row 1: Jenis & Kemasan -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">
                            Jenis <span style="color: #dd4b39; font-style: italic; font-weight: normal; font-size: 11.5px;">*wajib diisi!</span>
                        </label>
                        @php $curJenis = old('jenis', $barang->jenis ?? 'Obat'); @endphp
                        <select name="jenis" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="Obat" {{ $curJenis == 'Obat' ? 'selected' : '' }}>Obat</option>
                            <option value="Alkes" {{ $curJenis == 'Alkes' ? 'selected' : '' }}>Alkes</option>
                            <option value="BMHP" {{ $curJenis == 'BMHP' ? 'selected' : '' }}>BMHP</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">
                            Kemasan <span style="color: #dd4b39; font-style: italic; font-weight: normal; font-size: 11.5px;">*wajib diisi!</span>
                        </label>
                        @php $curKemasan = old('kemasan', $barang->kemasan ?? ''); @endphp
                        <select name="kemasan" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Pilih--</option>
                            <option value="Tablet" {{ $curKemasan == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="Kaplet" {{ $curKemasan == 'Kaplet' ? 'selected' : '' }}>Kaplet</option>
                            <option value="Kapsul" {{ $curKemasan == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="Botol" {{ $curKemasan == 'Botol' ? 'selected' : '' }}>Botol</option>
                            <option value="Tube" {{ $curKemasan == 'Tube' ? 'selected' : '' }}>Tube</option>
                            <option value="Sachet" {{ $curKemasan == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                            <option value="Ampul" {{ $curKemasan == 'Ampul' ? 'selected' : '' }}>Ampul</option>
                            <option value="Vial" {{ $curKemasan == 'Vial' ? 'selected' : '' }}>Vial</option>
                            <option value="Pcs" {{ $curKemasan == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="Box" {{ $curKemasan == 'Box' ? 'selected' : '' }}>Box</option>
                            <option value="Strip" {{ $curKemasan == 'Strip' ? 'selected' : '' }}>Strip</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Kelas Terapi & Golongan Obat -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Kelas Terapi</label>
                        @php $curTerapi = old('kelas_terapi', $barang->kelas_terapi ?? ''); @endphp
                        <select name="kelas_terapi" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Pilih--</option>
                            <option value="Antiinfeksi (Antibiotik/jamur/virus)" {{ $curTerapi == 'Antiinfeksi (Antibiotik/jamur/virus)' ? 'selected' : '' }}>Antiinfeksi (Antibiotik/jamur/virus)</option>
                            <option value="Analgesik/Antipiretik" {{ $curTerapi == 'Analgesik/Antipiretik' ? 'selected' : '' }}>Analgesik/Antipiretik</option>
                            <option value="Antidiabetes" {{ $curTerapi == 'Antidiabetes' ? 'selected' : '' }}>Antidiabetes</option>
                            <option value="Antihipertensi" {{ $curTerapi == 'Antihipertensi' ? 'selected' : '' }}>Antihipertensi</option>
                            <option value="Antihistamin/Antialergi" {{ $curTerapi == 'Antihistamin/Antialergi' ? 'selected' : '' }}>Antihistamin/Antialergi</option>
                            <option value="Gastrointestinal" {{ $curTerapi == 'Gastrointestinal' ? 'selected' : '' }}>Gastrointestinal</option>
                            <option value="Kardiovaskular" {{ $curTerapi == 'Kardiovaskular' ? 'selected' : '' }}>Kardiovaskular</option>
                            <option value="Respiratorius/Saluran Nafas" {{ $curTerapi == 'Respiratorius/Saluran Nafas' ? 'selected' : '' }}>Respiratorius/Saluran Nafas</option>
                            <option value="Vitamin & Suplemen" {{ $curTerapi == 'Vitamin & Suplemen' ? 'selected' : '' }}>Vitamin & Suplemen</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Golongan Obat</label>
                        @php $curGol = old('golongan_obat', $barang->golongan_obat ?? ''); @endphp
                        <select name="golongan_obat" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Pilih Golongan Obat--</option>
                            <option value="Obat Bebas" {{ $curGol == 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                            <option value="Obat Bebas Terbatas" {{ $curGol == 'Obat Bebas Terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
                            <option value="Obat Keras" {{ $curGol == 'Obat Keras' ? 'selected' : '' }}>Obat Keras</option>
                            <option value="Psikotropika" {{ $curGol == 'Psikotropika' ? 'selected' : '' }}>Psikotropika</option>
                            <option value="Narkotika" {{ $curGol == 'Narkotika' ? 'selected' : '' }}>Narkotika</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3: Bentuk Obat & Rute Obat -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Bentuk obat</label>
                        @php $curBentuk = old('bentuk_obat', $barang->bentuk_obat ?? ''); @endphp
                        <select name="bentuk_obat" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Pilih--</option>
                            <option value="Tablet" {{ $curBentuk == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="Kaplet" {{ $curBentuk == 'Kaplet' ? 'selected' : '' }}>Kaplet</option>
                            <option value="Kapsul" {{ $curBentuk == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="Sirup" {{ $curBentuk == 'Sirup' ? 'selected' : '' }}>Sirup</option>
                            <option value="Suspensi" {{ $curBentuk == 'Suspensi' ? 'selected' : '' }}>Suspensi</option>
                            <option value="Drop" {{ $curBentuk == 'Drop' ? 'selected' : '' }}>Drop</option>
                            <option value="Salep" {{ $curBentuk == 'Salep' ? 'selected' : '' }}>Salep</option>
                            <option value="Krim" {{ $curBentuk == 'Krim' ? 'selected' : '' }}>Krim</option>
                            <option value="Gel" {{ $curBentuk == 'Gel' ? 'selected' : '' }}>Gel</option>
                            <option value="Injeksi" {{ $curBentuk == 'Injeksi' ? 'selected' : '' }}>Injeksi</option>
                            <option value="Infus" {{ $curBentuk == 'Infus' ? 'selected' : '' }}>Infus</option>
                            <option value="Suppositoria" {{ $curBentuk == 'Suppositoria' ? 'selected' : '' }}>Suppositoria</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Rute Obat</label>
                        @php $curRute = old('rute_obat', $barang->rute_obat ?? ''); @endphp
                        <select name="rute_obat" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <option value="">--Pilih--</option>
                            <option value="Oral" {{ $curRute == 'Oral' ? 'selected' : '' }}>Oral</option>
                            <option value="Topikal" {{ $curRute == 'Topikal' ? 'selected' : '' }}>Topikal</option>
                            <option value="Parenteral" {{ $curRute == 'Parenteral' ? 'selected' : '' }}>Parenteral</option>
                            <option value="Inhalasi" {{ $curRute == 'Inhalasi' ? 'selected' : '' }}>Inhalasi</option>
                            <option value="Rektal" {{ $curRute == 'Rektal' ? 'selected' : '' }}>Rektal</option>
                            <option value="Sublingual" {{ $curRute == 'Sublingual' ? 'selected' : '' }}>Sublingual</option>
                            <option value="Oftalmik" {{ $curRute == 'Oftalmik' ? 'selected' : '' }}>Oftalmik</option>
                            <option value="Otik" {{ $curRute == 'Otik' ? 'selected' : '' }}>Otik</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4: HNA, Minimum Stok, Formularium -->
                <div class="row g-3 mb-3 align-items-start">
                    <div class="col-md-4">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">
                            HNA <span style="color: #dd4b39; font-style: italic; font-weight: normal; font-size: 11.5px;">*wajib!</span>
                        </label>
                        <input type="number" name="hna" class="form-control" 
                            value="{{ old('hna', isset($barang) ? intval($barang->hna) : 0) }}" 
                            required 
                            style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <span style="font-size: 11px; color: #777;">Harga Netto Apotek</span>
                    </div>
                    <div class="col-md-4">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">
                            Minimum Stok <span style="color: #dd4b39; font-style: italic; font-weight: normal; font-size: 11.5px;">*wajib!</span>
                        </label>
                        <input type="number" name="minimum_stok" class="form-control" 
                            value="{{ old('minimum_stok', $barang->minimum_stok ?? 1) }}" 
                            required 
                            style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <span style="font-size: 11px; color: #777;">Untuk informasi stok yang dibawah minimum</span>
                    </div>
                    <div class="col-md-4">
                        <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 8px; display: block;">Formularium ?</label>
                        @php $curFormularium = old('is_formularium', $barang->is_formularium ?? 0); @endphp
                        <div class="d-flex align-items-center gap-3" style="font-size: 12.5px; padding-top: 5px;">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="is_formularium" id="formTidak" value="0" {{ !$curFormularium ? 'checked' : '' }}>
                                <label class="form-check-label" for="formTidak">Tidak</label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="is_formularium" id="formYa" value="1" {{ $curFormularium ? 'checked' : '' }}>
                                <label class="form-check-label" for="formYa">Ya</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" 
                        value="{{ old('keterangan', $barang->keterangan ?? '') }}" 
                        placeholder="Contoh: Obat Pusing"
                        style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                    <span style="font-size: 11px; color: #777;">Keterangan ini akan muncul saat membuat resep</span>
                </div>
            </div>

            <!-- Right Section (40%) -->
            <div class="col-lg-5 col-md-12">
                <!-- Status Barang/Obat -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 8px; display: block;">Status Barang/Obat</label>
                    @php $curStatus = old('status', isset($barang) ? ($barang->is_active ? 'Aktif' : 'Non Aktif') : 'Aktif'); @endphp
                    <div class="d-flex align-items-center gap-3" style="font-size: 12.5px;">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="status" id="statusAktif" value="Aktif" {{ $curStatus == 'Aktif' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="statusAktif">Aktif</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="Non Aktif" {{ $curStatus == 'Non Aktif' ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusNonAktif">Non Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Produsen -->
                <div class="mb-3">
                    <label style="font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 5px; display: block;">Produsen</label>
                    @php $curProdusen = old('produsen', $barang->produsen ?? ''); @endphp
                    <select name="produsen" class="form-select" style="height: 34px; font-size: 12.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <option value="">--Pilih--</option>
                        @foreach($produsens as $p)
                            <option value="{{ $p->nama_produsen }}" {{ $curProdusen == $p->nama_produsen ? 'selected' : '' }}>
                                {{ $p->nama_produsen }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Box MAPPING BARANG SESUAI KODE KFA SATU SEHAT (1:1 PROFASKES Screenshot 2) -->
                <div style="background: #ffffff; border: 1px solid #d2d6de; border-radius: 3px; padding: 15px;">
                    <div style="color: #337ab7; font-weight: 700; font-size: 12px; margin-bottom: 4px;">
                        MAPPING BARANG SESUAI KODE KFA SATU SEHAT
                    </div>
                    <div style="color: #dd4b39; font-size: 11px; margin-bottom: 12px;">
                        Lakukan Mapping Kode KFA agar obat bisa terkirim ke Satu Sehat, Untuk cek kode KFA bisa dari berikut : 
                        <a href="https://kfa.kemkes.go.id" target="_blank" style="color: #337ab7; text-decoration: underline;">KFA Browser</a>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-5">
                            <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Jenis Produk KFA</label>
                            @php $curKfaJenis = old('jenis_produk_kfa', $barang->jenis_produk_kfa ?? ''); @endphp
                            <select name="jenis_produk_kfa" class="form-select" style="height: 32px; font-size: 11.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                                <option value="">--Jenis Produk KFA--</option>
                                <option value="Bahan Medis Habis Pakai" {{ $curKfaJenis == 'Bahan Medis Habis Pakai' ? 'selected' : '' }}>Bahan Medis Habis Pakai</option>
                                <option value="Obat" {{ $curKfaJenis == 'Obat' ? 'selected' : '' }}>Obat</option>
                                <option value="Vaksin" {{ $curKfaJenis == 'Vaksin' ? 'selected' : '' }}>Vaksin</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Kode Produk (KFA)</label>
                            <input type="text" name="kode_kfa" class="form-control" 
                                value="{{ old('kode_kfa', $barang->kode_kfa ?? '') }}" 
                                placeholder="BZA: 91xxxxxx, POV: 92xxxxxx, POA: 93xxxxxx" 
                                style="height: 32px; font-size: 11.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                            <span style="font-size: 10.5px; color: #777; display: block; margin-top: 2px;">
                                Jenis "Vaksin" gunakan kode KFA dengan awalan 93xxxxx
                            </span>
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11.5px; font-weight: 600; color: #333; margin-bottom: 4px; display: block;">Nama Obat Sesuai KFA Satu Sehat</label>
                        <input type="text" name="nama_kfa" class="form-control" 
                            value="{{ old('nama_kfa', $barang->nama_kfa ?? '') }}" 
                            placeholder="Contoh: Obat Pusing" 
                            style="height: 32px; font-size: 11.5px; border-radius: 3px; border: 1px solid #d2d6de;">
                        <span style="font-size: 10.5px; color: #777; display: block; margin-top: 2px;">
                            Harus sesuai kode KFA Satu Sehat
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons (Simpan & Batal 1:1 PROFASKES Screenshot 2) -->
        <div class="d-flex align-items-center gap-1">
            <button type="submit" class="btn text-white" style="background-color: #00a65a; border-color: #008d4c; height: 34px; padding: 0 18px; font-size: 12.5px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center;">
                <i class="fas fa-save me-1"></i> Simpan
            </button>
            <a href="/barang/index" class="btn text-white" style="background-color: #f39c12; border-color: #e08e0b; height: 34px; padding: 0 16px; font-size: 12.5px; border-radius: 3px; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
