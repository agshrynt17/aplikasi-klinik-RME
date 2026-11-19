@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('title', 'Tambah Tarif Tindakan - ' . ($profil->nama_faskes ?? 'KLINIK TELAGASARI 24 JAM'))

@section('content')
<div class="tambah-tarif-container">
    <!-- Header Area (Title and Breadcrumbs) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: 0.3px;">Tambah Tarif Tindakan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/master-tarif-tindakan" class="text-decoration-none text-muted">Tarif Tindakan</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><a href="{{ url()->current() }}" class="text-decoration-none">Tambah Tarif Tindakan</a></li>
            </ol>
        </nav>
    </div>

    <!-- Cyan Info Banner -->
    <div class="alert alert-dismissible fade show py-2 px-3 mb-3 d-flex align-items-center justify-content-between" 
         style="background-color: #00c0ef; border: none; color: #ffffff; font-size: 13px; border-radius: 3px;">
        <div>
            <i class="fas fa-info-circle me-1"></i> 
            Default tarif yang muncul dibawah ini adalah tarif pasien pribadi, jika ada perubahan tarif, silakan di edit dan klik simpan
        </div>
        <button type="button" class="btn-close btn-close-white py-2" data-bs-dismiss="alert"></button>
    </div>

    <form action="/master-tarif-tindakan" method="POST" id="formBatchTarif">
        @csrf

        <!-- Penjamin Selection Card -->
        <div class="card border rounded-1 shadow-none bg-white p-3 mb-3">
            <div style="max-width: 500px;">
                <label class="form-label text-dark mb-1" style="font-size: 13px; font-weight: 600;">
                    Silakan Pilih Penjamin Terlebih dahulu
                </label>
                <select name="penjamin_id" id="selectPenjamin" class="form-select form-select-sm" style="height: 35px; font-size: 13px;" required>
                    <option value="">--Pilih--</option>
                    @foreach($penjamins as $pj)
                        <option value="{{ $pj->id }}">{{ $pj->nama_penjamin }}</option>
                    @endforeach
                </select>
                <small class="text-danger fst-italic mt-1 d-block" style="font-size: 11px;">
                    *Penjamin yang muncul adalah penjamin yang belum pernah di lakukan setting tarif tindakan
                </small>
            </div>
        </div>

        <!-- Table of Procedures -->
        <div class="card border rounded-1 shadow-none bg-white p-0">
            <div class="table-responsive" style="max-height: 580px; overflow-y: auto;">
                <table class="table table-hover table-striped align-middle mb-0 text-nowrap" id="tabelBatchTarif" style="font-size: 12.5px;">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #e2e8f0; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th width="40" class="text-center" style="color: #333; font-weight: 600;">#</th>
                            <th style="color: #337ab7; font-weight: 600;">Poliklinik</th>
                            <th style="color: #337ab7; font-weight: 600;">Tindakan</th>
                            <th class="text-center" style="color: #337ab7; font-weight: 600; min-width: 100px;">
                                Unit Cost
                                <div class="mt-1">
                                    <label class="form-check-label d-inline-flex align-items-center gap-1" style="font-size: 10.5px; font-weight: normal; cursor: pointer;">
                                        <input type="checkbox" id="checkHapusUnitCost" class="form-check-input" style="width: 13px; height: 13px;">
                                        <span>Hapus Unit Cost</span>
                                    </label>
                                </div>
                            </th>
                            <th style="color: #337ab7; font-weight: 600; width: 140px;" class="text-center">Jasa Medis (Rp)</th>
                            <th style="color: #337ab7; font-weight: 600; width: 140px;" class="text-center">Jasa Para Medis (Rp)</th>
                            <th style="color: #337ab7; font-weight: 600; width: 140px;" class="text-center">Margin (Rp)</th>
                            <th style="color: #337ab7; font-weight: 600; width: 150px;" class="text-center">Total Tarif (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tindakans as $index => $t)
                        @php
                            $defaultMargin = isset($defaultTarifs[$t->id]) ? (int)$defaultTarifs[$t->id] : 35000;
                            $unitCost = (int)$t->unit_cost;
                            $totalAwal = $unitCost + $defaultMargin;
                        @endphp
                        <tr data-tindakan-id="{{ $t->id }}">
                            <td class="text-center fw-bold" style="color: #555;">{{ $index + 1 }}</td>
                            <td style="color: #555;">{{ $t->poliklinik ? $t->poliklinik->nama_poli : '-' }}</td>
                            <td style="color: #333; font-weight: 500;">{{ $t->nama_tindakan }}</td>
                            <td class="text-center" style="color: #333;">
                                <span class="unit-cost-label">{{ $unitCost }}</span>
                                <input type="hidden" name="items[{{ $t->id }}][unit_cost]" class="row-unit-cost" value="{{ $unitCost }}" data-original="{{ $unitCost }}">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $t->id }}][jasa_medis]" 
                                       class="form-control form-control-sm text-end row-jasa-medis calc-field" 
                                       value="0" style="height: 30px; font-size: 12px;">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $t->id }}][jasa_paramedis]" 
                                       class="form-control form-control-sm text-end row-jasa-paramedis calc-field" 
                                       value="0" style="height: 30px; font-size: 12px;">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $t->id }}][margin]" 
                                       class="form-control form-control-sm text-end row-margin calc-field" 
                                       value="{{ $defaultMargin }}" style="height: 30px; font-size: 12px;">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $t->id }}][total_tarif]" 
                                       class="form-control form-control-sm text-end row-total bg-light" 
                                       value="{{ $totalAwal }}" style="height: 30px; font-size: 12px; font-weight: 600;" readonly>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Total <strong>{{ count($tindakans) }}</strong> tindakan siap diatur tarifnya.
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm text-white px-4 fw-semibold" 
                            style="background-color: #00a65a; border-color: #008d4c; height: 34px; font-size: 13px; border-radius: 3px;">
                        <i class="fas fa-save me-1"></i> Simpan Tarif
                    </button>
                    <a href="/master-tarif-tindakan" class="btn btn-sm text-white px-4 fw-semibold d-inline-flex align-items-center" 
                       style="background-color: #f39c12; border-color: #e08e0b; height: 34px; font-size: 13px; border-radius: 3px;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    function recalcRow($tr) {
        let unitCost = parseFloat($tr.find('.row-unit-cost').val()) || 0;
        let jasaMedis = parseFloat($tr.find('.row-jasa-medis').val()) || 0;
        let jasaParamedis = parseFloat($tr.find('.row-jasa-paramedis').val()) || 0;
        let margin = parseFloat($tr.find('.row-margin').val()) || 0;
        let total = unitCost + jasaMedis + jasaParamedis + margin;
        $tr.find('.row-total').val(total);
    }

    $(document).on('input change', '.calc-field', function() {
        let $tr = $(this).closest('tr');
        recalcRow($tr);
    });

    $('#checkHapusUnitCost').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('#tabelBatchTarif tbody tr').each(function() {
            let $tr = $(this);
            let originalCost = parseFloat($tr.find('.row-unit-cost').data('original')) || 0;
            if (isChecked) {
                $tr.find('.row-unit-cost').val(0);
                $tr.find('.unit-cost-label').text('0');
            } else {
                $tr.find('.row-unit-cost').val(originalCost);
                $tr.find('.unit-cost-label').text(originalCost);
            }
            recalcRow($tr);
        });
    });

    $('#formBatchTarif').on('submit', function(e) {
        if (!$('#selectPenjamin').val()) {
            e.preventDefault();
            alert('Silakan pilih Penjamin terlebih dahulu!');
            $('#selectPenjamin').focus();
        }
    });
});
</script>
@endsection
