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
        .illustration {
            width: 100%;
            height: 180px;
            background-image: url('assets/image/register.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            margin: 15px 0;
        }
        .register-container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .register-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 25px;
            margin-top: 5px;
        }
        .input-group {
            position: relative;
            margin-bottom: 15px;
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
        .register-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            margin: 25px 0 30px 0;
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
            font-size: 16px;
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
        
        <form onsubmit="handleRegister(event)" novalidate>
            <div class="input-group">
                <span class="input-icon">🆔</span>
                <input type="text" id="idcard" name="idcard" placeholder="ID Card" required />
            </div>

            <div class="input-group">
                <span class="input-icon">👤</span>
                <input type="text" id="name" name="name" placeholder="Full Name" required />
            </div>

            <div class="input-group">
                <span class="input-icon">👨</span>
                <input type="text" id="username" name="username" placeholder="Username" required autocomplete="username" />
            </div>

            <div class="input-group">
                <span class="input-icon">🔒</span>
                <input type="password" id="password" name="password" placeholder="Password" required autocomplete="new-password" />
            </div>

            <button type="submit" class="register-btn">Register</button>
        </form>

        <div class="login-section">
            Already have an account? <a href="login" class="login-link">Login</a>
        </div>
    </div>

    <script>
        function handleRegister(event) {
            event.preventDefault();
            
            const idcard = document.getElementById('idcard').value;
            const name = document.getElementById('name').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (!idcard || !name || !username || !password) {
                alert('Please fill in all fields');
                return;
            }
            
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                return;
            }
            
            // Simulate registration process
            const registerBtn = document.querySelector('.register-btn');
            registerBtn.textContent = 'Registering...';
            registerBtn.disabled = true;
            
            setTimeout(() => {
                // Store user data (simple simulation)
                const userData = {
                    idcard: idcard,
                    name: name,
                    username: username,
                    registeredAt: new Date().toISOString()
                };
                
                // In real app, this would be sent to server
                localStorage.setItem('registeredUser', JSON.stringify(userData));
                
                alert('Registration successful! Please login with your credentials.');
                window.location.href = 'login';
            }, 1500);
        }
        
        // Check if user is already logged in
        document.addEventListener('DOMContentLoaded', function() {
            const userSession = localStorage.getItem('userSession');
            if (userSession) {
                window.location.href = 'dashboard';
            }
        });
    </script>
</body>
</html>