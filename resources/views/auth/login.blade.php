<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi</title>
    <!-- Popup Component CSS -->
    <link rel="stylesheet" href="components/popup.css">
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
            padding: 18px 50px 18px 18px;
            border: none;
            border-radius: 25px;
            background-color: #e5e5e5;
            font-size: 16px;
            color: #666;
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
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 20px;
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
    </style>
    
</head>
<body>
    <div class="header">
        <h1>SISTEM ABSENSI DAN AKTIFITAS</h1>
    </div>

    <div class="illustration"></div>

    <div class="login-container">
        <h2 class="login-title">Login</h2>
        
        <form onsubmit="handleLogin(event)" novalidate>
            <div class="input-group">
                <span class="input-icon">👤</span>
                <input type="text" id="username" name="username" placeholder="Username" required autocomplete="username" />
            </div>

            <div class="input-group">
                <span class="input-icon">🔒</span>
                <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password" />
            </div>

            <div class="remember-section">
                <div class="remember-group">
                    <input type="checkbox" id="remember" name="remember" />
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="register-section">
            Don't have an account? <a href="{{ route('register') }}" class="register-link">Register</a>
        </div>
    </div>

    <script>
        function handleLogin(event) {
            event.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            
            // Basic validation
            if (!username || !password) {
                showErrorPopup({
                    title: 'Login Failed',
                    message: 'Please fill in all fields',
                    buttonText: 'OK'
                });
                return;
            }
            
            // Simulate login process
            const loginBtn = document.querySelector('.login-btn');
            loginBtn.textContent = 'Logging in...';
            loginBtn.disabled = true;
            
            setTimeout(() => {
                // Store user session (simple simulation)
                const userData = {
                    username: username,
                    loginTime: new Date().toISOString(),
                    remember: remember
                };
                
                localStorage.setItem('userSession', JSON.stringify(userData));
                
                // Redirect to dashboard
                window.location.href = '{{ route('dashboard') }}';
            }, 1500);
        }
        
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
    
    <!-- Popup Component JavaScript -->
    <script src="components/popup.js"></script>
</body>
</html>