<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Edit Profile - Sistem Absensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            width: 100%;
            max-width: 393px;
            min-height: 100vh;
            margin: 0 auto;
            overflow-y: auto;
        }
        
        @media (min-width: 394px) {
            body {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
        }

        /* Profile Photo Section */
        .profile-photo-section {
            background: white;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-photo-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-image: url('assets/image/account-circle.svg');
            background-size: cover;
            background-position: center;
            border: 4px solid #e5e7eb;
        }

        .change-photo-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #1ec7e6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid white;
            font-size: 16px;
        }

        .change-photo-text {
            margin-top: 12px;
            font-size: 14px;
            color: #1ec7e6;
            cursor: pointer;
            font-weight: 500;
        }

        /* Form Section */
        .form-section {
            background: white;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #374151;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background-color: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #1ec7e6;
            box-shadow: 0 0 0 3px rgba(30, 199, 230, 0.1);
        }

        .form-input:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
            color: #9ca3af;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background-color: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .form-select:focus {
            outline: none;
            border-color: #1ec7e6;
            box-shadow: 0 0 0 3px rgba(30, 199, 230, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            resize: vertical;
            min-height: 80px;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #1ec7e6;
            box-shadow: 0 0 0 3px rgba(30, 199, 230, 0.1);
        }

        .info-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Buttons */
        .button-group {
            padding: 20px;
            background: white;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .btn-save {
            background: #1ec7e6;
            color: white;
        }

        .btn-save:hover {
            background: #0ea5e9;
        }

        .btn-save:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        /* Success Message */
        .success-message {
            background: #10b981;
            color: white;
            padding: 12px 20px;
            text-align: center;
            font-size: 14px;
            display: none;
        }

        .success-message.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">Edit Profile</div>
    </div>

    <div class="success-message" id="successMessage">
        ✓ Profile berhasil diperbarui
    </div>

    <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="profile-photo-section">
            <div class="profile-photo-wrapper">
                <div class="profile-photo" id="profilePhoto" style="background-image: url('{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/image/account-circle.svg') }}');"></div>
                <button type="button" class="change-photo-btn" onclick="changePhoto()">📷</button>
            </div>
            <div class="change-photo-text" onclick="changePhoto()">Ubah Foto Profile</div>
            <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;" onchange="handlePhotoChange(event)">
        </div>

        <div class="form-section">
            <div class="form-group">
                <label class="form-label">ID Karyawan</label>
                <input type="text" class="form-input" value="{{ $user->employee_id ?? '' }}" disabled>
                <div class="info-text">ID karyawan tidak dapat diubah</div>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-input" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="tel" class="form-input" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Jabatan</label>
                <input type="text" class="form-input" value="{{ $user->roles->first()->description ?? 'Karyawan' }}" disabled>
                <div class="info-text">Hubungi HR untuk perubahan jabatan</div>
            </div>
            <div class="form-group">
                <label class="form-label">Departemen</label>
                <input type="text" class="form-input" value="{{ $user->department ?? '-' }}" disabled>
                <div class="info-text">Hubungi HR untuk perubahan departemen</div>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-input" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea class="form-textarea" name="address">{{ old('address', $user->address) }}</textarea>
            </div>
        </div>
        
        <div class="button-group">
            <button type="button" class="btn btn-cancel" onclick="cancelEdit()">Batal</button>
            <button type="submit" class="btn btn-save">Simpan</button>
        </div>
    </form>

    <script src="{{ asset('components/popup.js') }}"></script>
    <script>
        // Fallback function if popup.js fails to load
        if (typeof smartGoBack === 'undefined') {
            function smartGoBack(fallbackUrl) {
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = fallbackUrl;
                    }
                } else {
                    window.location.href = fallbackUrl;
                }
            }
        }

        function goBack() {
            smartGoBack('{{ route("profile.show") }}');
        }
        function changePhoto() {
            document.getElementById('photoInput').click();
        }
        function handlePhotoChange(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePhoto').style.backgroundImage = `url(${e.target.result})`;
                }
                reader.readAsDataURL(file);
            }
        }
        function cancelEdit() {
            if (confirm('Batalkan perubahan? Data yang belum disimpan akan hilang.')) {
                goBack();
            }
        }
    </script>
</body>
</html>
