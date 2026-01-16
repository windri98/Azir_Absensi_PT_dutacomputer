<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            width: 100%;
            margin: 0;
        }
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            text-align: center;
            padding: 50px 20px 30px 20px;
            position: relative;
        }

        .header h1 {
            font-size: 28px;
            font-weight: normal;
            letter-spacing: 2px;
            margin-top: 20px;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            background-color: #f5f5f5;
        }
        .illustration {
            width: 300px;
            height: 250px;
            margin-bottom: 60px;
            background-image: url('assets/image/welcome.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 280px;
        }
        .button-link {
            display: block;
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 18px 24px;
            text-decoration: none;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 199, 230, 0.3);
        }
        .button-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 199, 230, 0.4);
        }
        .button-link.login {
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
        }
        footer {
            background-color: transparent;
            color: #666;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            font-style: italic;
        }
    </style>
    
</head>
<body>
    <div class="header">
        <h1>WELCOME</h1>
    </div>
    
    <div class="main-content">
        <div class="illustration"></div>
        
        <div class="button-container">
            {{-- <a href="{{ route('register') }}" class="button-link">Register</a> --}}
            <a href="{{ route('login') }}" class="button-link login">Login</a>
        </div>
    </div>
    
    <footer>
        PT.Duta Computer | © 2025
    </footer>

    <script>
        // Check if user is already logged in
        document.addEventListener('DOMContentLoaded', function() {
            const userSession = localStorage.getItem('userSession');
            if (userSession) {
                window.location.href = '{{ route('dashboard') }}';
            }
        });
    </script>
</body>
</html>
