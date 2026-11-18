<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Klinik Telagasari 24 Jam - Rekam Medis Elektronik (RME) & Layanan Medis 24 Jam</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Klinik Telagasari 24 Jam - Rekam Medis Elektronik (RME) Terpadu & Layanan Medis Nonstop 24 Jam. 100% Full Paperless, Terintegrasi SATUSEHAT Kemenkes & BPJS Kesehatan (PCare, Mobile JKN, i-Care). Siap Akreditasi Paripurna!">
    <meta name="keywords" content="Klinik Telagasari, Klinik Telagasari 24 Jam, Rekam Medis Elektronik, RME Klinik, SIMRS, SatuSehat Kemenkes, PCare BPJS, Akreditasi Paripurna, Laboratorium, Radiologi, Farmasi, Karawang">
    <meta name="author" content="Klinik Telagasari 24 Jam">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Klinik Telagasari 24 Jam - Rekam Medis Elektronik Terbaik">
    <meta property="og:description" content="Layanan Kesehatan 24 Jam & Aplikasi RME Terintegrasi SATUSEHAT Kemenkes & BPJS Kesehatan.">
    <meta property="og:type" content="website">

    <!-- Google Fonts: Roboto Condensed & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons: FontAwesome 6 & Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #004aad;
            --primary-dark: #003680;
            --primary-light: #e6f0fa;
            --primary-green: #00a65a;
            --wa-green: #25D366;
            --text-dark: #222222;
            --text-muted: #555555;
            --bg-light: #f8fafc;
            --border-light: #e5e7eb;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            font-family: "Roboto Condensed", sans-serif !important;
            color: var(--text-dark);
            background-color: #ffffff;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        a, button, div, h1, h2, h3, h4, h5, h6, p, span, table, input, select, textarea {
            font-family: "Roboto Condensed", sans-serif !important;
        }

        /* Top Bar Container Wrapper */
        .site-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            position: relative;
            background-color: #ffffff;
        }

        /* ============================================================
           HEADER & NAVIGATION (Persis seperti konhakes.id di screenshot)
           ============================================================ */
        .header {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .header-middle-area {
            padding: 16px 0;
            background-color: #ffffff;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        /* Logo Klinik Telagasari 24 Jam */
        .brand-logo-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        .logo-icon-wrap {
            width: 52px;
            height: 52px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-icon-svg {
            width: 52px;
            height: 52px;
        }

        .logo-text-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-title {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--primary-blue);
            line-height: 1.05;
            text-transform: uppercase;
        }

        .logo-subtitle {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary-green);
            font-style: italic;
            letter-spacing: 0.2px;
            line-height: 1.1;
            margin-top: 2px;
        }

        /* Navigation Menu */
        .bk-navigation {
            display: flex;
            align-items: center;
        }

        .bk-nav-list {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 28px;
        }

        .bk-nav-item {
            position: relative;
        }

        .bk-nav-link {
            text-decoration: none;
            color: #222222;
            font-size: 15.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 8px 0;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s ease;
        }

        .bk-nav-link:hover, .bk-nav-link.active {
            color: var(--primary-blue);
        }

        .bk-nav-link i.fa-angle-down {
            font-size: 12px;
            transition: transform 0.2s ease;
        }

        /* Dropdown Menu */
        .bk-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: #ffffff;
            min-width: 260px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            border-radius: 8px;
            border-top: 3px solid var(--primary-blue);
            padding: 10px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: all 0.25s ease;
            z-index: 1000;
            list-style: none;
        }

        .bk-nav-item:hover .bk-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .bk-dropdown-item a {
            display: flex;
            align-items: center;
            padding: 10px 22px;
            font-size: 14.5px;
            font-weight: 600;
            color: #333333;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .bk-dropdown-item a:hover {
            background-color: var(--primary-light);
            color: var(--primary-blue);
            padding-left: 26px;
        }

        .bk-dropdown-item a i {
            width: 22px;
            margin-right: 8px;
            color: var(--primary-blue);
        }


        /* ============================================================
           HERO BANNER AREA (PERSIS SEPERTI DI SCREENSHOT KONHAKES.ID)
           ============================================================ */
        .banner-area {
            position: relative;
            background-color: #ffffff;
            overflow: hidden;
            min-height: 540px;
            display: flex;
            align-items: center;
        }

        .banner-single {
            width: 100%;
            min-height: 540px;
            display: flex;
            align-items: center;
            position: relative;
            background-size: cover;
            background-position: right center;
            background-repeat: no-repeat;
            transition: opacity 0.5s ease-in-out;
        }

        /* Mask/Gradient overlay so text is crisp on the left */
        .banner-single::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 65%;
            height: 100%;
            background: linear-gradient(90deg, #ffffff 0%, #ffffff 58%, rgba(255, 255, 255, 0.85) 80%, rgba(255, 255, 255, 0) 100%);
            z-index: 1;
        }

        .banner-content-container {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .banner-text {
            max-width: 680px;
        }

        /* 1. SOLID ROYAL BLUE BADGE (PERSIS SCREENSHOT) */
        .banner-title-badge {
            background-color: var(--primary-blue) !important;
            color: #ffffff !important;
            display: inline-block;
            font-size: 2.35rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 22px;
            line-height: 1.15;
            margin-bottom: 14px;
            border-radius: 2px;
        }

        /* 2. SUBTITLE ROYAL BLUE (PERSIS SCREENSHOT) */
        .banner-subtitle {
            background-color: transparent !important;
            color: var(--primary-blue) !important;
            font-size: 1.62rem;
            font-weight: 700;
            line-height: 1.25;
            padding: 0 !important;
            margin-bottom: 22px;
            display: block;
        }

        /* 3. MINIMALIST SLIDER ARROWS < > (PERSIS SCREENSHOT) */
        .cr-navigation-holder {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 26px;
        }

        .banner-nav-btn {
            background: transparent !important;
            border: none;
            padding: 0;
            font-size: 26px;
            color: #111111 !important;
            cursor: pointer;
            line-height: 1;
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .banner-nav-btn:hover {
            color: var(--primary-blue) !important;
            transform: scale(1.15);
        }

        /* 4. BUTTON COBA GRATIS ROYAL BLUE (PERSIS SCREENSHOT) */
        .modal-button {
            background-color: var(--primary-blue) !important;
            color: #ffffff !important;
            border-radius: 10px !important;
            padding: 13px 32px;
            font-size: 15.5px;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0, 74, 173, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .modal-button:hover {
            background-color: var(--primary-dark) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 74, 173, 0.4);
        }

        /* 5. BUTTON MASUK HERO & NAVBAR */
        .btn-secondary-login {
            background-color: transparent !important;
            color: var(--primary-blue) !important;
            border: 2px solid var(--primary-blue) !important;
            border-radius: 10px !important;
            padding: 11px 26px;
            font-size: 15.5px;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-secondary-login:hover {
            background-color: var(--primary-light) !important;
            color: var(--primary-blue) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 74, 173, 0.2);
        }

        .btn-nav-login {
            background-color: var(--primary-blue);
            color: #ffffff !important;
            padding: 8px 22px;
            border-radius: 6px;
            font-size: 14.5px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 74, 173, 0.25);
            margin-left: 20px;
        }
        .btn-nav-login:hover {
            background-color: var(--primary-dark);
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 74, 173, 0.35);
        }

        .btn-nav-dashboard {
            background-color: var(--primary-green);
            box-shadow: 0 2px 8px rgba(0, 166, 90, 0.25);
        }
        .btn-nav-dashboard:hover {
            background-color: #008d4c;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(0, 166, 90, 0.35);
        }



        /* ============================================================
           FEATUREBOX AREA (3 KOLOM PERSIS DI BAWAH HERO)
           ============================================================ */
        .featurebox-area {
            background-color: #ffffff;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #eef2f6;
        }

        .featurebox {
            padding: 48px 36px;
            height: 100%;
            border-right: 1px solid #eeeeee;
            transition: all 0.3s ease;
        }

        .featurebox:hover {
            background-color: #fafcff;
        }

        .featurebox h4 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary-blue);
            text-transform: uppercase;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }

        .featurebox p {
            font-size: 14.5px;
            color: #4b5563;
            line-height: 1.65;
            margin-bottom: 0;
        }

        .table-informasi {
            width: 100%;
            font-size: 14px;
            margin: 0 auto;
            text-align: left;
        }

        .table-informasi td {
            padding: 6px 4px;
            vertical-align: top;
            color: #374151;
        }

        .table-informasi td:first-child {
            font-weight: 700;
            width: 25%;
            color: #111827;
        }

        /* ============================================================
           KENAPA HARUS MEMILIH SECTION
           ============================================================ */
        .why-choose-area {
            padding: 75px 0;
            background: linear-gradient(135deg, #f8fbfd 0%, #eef5fc 100%);
            position: relative;
        }

        .section-title {
            margin-bottom: 35px;
        }

        .section-title h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title h2 span {
            color: var(--primary-blue);
        }

        .section-title p {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.6;
            margin-top: 8px;
        }

        .why-badge-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .why-badge-item {
            background: #ffffff;
            border-radius: 10px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 12px rgba(0, 74, 173, 0.06);
            border-left: 4px solid var(--primary-blue);
            transition: all 0.2s ease;
        }

        .why-badge-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 74, 173, 0.12);
        }

        .why-badge-icon {
            width: 36px;
            height: 36px;
            background: var(--primary-light);
            color: var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .why-badge-text {
            font-size: 15.5px;
            font-weight: 700;
            color: #1f2937;
        }

        /* ============================================================
           SERVICES & MODULES (GRID 8 KARTU LAYANAN KLINIK)
           ============================================================ */
        .services-area {
            padding: 80px 0;
            background-color: #ffffff;
        }

        .bk-service-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 36px 24px;
            text-align: center;
            transition: all 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .bk-service-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 12px 30px rgba(0, 74, 173, 0.12);
            transform: translateY(-5px);
        }

        .service-icon-wrap {
            width: 64px;
            height: 64px;
            background: var(--primary-light);
            color: var(--primary-blue);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 18px;
            transition: all 0.25s ease;
        }

        .bk-service-card:hover .service-icon-wrap {
            background: var(--primary-blue);
            color: #ffffff;
        }

        .bk-service-card h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #111827;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .bk-service-card .seemore-badge {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary-blue);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            display: inline-block;
        }

        .bk-service-card p {
            font-size: 13.5px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .bk-service-card .btn-modul-link {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--primary-blue);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.2s ease;
        }

        .bk-service-card:hover .btn-modul-link {
            gap: 9px;
            color: var(--primary-dark);
        }

        /* ============================================================
           TESTIMONIAL & AKREDITASI PARIPURNA
           ============================================================ */
        .testimonial-area {
            padding: 75px 0;
            background: #f8fafc;
        }

        .testimonial-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 32px 28px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            height: 100%;
            border-top: 4px solid var(--primary-green);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .testimonial-text {
            font-size: 14.5px;
            color: #374151;
            line-height: 1.7;
            font-style: italic;
            margin-bottom: 20px;
            position: relative;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .author-avatar {
            width: 46px;
            height: 46px;
            background: #e8f5e9;
            color: var(--primary-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
        }

        .author-info h5 {
            font-size: 15px;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        .author-info span {
            font-size: 12.5px;
            color: var(--primary-green);
            font-weight: 600;
        }

        /* ============================================================
           FAQ SECTION
           ============================================================ */
        .faq-area {
            padding: 75px 0;
            background: #ffffff;
        }

        .accordion-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px !important;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .accordion-button {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            background: #ffffff;
            padding: 18px 24px;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--primary-light);
            color: var(--primary-blue);
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: var(--primary-blue);
        }

        .accordion-body {
            font-size: 14.5px;
            color: #4b5563;
            line-height: 1.65;
            padding: 18px 24px;
        }

        /* ============================================================
           CALL TO ACTION (CTA)
           ============================================================ */
        .cta-banner-area {
            background: linear-gradient(135deg, #003680 0%, #004aad 60%, #00a65a 100%);
            color: #ffffff;
            padding: 60px 0;
            text-align: center;
        }

        .cta-banner-area h2 {
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .cta-banner-area p {
            font-size: 16px;
            opacity: 0.9;
            max-width: 650px;
            margin: 0 auto 28px;
        }

        .btn-cta-white {
            background: #ffffff;
            color: var(--primary-blue);
            font-size: 16px;
            font-weight: 800;
            padding: 13px 34px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease;
        }

        .btn-cta-white:hover {
            background: #f1f5f9;
            color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* ============================================================
           FOOTER
           ============================================================ */
        .footer {
            background-color: #111827;
            color: #9ca3af;
        }

        .footer-top {
            background-color: #0b111e;
            padding: 24px 0;
            border-bottom: 1px solid #1f2937;
        }

        .footer-social-link {
            color: #ffffff;
            background: #1f2937;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            margin-right: 10px;
            transition: all 0.2s ease;
        }

        .footer-social-link:hover {
            background: var(--primary-blue);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .footer-newsletter-form {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .footer-newsletter-form h4 {
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            margin: 0 10px 0 0;
            text-transform: uppercase;
        }

        .footer-newsletter-input {
            background: #1f2937;
            border: 1px solid #374151;
            color: #ffffff;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .footer-newsletter-input:focus {
            border-color: var(--primary-blue);
        }

        .footer-newsletter-btn {
            background: var(--primary-blue);
            color: #ffffff;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
        }

        .footer-main {
            padding: 60px 0 40px;
        }

        .footer-widget-title {
            color: #ffffff;
            font-size: 1.15rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 8px;
        }

        .footer-widget-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 36px;
            height: 2px;
            background: var(--primary-blue);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .footer-links a:hover {
            color: #ffffff;
            padding-left: 5px;
        }

        .footer-bottom {
            background-color: #080c14;
            padding: 20px 0;
            border-top: 1px solid #1f2937;
            text-align: center;
            font-size: 13.5px;
            color: #6b7280;
        }

        /* ============================================================
           FLOATING WHATSAPP BUTTON (PERSIS DI SCREENSHOT POJOK KANAN BAWAH)
           ============================================================ */
        .floating-whatsapp {
            position: fixed;
            z-index: 1112;
            right: 20px;
            bottom: 20px;
            width: 58px;
            height: 58px;
            background-color: var(--wa-green);
            border-radius: 50%;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .floating-whatsapp:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
        }

        .floating-whatsapp svg {
            width: 36px;
            height: 36px;
            fill: #ffffff;
        }

        /* Responsive Mobile Styles */
        @media (max-width: 991px) {
            .header-middle-area {
                padding: 12px 0;
            }
            .banner-single::before {
                width: 100%;
                background: rgba(255, 255, 255, 0.92);
            }
            .banner-title-badge {
                font-size: 1.8rem;
                padding: 6px 14px;
            }
            .banner-subtitle {
                font-size: 1.25rem;
            }
            .featurebox {
                border-right: none;
                border-bottom: 1px solid #eeeeee;
                padding: 32px 20px;
            }
            .footer-newsletter-form {
                justify-content: flex-start;
                margin-top: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Floating WhatsApp Button -->
    <a href="https://api.whatsapp.com/send/?phone=6281288882026&text=Halo%20Tim%20Konhakes%2C%20saya%20ingin%20informasi%20pelayanan%20dan%20RME%20Konhakes" target="_blank" class="floating-whatsapp" aria-label="Hubungi WhatsApp Konhakes">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="white" d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2c-5.46 0-9.91 4.45-9.91 9.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91c0-2.65-1.03-5.14-2.9-7.01m-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18l-3.12.82l.83-3.04l-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24c2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c.02 4.54-3.68 8.23-8.22 8.23m4.52-6.16c-.25-.12-1.47-.72-1.69-.81c-.23-.08-.39-.12-.56.12c-.17.25-.64.81-.78.97c-.14.17-.29.19-.54.06c-.25-.12-1.05-.39-1.99-1.23c-.74-.66-1.23-1.47-1.38-1.72c-.14-.25-.02-.38.11-.51c.11-.11.25-.29.37-.43s.17-.25.25-.41c.08-.17.04-.31-.02-.43s-.56-1.34-.76-1.84c-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31c-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74c.59.26 1.05.41 1.41.52c.59.19 1.13.16 1.56.1c.48-.07 1.47-.6 1.67-1.18c.21-.58.21-1.07.14-1.18s-.22-.16-.47-.28" />
        </svg>
    </a>

    <div class="site-wrapper">

        <!-- ========================================================
             HEADER / NAVBAR (PERSIS KONHAKES.ID DI SCREENSHOT)
             ======================================================== -->
        <header class="header sticky-header">
            <div class="header-middle-area">
                <div class="container">
                    <div class="header-container">
                        
                        <!-- Logo: KLINIK TELAGASARI 24 JAM -->
                        <a href="/" class="brand-logo-link">
                            <div class="logo-icon-wrap">
                                <svg class="logo-icon-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Stethoscope / Health Blue Circle -->
                                    <circle cx="50" cy="50" r="44" stroke="#004aad" stroke-width="7" fill="#ffffff" />
                                    <!-- Inner Medical Cross in Green -->
                                    <rect x="44" y="24" width="12" height="52" rx="4" fill="#00a65a" />
                                    <rect x="24" y="44" width="52" height="12" rx="4" fill="#00a65a" />
                                    <!-- Stethoscope Ear Pieces Accent -->
                                    <circle cx="34" cy="24" r="5" fill="#004aad" />
                                    <circle cx="66" cy="24" r="5" fill="#004aad" />
                                </svg>
                            </div>
                            <div class="logo-text-group">
                                <span class="logo-title">KLINIK TELAGASARI</span>
                                <span class="logo-subtitle">Layanan Medis 24 Jam</span>
                            </div>
                        </a>

                        <!-- Navigation Menu (HOME, FITUR ∨, BERLANGGANAN, ARTIKEL, KONTAK, MASUK) -->
                        <nav class="bk-navigation d-none d-lg-flex align-items-center">
                            <ul class="bk-nav-list">
                                <li class="bk-nav-item">
                                    <a href="/" class="bk-nav-link active">HOME</a>
                                </li>
                                <li class="bk-nav-item">
                                    <a href="#modul-layanan" class="bk-nav-link">
                                        FITUR <i class="fa fa-angle-down ms-1"></i>
                                    </a>
                                    <ul class="bk-dropdown-menu">
                                        <li class="bk-dropdown-item">
                                            <a href="/pendaftaran">
                                                <i class="fas fa-id-card"></i> Front Office & Antrean
                                            </a>
                                        </li>
                                        <li class="bk-dropdown-item">
                                            <a href="/pasien">
                                                <i class="fas fa-file-medical"></i> Rekam Medis (RME)
                                            </a>
                                        </li>
                                        <li class="bk-dropdown-item">
                                            <a href="/laboratorium/transaksi-lab">
                                                <i class="fas fa-vial"></i> Laboratorium Terpadu
                                            </a>
                                        </li>
                                        <li class="bk-dropdown-item">
                                            <a href="/dashboard">
                                                <i class="fas fa-x-ray"></i> Radio-Diagnostik
                                            </a>
                                        </li>
                                        <li class="bk-dropdown-item">
                                            <a href="/transaksi-kasir">
                                                <i class="fas fa-cash-register"></i> Kasir & Pembayaran
                                            </a>
                                        </li>
                                        <li class="bk-dropdown-item">
                                            <a href="/bridging-check">
                                                <i class="fas fa-heartbeat"></i> Integrasi BPJS & SATUSEHAT
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="bk-nav-item">
                                    <a href="#keunggulan" class="bk-nav-link">BERLANGGANAN</a>
                                </li>
                                <li class="bk-nav-item">
                                    <a href="#testimoni" class="bk-nav-link">ARTIKEL</a>
                                </li>
                                <li class="bk-nav-item">
                                    <a href="#kontak-area" class="bk-nav-link">KONTAK</a>
                                </li>
                            </ul>

                            @if(auth()->check())
                                <a href="/dashboard" class="btn-nav-login btn-nav-dashboard" title="Menuju Dashboard">
                                    <i class="fas fa-desktop"></i> DASHBOARD
                                </a>
                            @else
                                <a href="/login" class="btn-nav-login" title="Masuk ke Aplikasi">
                                    <i class="fas fa-sign-in-alt"></i> MASUK
                                </a>
                            @endif
                        </nav>

                        <!-- Mobile Header Button (d-lg-none) -->
                        <div class="d-flex d-lg-none align-items-center gap-2">
                            @if(auth()->check())
                                <a href="/dashboard" class="btn btn-sm btn-success fw-bold px-3 py-1" style="font-size: 13px;">
                                    <i class="fas fa-desktop"></i> Dashboard
                                </a>
                            @else
                                <a href="/login" class="btn btn-sm btn-primary fw-bold px-3 py-1" style="background-color: var(--primary-blue); border-color: var(--primary-blue); font-size: 13px;">
                                    <i class="fas fa-sign-in-alt"></i> Masuk
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </header>

        <!-- ========================================================
             HERO BANNER AREA (PERSIS SEPERTI DI SCREENSHOT)
             ======================================================== -->
        <section class="banner-area" id="hero">
            <div class="banner-single" id="sliderSingle" style="background-image: url('/landing_assets/hero-doctor.jpg');">
                <div class="container banner-content-container">
                    <div class="banner-text">
                        
                        <!-- 1. SOLID ROYAL BLUE BOX BADGE -->
                        <div class="banner-text-single">
                            <h1 class="m-0">
                                <span class="banner-title-badge" id="slideBadge">KLINIK TELAGASARI 24 JAM</span>
                            </h1>
                        </div>

                        <!-- 2. SUBTITLE ROYAL BLUE -->
                        <div class="banner-text-single">
                            <h5>
                                <span class="banner-subtitle" id="slideSubtitle">Rekam Medis Elektronik Yang Bikin Faskes NAIK KELAS !</span>
                            </h5>
                        </div>

                        <!-- 3. SLIDER ARROW CONTROLS < > -->
                        <div class="cr-navigation-holder">
                            <button type="button" aria-label="Slide Sebelumnya" class="banner-nav-btn" id="btnPrevSlide">
                                <i class="fa fa-angle-left"></i>
                            </button>
                            <button type="button" aria-label="Slide Selanjutnya" class="banner-nav-btn ms-2" id="btnNextSlide">
                                <i class="fa fa-angle-right"></i>
                            </button>
                        </div>

                        <!-- 4. ACTION BUTTONS: COBA GRATIS & MASUK APLIKASI -->
                        <div class="banner-action mt-2 d-flex flex-wrap align-items-center gap-3">
                            <button type="button" class="modal-button" data-bs-toggle="modal" data-bs-target="#modalCobaGratis">
                                <i class="fas fa-play-circle me-1"></i> Coba Gratis
                            </button>
                            <a href="/login" class="btn-secondary-login">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk Aplikasi
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================
             FEATUREBOX AREA (3 KOLOM PERSIS DI BAWAH HERO)
             ======================================================== -->
        <section class="bk-section featurebox-area bg-white">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    
                    <!-- Kolom 1: KLINIK TELAGASARI 24 JAM -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="featurebox text-center">
                            <h4 class="text-uppercase">KLINIK TELAGASARI 24 JAM</h4>
                            <p>Dirancang untuk mempercepat dan mempermudah seluruh proses akreditasi fasilitas kesehatan, sehingga tidak menjadi beban buat staf medis dan non-medis.</p>
                            <p class="mt-2">Dari jenis dan jumlah rekam medis, pendokumentasian setiap data pelayanan, hingga kesiapan bukti telusur dokumen rekam medis telah disiapkan dengan rapi dan sistematis.</p>
                        </div>
                    </div>

                    <!-- Kolom 2: KONTAK KAMI -->
                    <div class="col-lg-4 col-md-6 col-12" id="kontak-area">
                        <div class="featurebox text-center">
                            <h4 class="text-uppercase">KONTAK KAMI</h4>
                            <table class="table-informasi">
                                <tr>
                                    <td>Email</td>
                                    <td><a href="mailto:info@kliniktelagasari.com" class="text-dark fw-bold text-decoration-none">info@kliniktelagasari.com</a></td>
                                </tr>
                                <tr>
                                    <td>Whatsapp</td>
                                    <td class="w-75 fw-bold text-success">0812-8888-2026 (Konhakes)</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td style="font-size: 13.5px; line-height: 1.5;">
                                        Jl. Raya Telagasari No. 24, Kec. Telagasari, Kab. Karawang, Jawa Barat 41382.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="pt-2">
                                        Punya Pertanyaan Lain? <a href="https://api.whatsapp.com/send/?phone=6281288882026&text=Halo%20Tim%20Konhakes%2C%20saya%20ingin%20konsultasi%20layanan" target="_blank" style="color: #004aad; font-weight: 700;">Hubungi Tim Konhakes</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kolom 3: SISTEM TERINTEGRASI -->
                    <div class="col-lg-4 col-md-12 col-12">
                        <div class="featurebox text-center">
                            <h4 class="text-uppercase">SISTEM TERINTEGRASI 24 JAM</h4>
                            <p>Membuat operasional fasilitas kesehatan lebih efisien, efektif, dan terkontrol karena seluruh proses pelayanan berjalan dalam satu sistem terpusat.</p>
                            <p class="mt-2">Mengurangi pekerjaan berulang karena sudah terbridging SATU SEHAT Kemenkes, BPJS Kesehatan (PCare, Vclaim, Eclaim, Antrol, ICare) bahkan iDRG, mengurangi antrean pelayanan, dan memastikan setiap aktivitas tercatat dengan benar.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ========================================================
             KENAPA HARUS MEMILIH KLINIK TELAGASARI 24 JAM
             ======================================================== -->
        <section class="why-choose-area" id="keunggulan">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 col-12">
                        <div class="section-title">
                            <h2>Kenapa Harus <span>Memilih</span> <span>Klinik Telagasari 24 Jam</span></h2>
                            <p>Fasilitas kesehatan dan sistem informasi manajemen rekam medis elektronik kami berfokus pada kecepatan pelayanan, akurasi diagnosa medis, memenuhi standar akreditasi Kemenkes RI, serta didukung operasional 24 jam nonstop.</p>
                        </div>
                    </div>
                </div>

                <div class="why-badge-list mt-3">
                    <div class="why-badge-item">
                        <div class="why-badge-icon"><i class="fas fa-check"></i></div>
                        <div class="why-badge-text">Terintegrasi Resmi BPJS & SATUSEHAT Kemenkes</div>
                    </div>
                    <div class="why-badge-item">
                        <div class="why-badge-icon"><i class="fas fa-clock"></i></div>
                        <div class="why-badge-text">Layanan Medis & Dukungan Teknis 24 Jam Nonstop</div>
                    </div>
                    <div class="why-badge-item">
                        <div class="why-badge-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="why-badge-text">Pelatihan Komprehensif Dokter & Staf Faskes</div>
                    </div>
                    <div class="why-badge-item">
                        <div class="why-badge-icon"><i class="fas fa-award"></i></div>
                        <div class="why-badge-text">Memenuhi Semua Standar / Elemen Akreditasi Paripurna</div>
                    </div>
                    <div class="why-badge-item">
                        <div class="why-badge-icon"><i class="fas fa-network-wired"></i></div>
                        <div class="why-badge-text">Solusi Terintegrasi: Poli, IGD, Rawat Inap, Lab, Farmasi</div>
                    </div>
                    <div class="why-badge-item">
                        <div class="why-badge-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="why-badge-text">Keamanan Data Pasien Teruji & 100% Full Paperless</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================
             MODUL-MODUL LAYANAN LENGKAP (GRID 8 KARTU)
             ======================================================== -->
        <section class="services-area" id="modul-layanan">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="section-title">
                        <h2>MODUL FITUR <span>TERBAIK</span> <span>KLINIK TELAGASARI 24 JAM</span></h2>
                        <p class="mx-auto" style="max-width: 720px;">Semua tahapan pelayanan faskes dari penerimaan pasien hingga penyelesaian tagihan dan pelaporan terhubung secara otomatis.</p>
                    </div>
                </div>

                <div class="row g-4">
                    
                    <!-- 1. FRONT OFFICE -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3>FRONT-OFFICE</h3>
                                <span class="seemore-badge">LAYANAN REGISTRASI</span>
                                <p>Pendaftaran pasien rawat jalan & IGD, pencatatan General Consent digital, cetak nomor antrean otomatis, dan integrasi survei kepuasan pasien.</p>
                            </div>
                            <a href="/pendaftaran" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 2. REKAM MEDIK -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-file-medical-alt"></i>
                                </div>
                                <h3>REKAM MEDIK</h3>
                                <span class="seemore-badge">SOAP & ELEKTRONIK</span>
                                <p>Pencatatan rekam medis terstandar Permenkes 24/2022: Triase gawat darurat, CPPT dokter & perawat, Odontogram gigi, riwayat alergi, dan ICD-10.</p>
                            </div>
                            <a href="/pasien" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 3. LABORATORIUM -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-vial"></i>
                                </div>
                                <h3>LABORATORIUM</h3>
                                <span class="seemore-badge">PEMERIKSAAN & HASIL</span>
                                <p>Pengaturan master tarif lab, nilai normal rujukan, transaksi sampel, input hasil pemeriksaan analitik, verifikasi medis, hingga laporan jasa medis lab.</p>
                            </div>
                            <a href="/laboratorium/transaksi-lab" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 4. RADIOLOGI -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-x-ray"></i>
                                </div>
                                <h3>RADIOLOGI</h3>
                                <span class="seemore-badge">RADIO-DIAGNOSTIK</span>
                                <p>Permintaan foto rontgen & USG, pencatatan hasil expertise dokter spesialis radiologi, dokumentasi digital dan integrasi ke rekam medis pasien.</p>
                            </div>
                            <a href="/dashboard" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 5. FARMASI -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <h3>FARMASI & APOTEK</h3>
                                <span class="seemore-badge">MANAJEMEN OBAT</span>
                                <p>Master obat & alkes, kontrol stok real-time, penerimaan faktur, mutasi antar depo, telaah interaksi resep dokter, hingga penjualan apotek.</p>
                            </div>
                            <a href="/dashboard" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 6. RAWAT INAP -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <h3>RAWAT INAP</h3>
                                <span class="seemore-badge">BED MANAGEMENT</span>
                                <p>Registrasi rawat inap, monitoring ketersediaan tempat tidur, catatan asuhan PPA, lembar instruksi visite dokter, serta rekapitulasi tindakan.</p>
                            </div>
                            <a href="/dashboard" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 7. KASIR & KEUANGAN -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-cash-register"></i>
                                </div>
                                <h3>KASIR & BILLING</h3>
                                <span class="seemore-badge">PEMBAYARAN OTOMATIS</span>
                                <p>Penagihan otomatis tarif pendaftaran, tindakan medis, jasa dokter, pemeriksaan lab, serta obat. Cetak invoice dan kwitansi resmi langsung.</p>
                            </div>
                            <a href="/transaksi-kasir" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 8. BRIDGING SATUSEHAT & BPJS -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="bk-service-card">
                            <div>
                                <div class="service-icon-wrap">
                                    <i class="fas fa-hand-holding-medical"></i>
                                </div>
                                <h3>BRIDGING KEMENKES</h3>
                                <span class="seemore-badge">SATUSEHAT & PCARE</span>
                                <p>Koneksi otomatis SATUSEHAT Kemenkes RI (Encounter, Condition, Observation), BPJS PCare, Antrean Online Mobile JKN, dan i-Care terverifikasi.</p>
                            </div>
                            <a href="/bridging-check" class="btn-modul-link">
                                Buka Modul <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ========================================================
             TESTIMONI PARIPURNA
             ======================================================== -->
        <section class="testimonial-area" id="testimoni">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="section-title">
                        <h2>APA <span>KATA MEREKA</span></h2>
                        <p class="mx-auto" style="max-width: 650px;">Fasilitas kesehatan yang telah bermitra dan berhasil meraih Akreditasi Paripurna bersama sistem kami.</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="testimonial-card">
                            <p class="testimonial-text">"Klinik kami berhasil meraih AKREDITASI PARIPURNA berkat kemudahan sistem rekam medis elektronik Klinik Telagasari. Semua bukti telusur dokumen tersusun otomatis dan rapi!"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">U</div>
                                <div class="author-info">
                                    <h5>Klinik Ummi Nindira</h5>
                                    <span>Meraih Predikat Paripurna</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="testimonial-card">
                            <p class="testimonial-text">"Dukungan dan responsifitas tim 24 jam sangat membantu operasional faskes kami. Bridging SATUSEHAT dan PCare BPJS berjalan mulus tanpa kendala antrean."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">K</div>
                                <div class="author-info">
                                    <h5>Klinik Keysa Medika</h5>
                                    <span>Pengguna Aktif Aplikasi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="testimonial-card">
                            <p class="testimonial-text">"Alhamdulillah Klinik kami meraih predikat PARIPURNA tahun ini! Modul laboratorium dan kasirnya sangat terintegrasi memotong waktu kerja staf kami hingga 70%."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">D</div>
                                <div class="author-info">
                                    <h5>Klinik Dwi Ananda</h5>
                                    <span>Akreditasi Paripurna Faskes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================
             FAQ (PERTANYAAN YANG SERING DIAJUKAN)
             ======================================================== -->
        <section class="faq-area">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="section-title">
                        <h2>PERTANYAAN <span>YANG SERING</span> <span>DIAJUKAN (FAQ)</span></h2>
                        <p>Jawaban atas pertanyaan umum mengenai Rekam Medis Elektronik di Klinik Telagasari 24 Jam.</p>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-9 col-12">
                        <div class="accordion" id="accordionFaq">
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Apa itu aplikasi Rekam Medis Elektronik (RME) Klinik Telagasari 24 Jam?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFaq">
                                    <div class="accordion-body">
                                        Aplikasi Rekam Medis Elektronik (RME) adalah sistem informasi kesehatan terintegrasi berbasis web yang mencatat data klinis pasien, riwayat pemeriksaan dokter, hasil laboratorium, resep obat, dan tagihan kasir secara digital 100% tanpa kertas (paperless), sesuai regulasi Permenkes No. 24 Tahun 2022.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Apakah sistem ini sudah terbridging resmi dengan SATUSEHAT Kemenkes & BPJS?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFaq">
                                    <div class="accordion-body">
                                        Ya, sistem telah terintegrasi resmi dengan Platform SATUSEHAT Kemenkes RI (Encounter, Diagnosis ICD-10, Tindakan ICD-9, Obat) serta BPJS Kesehatan (PCare, Antrean Online Mobile JKN, dan i-Care) sehingga klaim dan sinkronisasi data berlangsung otomatis.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Bagaimana keamanan data dan kerahasiaan pasien dijaga?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFaq">
                                    <div class="accordion-body">
                                        Keamanan data menerapkan enkripsi data klinis, sistem otentikasi bertingkat berbasis peran pengguna (Role-Based Access Control), audit trail lengkap, serta pencadangan data otomatis untuk memastikan privasi data pasien sesuai standar keamanan kesehatan nasional.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Apakah sistem dapat diakses secara fleksibel dari berbagai perangkat?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFaq">
                                    <div class="accordion-body">
                                        Sangat bisa! Sistem dirancang responsif dan ringan, sehingga dapat diakses melalui komputer desktop, laptop, tablet, maupun smartphone oleh tenaga medis saat bertugas di klinik selama 24 jam nonstop.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================
             CALL TO ACTION BANNER
             ======================================================== -->
        <section class="cta-banner-area">
            <div class="container">
                <h2>SIAP DIGITALISASI FASKES BERSAMA KLINIK TELAGASARI 24 JAM?</h2>
                <p>Tingkatkan mutu pelayanan klinik, pangkas waktu administrasi medis, dan raih Akreditasi Paripurna tanpa beban.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <button type="button" class="btn-cta-white border-0" data-bs-toggle="modal" data-bs-target="#modalCobaGratis">
                        <i class="fas fa-stethoscope me-1"></i> Coba Gratis Sekarang
                    </button>
                    <a href="/login" class="btn btn-outline-light fw-bold px-4 py-3" style="font-size: 15px; border-radius: 6px; letter-spacing: 0.3px;">
                        <i class="fas fa-sign-in-alt me-1"></i> Masuk Aplikasi
                    </a>
                </div>
            </div>
        </section>

        <!-- ========================================================
             FOOTER
             ======================================================== -->
        <footer class="footer">
            
            <!-- Footer Top -->
            <div class="footer-top">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-12 text-center text-lg-start mb-3 mb-lg-0">
                            <a href="#" class="footer-social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="footer-social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="footer-social-link"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="footer-social-link"><i class="fab fa-youtube"></i></a>
                        </div>
                        <div class="col-lg-6 col-12">
                            <form action="#" method="get" class="footer-newsletter-form">
                                <h4>BERLANGGANAN</h4>
                                <input type="email" placeholder="Alamat Email..." class="footer-newsletter-input" required>
                                <button type="submit" class="footer-newsletter-btn">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Main Content -->
            <div class="footer-main">
                <div class="container">
                    <div class="row g-4">
                        
                        <div class="col-lg-4 col-md-6 col-12">
                            <h5 class="footer-widget-title">Klinik Telagasari 24 Jam</h5>
                            <p style="font-size: 14px; line-height: 1.7; color: #9ca3af;">
                                Faskes terpercaya dengan pelayanan medis profesional 24 Jam Nonstop dan sistem Rekam Medis Elektronik (RME) terpadu. Siap mengantarkan faskes menuju standar mutu Akreditasi Paripurna Kemenkes RI.
                            </p>
                            <p class="mt-2 text-white fw-bold" style="font-size: 14px;">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i> Telagasari, Karawang, Jawa Barat
                            </p>
                        </div>

                        <div class="col-lg-2 col-md-6 col-12">
                            <h5 class="footer-widget-title">Menu Utama</h5>
                            <ul class="footer-links">
                                <li><a href="/">Home</a></li>
                                <li><a href="#keunggulan">Kenapa Memilih Kami</a></li>
                                <li><a href="#modul-layanan">Fitur & Modul</a></li>
                                <li><a href="#testimoni">Testimoni Paripurna</a></li>
                                <li><a href="#kontak-area">Kontak Kami</a></li>
                            </ul>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <h5 class="footer-widget-title">Modul Faskes</h5>
                            <ul class="footer-links">
                                <li><a href="/pendaftaran">Front Office & Antrean</a></li>
                                <li><a href="/pasien">Rekam Medis (RME)</a></li>
                                <li><a href="/laboratorium/transaksi-lab">Laboratorium Terpadu</a></li>
                                <li><a href="/transaksi-kasir">Kasir & Billing Keuangan</a></li>
                                <li><a href="/bridging-check">SATUSEHAT & BPJS PCare</a></li>
                            </ul>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <h5 class="footer-widget-title">Kontak Darurat 24 Jam</h5>
                            <p style="font-size: 14px; color: #9ca3af; margin-bottom: 8px;">
                                <i class="fas fa-phone-alt text-success me-2"></i> IGD / Call Center: (0267) 845-xxxx
                            </p>
                            <p style="font-size: 14px; color: #9ca3af; margin-bottom: 8px;">
                                <i class="fab fa-whatsapp text-success me-2"></i> WhatsApp: 0812-8888-2026 (Konhakes)
                            </p>
                            <p style="font-size: 14px; color: #9ca3af;">
                                <i class="fas fa-envelope text-primary me-2"></i> info@kliniktelagasari.com
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="container">
                    &copy; 2026 Klinik Telagasari 24 Jam. Seluruh Hak Cipta Dilindungi Undang-Undang.
                </div>
            </div>

        </footer>

        <!-- Modal Coba Gratis & Akses Demo -->
        <div class="modal fade" id="modalCobaGratis" tabindex="-1" aria-labelledby="modalCobaGratisLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #004aad 0%, #0077b6 100%);">
                        <div>
                            <h4 class="modal-title fw-bold mb-1" id="modalCobaGratisLabel" style="font-size: 1.35rem;">
                                <i class="fas fa-stethoscope me-2"></i> Coba Gratis & Akses Demo RME
                            </h4>
                            <p class="mb-0 opacity-75" style="font-size: 13.5px;">
                                Pilih cara untuk mencoba fitur lengkap Rekam Medis Elektronik Klinik Telagasari 24 Jam
                            </p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <!-- Opsi 1: Akses Demo Langsung -->
                            <div class="col-md-6 col-12">
                                <div class="card h-100 border-0 shadow-sm p-3 bg-white" style="border-radius: 10px; border-top: 4px solid #004aad !important;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary px-2 py-1"><i class="fas fa-laptop-medical"></i> Akun Demo</span>
                                        <span class="badge bg-success-subtle text-success fw-bold">Siap Pakai</span>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem;">Eksplorasi Demo SIMRS</h5>
                                    <p class="text-muted small mb-3" style="font-size: 13px; line-height: 1.45;">
                                        Langsung uji coba semua fitur klinik (Pendaftaran, RME Dokter, Farmasi, Kasir, Lab, Radiologi) menggunakan akun demo gratis.
                                    </p>
                                    <div class="bg-light p-2 rounded border mb-3" style="font-size: 12px;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Username:</span>
                                            <strong class="text-dark">admin / fahmirizal / lusi</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Password:</span>
                                            <strong class="text-primary font-monospace">password123</strong>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="/login" class="btn btn-primary w-100 fw-bold py-2" style="background-color: #004aad; border-color: #004aad; border-radius: 6px; font-size: 14px;">
                                            <i class="fas fa-sign-in-alt me-1"></i> Masuk ke Demo Aplikasi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Opsi 2: Daftarkan Klinik Baru -->
                            <div class="col-md-6 col-12">
                                <div class="card h-100 border-0 shadow-sm p-3 bg-white" style="border-radius: 10px; border-top: 4px solid #00a65a !important;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-success px-2 py-1"><i class="fas fa-clinic-medical"></i> Faskes Baru</span>
                                        <span class="badge bg-warning-subtle text-dark fw-bold">Trial 14 Hari</span>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem;">Daftarkan Klinik Anda</h5>
                                    <p class="text-muted small mb-3" style="font-size: 13px; line-height: 1.45;">
                                        Ingin mencoba sistem ini dengan nama faskes, data tindakan, dan tim dokter klinik Anda sendiri? Hubungi kami untuk aktivasi trial.
                                    </p>
                                    <ul class="list-unstyled mb-3" style="font-size: 12.5px; color: #444;">
                                        <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> Gratis setup database klinik sendiri</li>
                                        <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> Panduan & pendampingan tim IT</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i> Uji coba integrasi BPJS & SATUSEHAT</li>
                                    </ul>
                                    <div class="mt-auto">
                                        <a href="https://api.whatsapp.com/send/?phone=6281288882026&text=Halo%20Tim%20Konhakes%2C%20saya%20tertarik%20mencoba%20gratis%20sistem%20RME%20Konhakes%20untuk%20klinik%20saya" target="_blank" class="btn btn-success w-100 fw-bold py-2" style="background-color: #00a65a; border-color: #00a65a; border-radius: 6px; font-size: 14px;">
                                            <i class="fab fa-whatsapp me-1"></i> Aktivasi via WhatsApp Konhakes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center">
                        <span class="text-muted" style="font-size: 12px;">
                            <i class="fas fa-info-circle me-1 text-primary"></i> Dukungan Teknis Hotline Konhakes: <strong>(+62)812-8888-2026</strong>
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Interactive Hero Slider Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = [
                {
                    badge: "KLINIK TELAGASARI 24 JAM",
                    subtitle: "Rekam Medis Elektronik Yang Bikin Faskes NAIK KELAS !",
                    bg: "/landing_assets/hero-doctor.jpg"
                },
                {
                    badge: "AKREDITASI PARIPURNA",
                    subtitle: "Persiapan Cepat, Bukti Telusur Tinggal Download",
                    bg: "/landing_assets/hero-doctor.jpg"
                },
                {
                    badge: "PELAYANAN MEDIS 24 JAM",
                    subtitle: "Siap Melayani IGD, Rawat Jalan & Inap Nonstop Kapan Saja",
                    bg: "/landing_assets/hero-doctor.jpg"
                }
            ];

            let currentSlideIndex = 0;
            const slideSingle = document.getElementById('sliderSingle');
            const slideBadge = document.getElementById('slideBadge');
            const slideSubtitle = document.getElementById('slideSubtitle');
            const btnPrev = document.getElementById('btnPrevSlide');
            const btnNext = document.getElementById('btnNextSlide');

            function showSlide(index) {
                if (!slideSingle || !slideBadge || !slideSubtitle) return;
                
                // Fade effect
                slideSingle.style.opacity = '0.3';
                
                setTimeout(() => {
                    const slide = slides[index];
                    slideBadge.textContent = slide.badge;
                    slideSubtitle.textContent = slide.subtitle;
                    if (slide.bg) {
                        slideSingle.style.backgroundImage = `url('${slide.bg}')`;
                    }
                    slideSingle.style.opacity = '1';
                }, 180);
            }

            if (btnPrev) {
                btnPrev.addEventListener('click', function () {
                    currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
                    showSlide(currentSlideIndex);
                });
            }

            if (btnNext) {
                btnNext.addEventListener('click', function () {
                    currentSlideIndex = (currentSlideIndex + 1) % slides.length;
                    showSlide(currentSlideIndex);
                });
            }

            // Auto-advance slider every 7 seconds
            setInterval(() => {
                currentSlideIndex = (currentSlideIndex + 1) % slides.length;
                showSlide(currentSlideIndex);
            }, 7000);
        });
    </script>
</body>
</html>
