<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#00C9FF">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Profil - Absensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }
        
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #00C9FF 0%, #00B4DB 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
        }

        .container {
            background: white;
            border-radius: 0;
            padding: 40px 30px;
            width: 100%;
            max-width: 393px;
            min-height: 100vh;
            box-shadow: none;
            position: relative;
        }
        
        @media (min-width: 394px) {
            body {
                padding: 20px;
            }
            
            .container {
                border-radius: 20px;
                min-height: auto;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            background: rgba(0, 201, 255, 0.2);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(0, 201, 255, 0.3);
            transform: translateX(-3px);
        }

        .back-button::before {
            content: '←';
            font-size: 24px;
            color: #00C9FF;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
            border: 3px solid #00C9FF;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .profile-id {
            font-size: 14px;
            color: #666;
        }

        .menu-item {
            padding: 18px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: background 0.2s ease;
            text-decoration: none;
            color: #333;
        }

        .menu-item:hover {
            background: #f5f5f5;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu-icon {
            width: 24px;
            height: 24px;
            color: #666;
        }

        .menu-text {
            font-size: 16px;
            color: #333;
        }

        .menu-arrow {
            color: #999;
            font-size: 18px;
        }

        .logout-button {
            margin-top: 30px;
            width: 100%;
            padding: 14px;
            background: #00C9FF;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-button:hover {
            background: #00B4DB;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 201, 255, 0.3);
        }

        @media (max-width: 393px) {
            .container {
                padding: 30px 20px;
            }

            .back-button {
                top: 20px;
                left: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="back-button" onclick="goBack()"></button>
        
        <div class="profile-header">
            <img src="assets/image/profile-avatar.jpg" alt="Profile" class="profile-image" onerror="this.src='https://via.placeholder.com/80'">
            <div class="profile-name">Widya Mayasari Fauziah</div>
            <div class="profile-id">ID : 0001</div>
        </div>

        <div class="menu-list">
            <div class="menu-item" onclick="location.href='{{ route('profile.show') }}'">
                <div class="menu-item-left">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    <span class="menu-text">ID Card</span>
                </div>
                <span class="menu-arrow">›</span>
            </div>

            <div class="menu-item" onclick="location.href='{{ route('change-password') }}'">
                <div class="menu-item-left">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span class="menu-text">Ubah Password</span>
                </div>
                <span class="menu-arrow">›</span>
            </div>
        </div>

        <button class="logout-button" onclick="logout()">log out</button>
    </div>

    <script>
        function goBack() {
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("profile.show") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("profile.show") }}';
                    }
                } else {
                    window.location.href = '{{ route("profile.show") }}';
                }
            }
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                // Clear any stored session data
                localStorage.clear();
                sessionStorage.clear();
                // Redirect to login page
                window.location.href = '{{ route("login") }}';
            }
        }
    </script>
</body>
</html>
