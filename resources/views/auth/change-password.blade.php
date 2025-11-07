<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#00C9FF">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Ubah Password - Absensi</title>
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

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            background: #f0f0f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-icon svg {
            width: 24px;
            height: 24px;
            color: #333;
        }

        .header-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .form-label .required {
            color: #ff4444;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #00C9FF;
            box-shadow: 0 0 0 3px rgba(0, 201, 255, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel {
            background: #e0e0e0;
            color: #666;
        }

        .btn-cancel:hover {
            background: #d0d0d0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
            background: #00C9FF;
            color: white;
        }

        .btn-submit:hover {
            background: #00B4DB;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 201, 255, 0.3);
        }

        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            color: #ff4444;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .success-message {
            background: #4CAF50;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            text-align: center;
        }

        .success-message.show {
            display: block;
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
        
        <div class="header">
            <div class="header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="header-title">Ubah Password</h1>
        </div>

        <div class="success-message" id="successMessage">
            Password berhasil diubah!
        </div>

        <form id="changePasswordForm">
            <div class="form-group">
                <label class="form-label">
                    Password lama<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        class="form-input" 
                        id="oldPassword"
                        placeholder="Masukkan password lama"
                        required
                    >
                    <div class="error-message" id="oldPasswordError"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Password Baru<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        class="form-input" 
                        id="newPassword"
                        placeholder="Masukkan password baru"
                        required
                    >
                    <div class="error-message" id="newPasswordError"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Konfirmasi Password<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        class="form-input" 
                        id="confirmPassword"
                        placeholder="Konfirmasi password baru"
                        required
                    >
                    <div class="error-message" id="confirmPasswordError"></div>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-cancel" onclick="resetForm()">Batal</button>
                <button type="submit" class="btn btn-submit">Ubah</button>
            </div>
        </form>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function resetForm() {
            document.getElementById('changePasswordForm').reset();
            hideAllErrors();
        }

        function hideAllErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
        }

        function showError(fieldId, message) {
            const errorElement = document.getElementById(fieldId + 'Error');
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }

        function validateForm() {
            hideAllErrors();
            let isValid = true;

            const oldPassword = document.getElementById('oldPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validate old password
            if (!oldPassword) {
                showError('oldPassword', 'Password lama harus diisi');
                isValid = false;
            }

            // Validate new password
            if (!newPassword) {
                showError('newPassword', 'Password baru harus diisi');
                isValid = false;
            } else if (newPassword.length < 6) {
                showError('newPassword', 'Password minimal 6 karakter');
                isValid = false;
            } else if (newPassword === oldPassword) {
                showError('newPassword', 'Password baru harus berbeda dari password lama');
                isValid = false;
            }

            // Validate confirm password
            if (!confirmPassword) {
                showError('confirmPassword', 'Konfirmasi password harus diisi');
                isValid = false;
            } else if (confirmPassword !== newPassword) {
                showError('confirmPassword', 'Konfirmasi password tidak cocok');
                isValid = false;
            }

            return isValid;
        }

        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (validateForm()) {
                // Simulate API call
                const submitButton = this.querySelector('.btn-submit');
                submitButton.disabled = true;
                submitButton.textContent = 'Mengubah...';

                setTimeout(() => {
                    // Show success message
                    document.getElementById('successMessage').classList.add('show');
                    
                    // Reset form
                    this.reset();
                    submitButton.disabled = false;
                    submitButton.textContent = 'Ubah';

                    // Hide success message after 3 seconds
                    setTimeout(() => {
                        document.getElementById('successMessage').classList.remove('show');
                        // Optionally redirect back
                        // window.history.back();
                    }, 3000);
                }, 1000);
            }
        });

        // Real-time validation
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && confirmPassword !== newPassword) {
                showError('confirmPassword', 'Konfirmasi password tidak cocok');
            } else {
                document.getElementById('confirmPasswordError').classList.remove('show');
            }
        });
    </script>
</body>
</html>
