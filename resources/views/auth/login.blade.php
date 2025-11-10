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
            width: 393px;
            height: 852px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
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
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="register-section">
            Tidak punya akun? Hubungi admin untuk pembuatan akun.
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
    </script>
</body>
</html>