@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0"><i class="fas fa-user-injured text-danger me-2"></i>Daftar Pasien Rawat Inap</h4>
            <small class="text-muted">Monitoring Pasien Sedang Dirawat, Lama Hari Inap, DPJP, &amp; Status Pelayanan</small>
        </div>
        <div class="d-flex gap-2">
            <a href="/rawat-inap/bed" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-bed me-1"></i> Visual Bed Board
            </a>
            <a href="/pendaftaran/create" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> Pendaftaran Pasien Baru
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-3 bg-white">
        <form method="GET" action="/rawat-inap/pasien" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Cari Nama Pasien / No. RM..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fas fa-filter me-1"></i> Cari</button>
                <a href="/rawat-inap/pasien" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table Pasien Ranap -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="text-center py-2" style="width: 40px;">No</th>
                        <th class="py-2">No. Registrasi &amp; RM</th>
                        <th class="py-2">Nama Pasien</th>
                        <th class="py-2">Kamar &amp; Tempat Tidur</th>
                        <th class="py-2">Dokter Penanggung Jawab (DPJP)</th>
                        <th class="text-center py-2">Waktu Masuk</th>
                        <th class="text-center py-2">Lama Inap</th>
                        <th class="text-center py-2" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasienRanap as $index => $item)
                        @php
                            $waktuMasuk = $item->waktu_masuk ?: now();
                            $lamaHari = max(1, (int)now()->diffInDays($waktuMasuk));
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $pasienRanap->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-primary">{{ $item->pendaftaran->nomor_registrasi ?? '-' }}</div>
                                <small class="text-muted">RM: {{ $item->pendaftaran->pasien->no_rm ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->pendaftaran->pasien->nama_pasien ?? 'Pasien' }}</div>
                                <span class="badge bg-secondary" style="font-size: 10px;">{{ $item->pendaftaran->penjamin->nama_penjamin ?? 'Umum/Pribadi' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_kamar }} ({{ $item->nomor_bed }})</div>
                                <small class="text-muted"><span class="badge bg-info text-dark">{{ $item->kelas }}</span> - Rp {{ number_format($item->tarif_per_hari, 0, ',', '.') }}/hari</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $item->pendaftaran->dokter->nama_pegawai ?? 'dr. Dokter Jaga' }}</div>
                            </td>
                            <td class="text-center">
                                <div>{{ $item->waktu_masuk ? $item->waktu_masuk->format('d/m/Y') : '-' }}</div>
                                <small class="text-muted">{{ $item->waktu_masuk ? $item->waktu_masuk->format('H:i') : '' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark px-2 py-1 fs-6 fw-bold">{{ $lamaHari }} Hari</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-outline-danger py-1 px-2 btnDischargeTable"
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->pendaftaran->pasien->nama_pasien ?? 'Pasien' }}"
                                        data-kamar="{{ $item->nama_kamar }} - {{ $item->nomor_bed }}"
                                        title="Pasien Pulang / Discharge">
                                    <i class="fas fa-sign-out-alt me-1"></i> Pulang
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-user-check fa-3x mb-3 text-secondary d-block"></i>
                                Tidak ada pasien yang sedang dirawat inap saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pasienRanap->hasPages())
        <div class="card-footer bg-white border-0 py-2">
            {{ $pasienRanap->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Pasien Pulang / Discharge -->
<div class="modal fade" id="modalDischargeTable" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title fw-bold"><i class="fas fa-sign-out-alt me-1"></i> Pasien Pulang / Discharge Rawat Inap</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDischargeTable">
                @csrf
                <input type="hidden" name="discharge_bed_id" id="table_discharge_bed_id">
                <div class="modal-body p-3">
                    <div class="p-2 bg-light border rounded mb-3 small">
                        <div>Nama Pasien: <strong id="table_discharge_pasien_nama">-</strong></div>
                        <div>Kamar Ranap: <strong id="table_discharge_kamar_nama">-</strong></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Pasien Pulang <span class="text-danger">*</span></label>
                        <select name="status_pulang" class="form-select form-select-sm" required>
                            <option value="Sembuh">Sembuh / Perbaikan</option>
                            <option value="Rujuk">Dirujuk ke RS Lebih Tinggi</option>
                            <option value="Atas Permintaan Sendiri">Pulang Atas Permintaan Sendiri (PAPS)</option>
                            <option value="Meninggal">Meninggal Dunia</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Catatan Pemulangan</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Kondisi keluar, obat pulang, kontrol..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-check me-1"></i> Konfirmasi Pulang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).off('click', '.btnDischargeTable').on('click', '.btnDischargeTable', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const kamar = $(this).data('kamar');
        $('#table_discharge_bed_id').val(id);
        $('#table_discharge_pasien_nama').text(nama);
        $('#table_discharge_kamar_nama').text(kamar);
        const modal = new bootstrap.Modal(document.getElementById('modalDischargeTable'));
        modal.show();
    });

    $('#formDischargeTable').off('submit').on('submit', function(e) {
        e.preventDefault();
        const id = $('#table_discharge_bed_id').val();
        const data = $(this).serialize();
        $.post('/rawat-inap/' + id + '/discharge', data, function(res) {
            if(res.success) {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Gagal discharge.');
            }
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Terjadi kesalahan sistem.');
        });
    });
});
</script>
@endsection

