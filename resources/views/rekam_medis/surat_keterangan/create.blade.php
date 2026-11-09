@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h1 class="page-title mb-0 border-0">
        <a href="{{ $backUrl ?? '/skb/index' }}" class="text-decoration-none text-dark">
            <i class="fas fa-arrow-left me-2 text-primary" style="font-size: 1.2rem;"></i>
        </a>
        {{ $title ?? 'Buat Surat Keterangan' }}
    </h1>
    <a href="{{ $backUrl ?? '/skb/index' }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="/surat-keterangan" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
    <input type="hidden" name="pasien_id" value="{{ $pendaftaran->pasien_id }}">
    <input type="hidden" name="jenis_surat" value="{{ $type ?? 'SKB' }}">
    
    <div class="card card-custom mb-4">
        <div class="card-header bg-light fw-bold">
            Formulir {{ $title ?? 'Surat Keterangan' }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="form-control" placeholder="Otomatis jika dikosongkan">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Jenis Surat</label>
                    <input type="text" class="form-control" value="{{ $type ?? 'SKB' }}" readonly>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Dokter Pemeriksa</label>
                    <select name="dokter_id" class="form-select">
                        <option value="">- Pilih Dokter -</option>
                        @foreach($dokters ?? [] as $dok)
                            <option value="{{ $dok->id }}" {{ old('dokter_id', $pendaftaran->dokter_id) == $dok->id ? 'selected' : '' }}>
                                {{ $dok->nama_pegawai }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(in_array(($type ?? 'SKB'), ['SKB', 'SKD']))
            <hr>
            <h6 class="fw-bold text-warning mb-3"><i class="fas fa-bed me-2"></i>Untuk Keterangan Sakit / Istirahat (Opsional)</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Mulai Tanggal</label>
                    <input type="date" name="mulai_tanggal" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control">
                </div>
            </div>
            @endif
            
            <hr>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">
                        @if(($type ?? '') == 'SRE')
                            Tujuan / Alasan Rujukan
                        @elseif(($type ?? '') == 'SKBN')
                            Hasil Pemeriksaan & Catatan Bebas Narkoba
                        @elseif(($type ?? '') == 'SKM')
                            Keterangan Penyebab Kematian / Waktu & Lokasi Meninggal
                        @elseif(($type ?? '') == 'SKH')
                            Keterangan Kehamilan (Usia Kehamilan, HPHT, Perkiraan Persalinan)
                        @else
                            Keterangan / Diagnosa / Alasan
                        @endif
                    </label>
                    <textarea name="keterangan" class="form-control" rows="5" placeholder="Isi detail keterangan..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="{{ $backUrl ?? '/skb/index' }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Surat</button>
    </div>
</form>
@endsection
