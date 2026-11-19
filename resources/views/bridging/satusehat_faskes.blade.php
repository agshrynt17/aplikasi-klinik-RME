@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')
<style>
    /* KONHAKES 1:1 DATA FASKES SATU SEHAT */
    .faskes-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #333;
    }
    .konhakes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .konhakes-title {
        font-size: 20px;
        font-weight: 400;
        color: #333;
        margin: 0;
    }
    .konhakes-breadcrumb {
        font-size: 12px;
        color: #777;
    }
    .konhakes-breadcrumb a {
        color: #444;
        text-decoration: none;
    }
    .konhakes-breadcrumb a:hover {
        text-decoration: underline;
    }
    .card-faskes-cyan {
        background-color: #fff;
        border-top: 2px solid #00c0ef !important;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 15px 20px;
        margin-top: 10px;
    }
    .faskes-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }
    .faskes-notice-text {
        font-size: 12.5px;
        color: #8a6d3b;
        margin-bottom: 15px;
        line-height: 1.5;
    }
    .faskes-notice-text a {
        color: #337ab7;
        text-decoration: underline;
    }
    .table-faskes-detail {
        width: 100%;
        font-size: 13px;
        margin-bottom: 0;
    }
    .table-faskes-detail tr td {
        padding: 5px 0;
        vertical-align: top;
        border: none;
        color: #333;
    }
    .table-faskes-detail td.col-label {
        width: 180px;
        font-weight: 500;
        color: #333;
    }
    .table-faskes-detail td.col-colon {
        width: 18px;
        text-align: center;
        color: #333;
    }
    .table-faskes-detail td.col-val {
        color: #333;
    }
</style>

<div class="faskes-page">
    <!-- Header Title & Breadcrumb (KONHAKES 1:1) -->
    <div class="konhakes-header">
        <h3 class="konhakes-title">Bridging Satu Sehat</h3>
        <div class="konhakes-breadcrumb">
            <a href="/dashboard">Beranda</a> <span style="color: #ccc; margin: 0 2px;">&gt;</span> <a href="{{ url()->current() }}" style="color: #777;">Bridging Satu Sehat</a>
        </div>
    </div>

    <!-- Main Card Cyan (KONHAKES 1:1) -->
    <div class="card-faskes-cyan">
        <div class="faskes-section-title">Profil Klinik Terdaftar Pada Satu Sehat</div>
        <div class="faskes-notice-text">
            Profil Klinik didapat saat pendaftaran Bridging Satu Sehat pertama kali sehingga data tidak bisa diubah. Jika ingin mengubah data silakan ubah pada website satu sehat <a href="https://satusehat.kemkes.go.id/platform" target="_blank">https://satusehat.kemkes.go.id/platform</a>
        </div>

        <table class="table-faskes-detail">
            <tbody>
                <tr>
                    <td class="col-label">ID Klinik</td>
                    <td class="col-colon">:</td>
                    <td class="col-val">{{ $faskes->id ?? '100064741' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Nama Klinik</td>
                    <td class="col-colon">:</td>
                    <td class="col-val">{{ $faskes->nama ?? 'KLINIK TELAGASARI 24 JAM' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Type</td>
                    <td class="col-colon">:</td>
                    <td class="col-val">{{ $faskes->type ?? 'Organization' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Email</td>
                    <td class="col-colon">:</td>
                    <td class="col-val">{{ $faskes->email ?? 'kliniktelagasari24jam@gmail.com' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Telp</td>
                    <td class="col-colon">:</td>
                    <td class="col-val">{{ $faskes->telp ?? '(0264) 8303763' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
