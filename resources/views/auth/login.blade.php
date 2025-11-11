<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi</title>
    <!-- Small inline popup styles (self-contained so page doesn't depend on missing external file) -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            text-align: center;
            padding: 50px 20px 30px 20px;
            position: relative;
        }

        .header h1 {
            font-size: 18px;
            font-weight: normal;
            letter-spacing: 1px;
            margin-top: 20px;
            line-height: 1.2;
        }
        .illustration {
            width: 100%;
            height: 200px;
            background-image: url('assets/image/login_img.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            margin: 20px 0;
        }
        .login-container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .login-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
            margin-top: 10px;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group input {
            width: 100%;
            padding: 18px 18px 18px 55px;
            border: none;
            border-radius: 25px;
            background-color: #e5e5e5;
            font-size: 16px;
            color: #333;
        }
        .input-group input::placeholder {
            color: #999;
        }
        .input-group input:focus {
            outline: none;
            background-color: #ddd;
        }
        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #333;
            font-size: 20px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .input-icon svg {
            width: 20px;
            height: 20px;
            fill: #333;
        }
        .remember-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 30px 0;
            font-size: 14px;
        }
        .remember-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .remember-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .remember-group label {
            color: #333;
            cursor: pointer;
        }
        .forgot-link {
            color: #333;
            text-decoration: none;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
        .login-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(34, 211, 238, 0.3);
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 211, 238, 0.4);
        }
        .register-section {
            text-align: center;
            color: #666;
            font-size: 16px;
            margin-top: auto;
            margin-bottom: 20px;
        }
        .register-link {
            color: #ff4757;
            text-decoration: none;
            font-weight: 500;
        }
        .register-link:hover {
            text-decoration: underline;
        }
        /* Popup modal styles */
        .popup-overlay {
            display: none; /* shown via JS when needed */
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }
        .popup {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            max-width: 360px;
            width: 100%;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            color: #333;
        }
        
        /* Forgot password popup styles */
        .forgot-popup {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            color: #333;
            text-align: center;
        }
        
        .forgot-popup h3 {
            color: #e74c3c;
            margin-bottom: 16px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .forgot-popup h3 svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .forgot-popup p {
            margin-bottom: 20px;
            line-height: 1.5;
            color: #555;
        }
        
        .popup-btn {
            padding: 10px 20px;
            background: #06b6d4;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        
        .popup-btn:hover {
            background: #0891b2;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        
        /* Mobile First - Base styles are for mobile */
        
        /* Tablet - Portrait (768px and up) */
        @media (min-width: 768px) {
            body {
                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                display: grid;
                grid-template-columns: 1fr 1fr;
                min-height: 100vh;
                padding: 0;
                margin: 0;
            }
            
            .header {
                padding: 60px 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                background: linear-gradient(135deg, #0ea5e9, #0284c7);
            }
            
            .header h1 {
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 30px;
            }
            
            .illustration {
                width: 80%;
                height: 300px;
                margin: 0 auto;
                background-size: contain;
            }
            
            .login-container {
                padding: 60px 60px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                background: white;
                box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            }
            
            .login-title {
                font-size: 36px;
                text-align: center;
                margin-bottom: 40px;
            }
        }
        
        /* Desktop (1024px and up) */
        @media (min-width: 1024px) {
            .header {
                padding: 80px 60px;
            }
            
            .header h1 {
                font-size: 32px;
            }
            
            .illustration {
                height: 350px;
                width: 70%;
            }
            
            .login-container {
                padding: 80px 80px;
                max-width: none;
            }
            
            .login-title {
                font-size: 40px;
                margin-bottom: 50px;
            }
            
            .input-group input {
                padding: 20px 20px 20px 60px;
                font-size: 18px;
            }
            
            .login-btn {
                padding: 20px;
                font-size: 20px;
                margin-bottom: 40px;
            }
        }
        
        /* Large Desktop (1440px and up) */
        @media (min-width: 1440px) {
            body {
                grid-template-columns: 60% 40%;
            }
            
            .header {
                padding: 100px 80px;
            }
            
            .login-container {
                padding: 100px 100px;
            }
        }
        
        /* Mobile Landscape */
        @media (max-width: 767px) and (orientation: landscape) {
            .header {
                padding: 30px 20px 20px 20px;
            }
            
            .header h1 {
                font-size: 16px;
                margin-top: 10px;
            }
            
            .illustration {
                height: 120px;
                margin: 10px 0;
            }
            
            .login-container {
                padding: 20px;
            }
            
            .login-title {
                font-size: 24px;
                margin-bottom: 20px;
            }
            
            .input-group {
                margin-bottom: 15px;
            }
            
            .remember-section {
                margin: 15px 0 20px 0;
            }
            
            .login-btn {
                margin-bottom: 20px;
            }
        }
    </style>
    
</head>
<body>
    <div class="header">
        <h1>SISTEM ABSENSI DAN AKTIFITAS</h1>
    </div>

    <div class="illustration"></div>

    <div class="login-container">
        <h2 class="login-title">Login</h2>
        
        @if(session('success'))
            <!-- Modal popup shown after successful registration -->
            <div id="register-popup" class="popup-overlay">
                <div class="popup">
                    <h3 style="margin-bottom:8px;">Registrasi Berhasil</h3>
                    <p style="margin:0 0 12px 0;">{{ session('success') }}</p>
                    <div style="text-align:right;">
                        <button id="popup-ok" style="padding:8px 12px;background:#06b6d4;color:#fff;border:none;border-radius:6px;cursor:pointer;">OK</button>
                    </div>
                </div>
            </div>
            <noscript>
                <div style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 10px; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            </noscript>
        @endif

        @if($errors->any())
            <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 10px; margin-bottom: 15px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </span>
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email" />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                    </svg>
                </span>
                <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password" />
            </div>

            <div class="remember-section">
                <div class="remember-group">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} />
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-link" id="forgot-password-link">Forgot password?</a>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="register-section">
            Tidak punya akun? Hubungi admin untuk pembuatan akun.
        </div>
    </div>

    <!-- Forgot Password Popup -->
    <div id="forgot-password-popup" class="popup-overlay">
        <div class="forgot-popup">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 13a1.49 1.49 0 0 0-1 2.61V17a1 1 0 0 0 2 0v-1.39A1.49 1.49 0 0 0 12 13m5-4V7A5 5 0 0 0 7 7v2a3 3 0 0 0-3 3v7a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-7a3 3 0 0 0-3-3M9 7a3 3 0 0 1 6 0v2H9Zm9 12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1Z"/>
                </svg>
                <span>Lupa Password?</span>
            </h3>
            <p>Silakan hubungi Admin untuk reset password Anda.</p>
            <button id="forgot-popup-close" class="popup-btn">Tutup</button>
        </div>
    </div>

    <script>
        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[style*="background-color"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 5000);
            });
        });
        
        // Check if user is already logged in
        document.addEventListener('DOMContentLoaded', function() {
            const userSession = localStorage.getItem('userSession');
            if (userSession) {
                const userData = JSON.parse(userSession);
                // Check if remember me was checked or session is still valid
                if (userData.remember) {
                    window.location.href = '{{ route('dashboard') }}';
                }
            }
        });
    </script>
    
    <script>
        // Popup show/hide logic for registration success
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('register-popup');
            if (popup) {
                // show popup (overlay CSS handles visibility)
                popup.style.display = 'flex';

                const ok = document.getElementById('popup-ok');
                const hide = () => { popup.style.display = 'none'; };

                ok && ok.addEventListener('click', hide);
                // hide when clicking outside the popup box
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) hide();
                });
            }
        });
        
        // Forgot password popup logic
        document.addEventListener('DOMContentLoaded', function() {
            const forgotLink = document.getElementById('forgot-password-link');
            const forgotPopup = document.getElementById('forgot-password-popup');
            const closeBtn = document.getElementById('forgot-popup-close');
            
            // Show popup when forgot password link is clicked
            forgotLink.addEventListener('click', function(e) {
                e.preventDefault();
                forgotPopup.style.display = 'flex';
            });
            
            // Hide popup when close button is clicked
            closeBtn.addEventListener('click', function() {
                forgotPopup.style.display = 'none';
            });
            
            // Hide popup when clicking outside the popup box
            forgotPopup.addEventListener('click', function(e) {
                if (e.target === forgotPopup) {
                    forgotPopup.style.display = 'none';
                }
            });
            
            // Hide popup when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && forgotPopup.style.display === 'flex') {
                    forgotPopup.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>