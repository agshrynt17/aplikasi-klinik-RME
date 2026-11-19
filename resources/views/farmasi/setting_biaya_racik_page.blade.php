@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    .konhakes-title-box {
        padding-bottom: 8px;
        margin-bottom: 12px;
        position: relative;
    }
    .konhakes-title-box h3 {
        font-size: 20px;
        font-weight: 500;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .konhakes-cyan-line {
        height: 2px;
        background-color: #00c0ef;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .btn-prof-tambah-racik {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 3px;
    }
    .btn-prof-tambah-racik:hover {
        background-color: #00acd6;
        color: #fff;
    }
    .btn-prof-save {
        background-color: #00a65a;
        border-color: #008d4c;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 20px;
        border-radius: 3px;
    }
    .btn-prof-save:hover {
        background-color: #008d4c;
        color: #fff;
    }
    .btn-prof-batal {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 20px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-prof-batal:hover {
        background-color: #d68407;
        color: #fff;
    }
</style>

<div class="container-fluid px-3 py-2">
    <!-- Header Title & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center konhakes-title-box">
        <h3>Setting Biaya Racik Penjamin : <span class="text-primary">{{ $selectedPenjamin->nama_penjamin ?? '-' }}</span></h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a> <span class="mx-1">&gt;</span> <a href="/hargaobat/index">Setting Harga</a> <span class="mx-1">&gt;</span> <a href="{{ url()->current() }}">Setting Biaya Racik</a>
        </div>
    </div>
    <div class="konhakes-cyan-line"></div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 4px; border-top: 4px solid #00c0ef;">
        <div class="card-body p-4">
            <form action="/hargaobat/{{ $selectedPenjamin->id }}/biaya-racik" method="POST" id="formBiayaRacikPage">
                @csrf

                <!-- Pilih / Nama Penjamin -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <label class="form-label mb-1 fw-normal" style="font-size: 13px; color: #333;">Pilih Penjamin</label>
                        <select class="form-select form-select-sm" id="selectPenjamin" onchange="location.href='/hargaobat/biaya-racik?penjamin_id=' + this.value;">
                            @foreach($penjamins as $p)
                                <option value="{{ $p->id }}" {{ $selectedPenjamin->id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_penjamin }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tombol Tambah Komponen -->
                <div class="mb-3">
                    <button type="button" class="btn btn-prof-tambah-racik" id="btnTambahKomponenPage">
                        <i class="fas fa-plus me-1"></i> Tambah Komponen Biaya Racik
                    </button>
                </div>

                <!-- Table Komponen Biaya Racik -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0 align-middle">
                        <thead style="background-color: #f4f6f9;">
                            <tr>
                                <th width="6%" class="text-center" style="font-size: 12.5px;">#</th>
                                <th width="50%" style="font-size: 12.5px;">Nama Tarif</th>
                                <th width="38%" style="font-size: 12.5px;">Biaya (Rp)</th>
                                <th width="6%" class="text-center" style="font-size: 12.5px;"></th>
                            </tr>
                        </thead>
                        <tbody id="komponenRacikPageBody">
                            @foreach($komponen as $index => $k)
                            <tr class="komponen-row">
                                <td class="text-center text-muted row-number">{{ $index + 1 }}</td>
                                <td>
                                    <input type="hidden" name="komponen[{{ $index }}][id]" value="{{ $k->id }}">
                                    <input type="text" name="komponen[{{ $index }}][nama_tarif]" class="form-control form-control-sm" value="{{ $k->nama_tarif }}" placeholder="Nama Tarif" style="font-size: 13px;" required>
                                </td>
                                <td>
                                    <input type="number" min="0" name="komponen[{{ $index }}][biaya]" class="form-control form-control-sm" value="{{ round($k->biaya) }}" placeholder="0" style="font-size: 13px;" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 btnHapusKomponenPage" title="Hapus">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between pt-2 border-top">
                    <button type="submit" class="btn btn-prof-save">
                        Simpan Perubahan
                    </button>
                    <a href="/hargaobat/index" class="btn btn-prof-batal">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btnTambahKomponenPage').on('click', function() {
        let count = $('#komponenRacikPageBody tr.komponen-row').length;
        let newRow = `
            <tr class="komponen-row">
                <td class="text-center text-muted row-number">${count + 1}</td>
                <td>
                    <input type="hidden" name="komponen[${count}][id]" value="">
                    <input type="text" name="komponen[${count}][nama_tarif]" class="form-control form-control-sm" value="" placeholder="Nama Tarif Baru" style="font-size: 13px;" required>
                </td>
                <td>
                    <input type="number" min="0" name="komponen[${count}][biaya]" class="form-control form-control-sm" value="0" placeholder="0" style="font-size: 13px;" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 btnHapusKomponenPage" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#komponenRacikPageBody').append(newRow);
    });

    $(document).on('click', '.btnHapusKomponenPage', function() {
        $(this).closest('tr').remove();
        $('#komponenRacikPageBody tr.komponen-row').each(function(i) {
            $(this).find('.row-number').text(i + 1);
            $(this).find('input').each(function() {
                let name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/komponen\[\d+\]/, `komponen[${i}]`));
                }
            });
        });
    });
});
</script>
@endsection
