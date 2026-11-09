<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Aplikasi</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='46' fill='%23004aad'/><rect x='44' y='22' width='12' height='56' rx='4' fill='white'/><rect x='22' y='44' width='56' height='12' rx='4' fill='white'/></svg>">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Roboto+Condensed:wght@600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #d2d6de;
        }

        /* Full Page Background */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e4f1f8 url("/landing_assets/login-bg.jpg") no-repeat right center fixed;
            background-size: cover;
            position: relative;
            padding: 20px 15px;
            overflow-x: hidden;
        }

        /* Left Mask: completely covers any old background watermark */
        .login-bg-mask {
            position: fixed;
            top: 0;
            left: 0;
            width: 54%;
            height: 100%;
            background: linear-gradient(90deg, #d4ebf5 0%, #def0f8 70%, rgba(222, 240, 248, 0.98) 88%, rgba(222, 240, 248, 0) 100%);
            z-index: 1;
            pointer-events: none;
        }

        /* Crisp Vector Watermark on Left for Klinik Telagasari 24 Jam */
        .watermark-overlay {
            position: fixed;
            left: 6%;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            pointer-events: none;
            z-index: 2;
            user-select: none;
        }

        .watermark-circle {
            width: 170px;
            height: 170px;
            border: 10px solid rgba(0, 74, 173, 0.18);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            background: rgba(255, 255, 255, 0.25);
        }

        .watermark-circle i {
            font-size: 78px;
            color: rgba(0, 74, 173, 0.22);
        }

        .watermark-overlay h2 {
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 800;
            font-size: 32px;
            color: rgba(0, 74, 173, 0.25);
            letter-spacing: 2px;
            margin: 0;
            text-transform: uppercase;
        }

        .watermark-overlay span {
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 700;
            font-size: 13.5px;
            color: rgba(0, 166, 90, 0.32);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* AdminLTE Login Box */
        .login-box {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            border-radius: 4px;
            overflow: hidden;
            background: #ffffff;
        }

        /* Header Banner with Clean Klinik Telagasari Branding + Cartoon Operators */
        .login-card-header {
            position: relative;
            width: 100%;
            height: 124px;
            background-color: #0073b7;
            display: flex;
            align-items: center;
            padding-left: 20px;
            overflow: hidden;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        /* Left Brand: KLINIK TELAGASARI 24 JAM */
        .header-brand {
            position: relative;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 58%;
        }

        .header-logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2.5px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.18);
            flex-shrink: 0;
        }

        .header-logo-icon i {
            font-size: 24px;
            color: #ffffff;
        }

        .header-text-title {
            color: #ffffff;
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 800;
            font-size: 19px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.1;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        }

        .header-text-sub {
            color: #d1ecfd;
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        /* Right Illustration: ONLY cartoon agents cropped from the right 45% */
        .header-illustration-right {
            position: absolute;
            right: 0;
            top: 0;
            width: 45%;
            height: 100%;
            background: url('/landing_assets/login-header.jpg') right center no-repeat;
            background-size: cover;
            z-index: 1;
            pointer-events: none;
        }

        /* Soft Gradient Blend between solid #0073b7 and cartoon illustration */
        .header-mask-blend {
            position: absolute;
            right: 40%;
            top: 0;
            width: 14%;
            height: 100%;
            background: linear-gradient(90deg, #0073b7 0%, rgba(0, 115, 183, 0) 100%);
            z-index: 2;
            pointer-events: none;
        }

        /* Login Card Body */
        .login-box-body {
            background: #ffffff;
            padding: 24px 28px 18px;
            color: #444444;
        }

        .login-title {
            text-align: center;
            font-size: 23px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 4px;
        }

        .login-box-msg {
            text-align: center;
            font-size: 13.5px;
            color: #777777;
            margin-bottom: 22px;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-control {
            border-radius: 2px;
            border: 1px solid #d2d6de;
            box-shadow: none;
            padding: 10px 38px 10px 14px;
            font-size: 14px;
            color: #555555;
            height: 42px;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #3c8dbc;
            box-shadow: none;
        }

        .form-control-feedback {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 42px;
            color: #888888;
            font-size: 15px;
            pointer-events: none;
        }

        /* Captcha Verification Code */
        .verify-label {
            font-size: 13.5px;
            font-weight: 700;
            color: #444444;
            margin-bottom: 6px;
            display: block;
        }

        .captcha-container {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .captcha-canvas-wrap {
            width: 120px;
            height: 40px;
            background: #f4f6f9;
            border: 1px solid #d2d6de;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            flex-shrink: 0;
            user-select: none;
        }

        .captcha-canvas-wrap:hover {
            border-color: #3c8dbc;
        }

        .captcha-hint {
            font-size: 11px;
            color: #737373;
            margin-top: 5px;
            line-height: 1.35;
        }

        /* Action Button */
        .btn-login-submit {
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #ffffff;
            border-radius: 2px;
            padding: 10px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .btn-login-submit:hover {
            background-color: #286090;
            border-color: #204d74;
            color: #ffffff;
        }

        .forgot-link {
            display: inline-block;
            margin-top: 14px;
            color: #337ab7;
            text-decoration: none;
            font-size: 13.5px;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #23527c;
        }

        /* Box Footer */
        .box-footer {
            padding: 16px 20px;
            background-color: #ffffff;
            border-top: 1px solid #f4f4f4;
            text-align: center;
            font-size: 12.5px;
            color: #555555;
            line-height: 1.6;
        }

        .box-footer a {
            color: #337ab7;
            text-decoration: none;
            font-weight: 600;
        }

        .box-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="login-page">

    <!-- Mask to completely conceal old background watermark -->
    <div class="login-bg-mask"></div>

    <!-- Clean Vector Watermark on Left: KLINIK TELAGASARI 24 JAM -->
    <div class="watermark-overlay d-none d-lg-flex">
        <div class="watermark-circle">
            <i class="fas fa-stethoscope"></i>
        </div>
        <h2>KLINIK TELAGASARI</h2>
        <span>LAYANAN MEDIS 24 JAM</span>
    </div>

    <!-- AdminLTE Login Box -->
    <div class="login-box">
        
        <!-- Header Banner: KLINIK TELAGASARI 24 JAM + Cartoon Operators on Right -->
        <div class="login-card-header">
            <!-- Left Brand -->
            <div class="header-brand">
                <div class="header-logo-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="header-text-wrap">
                    <div class="header-text-title">KLINIK TELAGASARI</div>
                    <div class="header-text-sub">Layanan Medis 24 Jam</div>
                </div>
            </div>

            <!-- Blend Gradient -->
            <div class="header-mask-blend"></div>

            <!-- Right Cartoon Illustration -->
            <div class="header-illustration-right"></div>
        </div>

        <!-- Login Card Body -->
        <div class="login-box-body">
            <h3 class="login-title">Login Aplikasi</h3>
            <p class="login-box-msg">Masukan akun terdaftar untuk login</p>

            <form id="login-form" action="/dashboard" method="GET">
                
                <!-- Email / Username -->
                <div class="form-group">
                    <input type="text" id="loginform-email" class="form-control" name="LoginForm[email]" autocomplete="off" placeholder="Email / Username" value="admin" required>
                    <span class="form-control-feedback">
                        <i class="far fa-envelope"></i>
                    </span>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input type="password" id="loginform-password" class="form-control" name="LoginForm[password]" autocomplete="off" placeholder="Password" value="admin" required>
                    <span class="form-control-feedback">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>

                <!-- Kode Verifikasi (Captcha) -->
                <div class="form-group">
                    <label class="verify-label" for="loginform-verifycode">Kode Verifikasi</label>
                    <div class="captcha-container">
                        <div class="captcha-canvas-wrap" id="captchaWrap" title="Klik untuk mengganti kode captcha">
                            <canvas id="captchaCanvas" width="115" height="38"></canvas>
                        </div>
                        <div style="flex-grow: 1;">
                            <input type="text" id="loginform-verifycode" class="form-control" style="height: 40px;" name="LoginForm[verifyCode]" placeholder="Kode verifikasi" required>
                        </div>
                    </div>
                    <p class="captcha-hint">
                        Silakan isi kode disamping. Klik pada gambar jika ingin mengganti kode!
                    </p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login-submit" name="login-button" id="btnSubmit">
                    Login
                </button>

                <!-- Forgot Password -->
                <div>
                    <a href="javascript:void(0);" onclick="alert('Silakan hubungi administrator IT Klinik Telagasari 24 Jam untuk reset kata sandi.');" class="forgot-link">
                        Lupa password login?
                    </a>
                </div>

            </form>
        </div>

        <!-- Footer -->
        <div class="box-footer">
            &copy; 2026 Support By <a href="/">Klinik Telagasari 24 Jam</a><br>
            email : info@kliniktelagasari.com <br>
            hotline w.a : (+62)812-2580-80
        </div>

    </div>

    <!-- Script Captcha & Smooth Login -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('captchaCanvas');
            const captchaWrap = document.getElementById('captchaWrap');
            const verifyInput = document.getElementById('loginform-verifycode');
            let currentCaptcha = '';

            function generateCaptcha() {
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Background soft blue
                ctx.fillStyle = '#ebf4fc';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Generate 6 random characters
                const chars = 'abcdefhjkmnpqrstuvwxyz23456789';
                currentCaptcha = '';
                for (let i = 0; i < 6; i++) {
                    currentCaptcha += chars.charAt(Math.floor(Math.random() * chars.length));
                }

                if (verifyInput && !verifyInput.value) {
                    verifyInput.value = currentCaptcha;
                }

                // Add wavy noise lines
                for (let i = 0; i < 3; i++) {
                    ctx.strokeStyle = '#a4c2e6';
                    ctx.lineWidth = 1.2;
                    ctx.beginPath();
                    ctx.moveTo(0, Math.random() * canvas.height);
                    ctx.bezierCurveTo(
                        canvas.width / 3, Math.random() * canvas.height,
                        (canvas.width / 3) * 2, Math.random() * canvas.height,
                        canvas.width, Math.random() * canvas.height
                    );
                    ctx.stroke();
                }

                // Draw characters
                for (let i = 0; i < currentCaptcha.length; i++) {
                    ctx.save();
                    ctx.font = 'bold 20px "Roboto Condensed", sans-serif';
                    ctx.fillStyle = '#0f4c81';
                    
                    const x = 12 + (i * 16);
                    const y = 26 + (Math.sin(i) * 3);
                    const angle = (Math.random() - 0.5) * 0.3;
                    
                    ctx.translate(x, y);
                    ctx.rotate(angle);
                    ctx.fillText(currentCaptcha[i], 0, 0);
                    ctx.restore();
                }
            }

            // Generate initial captcha
            generateCaptcha();

            // Refresh on click
            if (captchaWrap) {
                captchaWrap.addEventListener('click', function () {
                    generateCaptcha();
                    if (verifyInput) verifyInput.value = currentCaptcha;
                });
            }

            // Form Submit to /dashboard
            const loginForm = document.getElementById('login-form');
            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    return true;
                });
            }
        });
    </script>
</body>
</html>
