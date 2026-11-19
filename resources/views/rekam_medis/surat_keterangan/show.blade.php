@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="/{{ strtolower($surat->jenis_surat) }}/index" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        Detail {{ $surat->jenis_surat }}: {{ $surat->nomor_surat }}
    </h1>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Cetak
        </button>
        <a href="/{{ strtolower($surat->jenis_surat) }}/index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card card-custom p-4 shadow-sm" style="border-top: 3px solid #00c0ef;">
            @php
                $profil = \App\Models\ProfilFaskes::first();
                $namaKlinik = $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM';
                $alamatKlinik = $profil->alamat ?? 'Jl. Raya Telagasari - Kosambi No. 12, Telagasari, Kab. Karawang, Jawa Barat';
                $telpKlinik = $profil->no_telepon ?? '(0267) 510123';
            @endphp
            <!-- Header Surat -->
            <div class="text-center border-bottom pb-3 mb-4">
                <h4 class="fw-bold mb-1">{{ strtoupper($namaKlinik) }}</h4>
                <p class="text-muted mb-1" style="font-size: 12px;">{{ $alamatKlinik }} | Telp: {{ $telpKlinik }}</p>
                <div style="height: 2px; background: #333; margin: 10px auto 0; width: 100%;"></div>
            </div>

            <!-- Judul Surat -->
            <div class="text-center mb-4">
                <h5 class="fw-bold text-uppercase text-decoration-underline mb-1">
                    @if($surat->jenis_surat == 'SKB')
                        SURAT KETERANGAN BEROBAT
                    @elseif($surat->jenis_surat == 'SKD')
                        SURAT KETERANGAN DOKTER
                    @elseif($surat->jenis_surat == 'SKS')
                        SURAT KETERANGAN KESEHATAN
                    @elseif($surat->jenis_surat == 'SKH')
                        SURAT KETERANGAN HAMIL
                    @elseif($surat->jenis_surat == 'SKM')
                        SURAT KETERANGAN KEMATIAN
                    @elseif($surat->jenis_surat == 'SRE')
                        SURAT RUJUKAN EKSTERNAL
                    @elseif($surat->jenis_surat == 'SKBN')
                        SURAT KETERANGAN BEBAS NARKOBA
                    @else
                        SURAT KETERANGAN
                    @endif
                </h5>
                <p class="text-muted" style="font-size: 13px;">Nomor: {{ $surat->nomor_surat }}</p>
            </div>

            <!-- Isi Surat -->
            <div style="font-size: 13.5px; line-height: 1.8; color: #333;">
                <p>Yang bertanda tangan di bawah ini, Dokter Pemeriksa pada {{ ucwords(strtolower($namaKlinik)) }} menerangkan bahwa:</p>
                
                <table class="table table-borderless table-sm ms-3 mb-3" style="width: 85%;">
                    <tr>
                        <td width="30%"><strong>Nama Pasien</strong></td>
                        <td width="5%">:</td>
                        <td>{{ strtoupper($surat->pendaftaran->pasien->nama_pasien ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nomor RM</strong></td>
                        <td>:</td>
                        <td>{{ $surat->pendaftaran->pasien->no_rm ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td>:</td>
                        <td>{{ in_array($surat->pendaftaran->pasien->jenis_kelamin ?? '', ['L', 'Laki-Laki']) ? 'Laki-Laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Lahir</strong></td>
                        <td>:</td>
                        <td>{{ $surat->pendaftaran->pasien && $surat->pendaftaran->pasien->tanggal_lahir ? date('d-m-Y', strtotime($surat->pendaftaran->pasien->tanggal_lahir)) : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>:</td>
                        <td>{{ $surat->pendaftaran->pasien->alamat ?? 'Karawang' }}</td>
                    </tr>
                </table>

                @if($surat->mulai_tanggal && $surat->sampai_tanggal)
                    <p>Berdasarkan hasil pemeriksaan yang telah dilakukan, pasien tersebut dalam keadaan sakit dan memerlukan istirahat selama 
                    <strong>{{ (new \DateTime($surat->mulai_tanggal))->diff(new \DateTime($surat->sampai_tanggal))->days + 1 }} hari</strong>, 
                    terhitung dari tanggal <strong>{{ date('d-m-Y', strtotime($surat->mulai_tanggal)) }}</strong> s/d <strong>{{ date('d-m-Y', strtotime($surat->sampai_tanggal)) }}</strong>.</p>
                @endif

                <div class="p-3 mb-4 rounded" style="background-color: #f9f9f9; border-left: 3px solid #337ab7;">
                    <strong>Keterangan / Diagnosa / Catatan:</strong><br>
                    <span style="font-size: 13px;">{{ $surat->keterangan ?: 'Pasien telah menjalani pemeriksaan kesehatan sesuai dengan standar medis.' }}</span>
                </div>

                <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
            </div>

            <!-- Tanda Tangan -->
            <div class="row mt-5 pt-3">
                <div class="col-6"></div>
                <div class="col-6 text-center" style="font-size: 13px;">
                    <p class="mb-1">Karawang, {{ date('d F Y', strtotime($surat->tanggal_surat ?? now())) }}</p>
                    <p class="mb-5">Dokter Pemeriksa,</p>
                    <br><br>
                    <strong class="text-decoration-underline">{{ $surat->dokter->nama_pegawai ?? 'dr. Fahmi Rizal' }}</strong><br>
                    <span class="text-muted" style="font-size: 11px;">SIP: {{ $surat->dokter->no_sip ?? '503/446/SIP.D-001/DPMPTSP/2024' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
