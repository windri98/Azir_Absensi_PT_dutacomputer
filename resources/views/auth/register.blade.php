<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Absensi</title>
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
            overflow-y: auto;
        }
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            text-align: center;
            padding: 30px 20px 20px 20px;
            position: relative;
            flex-shrink: 0;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: normal;
            letter-spacing: 2px;
            margin-top: 10px;
        }
        .illustration {
            width: 100%;
            height: 150px;
            background-image: url('{{ asset('assets/image/register.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            margin: 10px 0;
            flex-shrink: 0;
        }
        .register-container {
            flex: 1;
            padding: 15px 20px 20px 20px;
            display: flex;
            flex-direction: column;
        }
        .register-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            margin-top: 0;
        }
        .input-group {
            position: relative;
            margin-bottom: 12px;
        }
        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: none;
            border-radius: 25px;
            background-color: #e5e5e5;
            font-size: 15px;
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
        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            margin: 15px 0 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(34, 211, 238, 0.3);
        }
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 211, 238, 0.4);
        }
        .login-section {
            text-align: center;
            color: #666;
            font-size: 15px;
            margin-top: 10px;
            margin-bottom: 15px;
        }
            margin-top: auto;
            margin-bottom: 20px;
        }
        .login-link {
            color: #ff4757;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        
        <h1>REGISTER</h1>
    </div>

    <div class="illustration"></div>

    <div class="register-container">
        <h2 class="register-title">Register</h2>
        
        @if(session('success'))
            <div style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 10px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
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
        
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="8" cy="10" r="2" fill="currentColor"/>
                        <path d="M5 16c0-1.5 2-2 3-2s3 0.5 3 2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <line x1="13" y1="9" x2="19" y2="9" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="13" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="13" y1="15" x2="17" y2="15" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </span>
                <input type="text" id="employee_id" name="employee_id" placeholder="id card" value="{{ old('employee_id') }}" required />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </span>
                <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name') }}" required />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </span>
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                    </svg>
                </span>
                <input type="password" id="password" name="password" placeholder="Password" required autocomplete="new-password" />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.1-.03-.21-.05-.31-.05-.26 0-.51.1-.71.29l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.21c.28-.26.36-.65.25-1C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/>
                    </svg>
                </span>
                <input type="text" id="phone" name="phone" placeholder="Phone" value="{{ old('phone') }}" />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </span>
                <input type="text" id="address" name="address" placeholder="Address" value="{{ old('address') }}" />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5zm2 4h10v2H7v-2z"/>
                    </svg>
                </span>
                <input type="date" id="birth_date" name="birth_date" placeholder="Birth Date" value="{{ old('birth_date') }}" />
            </div>

            <button type="submit" class="register-btn">Register</button>
        </form>

        <div class="login-section">
            Don't have an account? <a href="{{ route('login') }}" class="login-link">Login</a>
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
    </script>
</body>
</html>
