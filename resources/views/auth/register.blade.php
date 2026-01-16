@extends('layouts.auth')

@section('title', 'Register - Sistem Absensi')

@section('content')
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
            overflow-y: auto;
            overflow-x: hidden; /* Prevent horizontal scroll */
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
                padding: 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                background: linear-gradient(135deg, #0ea5e9, #0284c7);
            }
            
            .header h1 {
                font-size: 32px;
                font-weight: 600;
                margin-bottom: 30px;
            }
            
            .illustration {
                width: 80%;
                height: 250px;
                margin: 0 auto;
                background-size: contain;
            }
            
            .register-container {
                padding: 40px 50px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                background: white;
                box-shadow: -5px 0 15px rgba(0,0,0,0.1);
                overflow-y: auto;
                max-height: 100vh;
            }
            
            .register-title {
                font-size: 32px;
                text-align: center;
                margin-bottom: 30px;
            }
            
            .input-group {
                margin-bottom: 20px;
            }
        }
        
        /* Desktop (1024px and up) */
        @media (min-width: 1024px) {
            .header {
                padding: 60px 60px;
            }
            
            .header h1 {
                font-size: 36px;
            }
            
            .illustration {
                height: 300px;
                width: 70%;
            }
            
            .register-container {
                padding: 60px 70px;
            }
            
            .register-title {
                font-size: 36px;
                margin-bottom: 40px;
            }
            
            .input-group input {
                padding: 18px 18px 18px 55px;
                font-size: 16px;
            }
            
            .register-btn {
                padding: 18px;
                font-size: 18px;
                margin: 20px 0 30px 0;
            }
        }
        
        /* Large Desktop (1440px and up) */
        @media (min-width: 1440px) {
            body {
                grid-template-columns: 55% 45%;
            }
            
            .header {
                padding: 80px;
            }
            
            .register-container {
                padding: 80px 90px;
            }
        }
        
        /* Mobile Landscape & Small Height Devices */
        @media (max-width: 767px) and (orientation: landscape), 
               (max-height: 600px) and (max-width: 767px) {
            .header {
                padding: 15px 20px 10px 20px;
            }
            
            .header h1 {
                font-size: 20px;
                margin-top: 5px;
            }
            
            .illustration {
                height: 80px;
                margin: 5px 0;
            }
            
            .register-container {
                padding: 15px 20px 20px 20px;
            }
            
            .register-title {
                font-size: 22px;
                margin-bottom: 15px;
            }
            
            .input-group {
                margin-bottom: 12px;
            }
            
            .input-group input {
                padding: 12px 12px 12px 45px;
                font-size: 14px;
            }
            
            .register-btn {
                padding: 12px;
                font-size: 16px;
                margin: 12px 0 15px 0;
            }
            
            .login-section {
                margin-top: 5px;
                margin-bottom: 10px;
                font-size: 14px;
            }
        }
    <!-- Auth Layout Container -->
    <div class="auth-layout">
        <!-- Header Section -->
        <div class="auth-header">
            <h1>REGISTER</h1>
        </div>

        <!-- Illustration -->
        <div class="auth-illustration" style="background-image: url('{{ asset('assets/image/register.png') }}');"></div>

        <!-- Register Container -->
        <div class="auth-container">
            <h2 class="auth-title">Register</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
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
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input type="text" id="employee_id" name="employee_id" placeholder="ID Card" value="{{ old('employee_id') }}" required />
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name') }}" required />
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Password" required autocomplete="new-password" />
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-phone"></i>
                    </span>
                    <input type="text" id="phone" name="phone" placeholder="Phone" value="{{ old('phone') }}" />
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <input type="text" id="address" name="address" placeholder="Address" value="{{ old('address') }}" />
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <input type="date" id="birth_date" name="birth_date" placeholder="Birth Date" value="{{ old('birth_date') }}" />
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-venus-mars"></i>
                    </span>
                    <select id="gender" name="gender" required style="width: 100%; padding: 18px 18px 18px 55px; border: none; border-radius: 25px; background-color: #e5e5e5; font-size: 1rem; color: #1f2937;">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <button type="submit" class="auth-btn">Register</button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}" class="register-link">Login</a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Register Page Specific Styles */
.auth-layout {
    min-height: 100vh;
    background-color: #f5f5f5;
    display: flex;
    flex-direction: column;
    overflow-x: hidden;
}

.auth-header {
    padding: 20px;
}

.auth-header h1 {
    font-size: 28px;
    font-weight: normal;
    letter-spacing: 2px;
    margin-top: 10px;
}

.auth-illustration {
    height: 150px;
    margin: 10px 0;
}

.auth-container {
    padding: 15px 20px 20px 20px;
}

.auth-title {
    font-size: 28px;
    margin-bottom: 15px;
    margin-top: 0;
}

.input-group {
    margin-bottom: 12px;
}

.input-group input,
.input-group select {
    padding: 15px 15px 15px 50px;
    font-size: 15px;
}

/* Responsive Register Layout */
@media (min-width: 768px) {
    .auth-layout {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 100vh;
    }

    .auth-header {
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
    }

    .auth-header h1 {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 30px;
    }

    .auth-illustration {
        width: 80%;
        height: 250px;
        margin: 0 auto;
        background-size: contain;
    }

    .auth-container {
        padding: 40px 50px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        background: white;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        max-height: 100vh;
    }

    .auth-title {
        font-size: 32px;
        text-align: center;
        margin-bottom: 30px;
    }

    .input-group {
        margin-bottom: 20px;
    }
}

@media (min-width: 1024px) {
    .auth-header {
        padding: 60px 60px;
    }

    .auth-header h1 {
        font-size: 36px;
    }

    .auth-illustration {
        height: 300px;
        width: 70%;
    }

    .auth-container {
        padding: 60px 70px;
    }

    .auth-title {
        font-size: 36px;
        margin-bottom: 40px;
    }

    .input-group input,
    .input-group select {
        padding: 18px 18px 18px 55px;
        font-size: 16px;
    }

    .auth-btn {
        padding: 18px;
        font-size: 18px;
        margin: 20px 0 30px 0;
    }
}

@media (min-width: 1440px) {
    .auth-layout {
        grid-template-columns: 55% 45%;
    }

    .auth-header {
        padding: 80px;
    }

    .auth-container {
        padding: 80px 90px;
    }
}

@media (max-width: 767px) and (orientation: landscape),
       (max-height: 600px) and (max-width: 767px) {
    .auth-header {
        padding: 15px 20px 10px 20px;
    }

    .auth-header h1 {
        font-size: 20px;
        margin-top: 5px;
    }

    .auth-illustration {
        height: 80px;
        margin: 5px 0;
    }

    .auth-container {
        padding: 15px 20px 20px 20px;
    }

    .auth-title {
        font-size: 22px;
        margin-bottom: 15px;
    }

    .input-group {
        margin-bottom: 12px;
    }

    .input-group input,
    .input-group select {
        padding: 12px 12px 12px 45px;
        font-size: 14px;
    }

    .auth-btn {
        padding: 12px;
        font-size: 16px;
        margin: 12px 0 15px 0;
    }

    .auth-footer {
        margin-top: 5px;
        margin-bottom: 10px;
        font-size: 14px;
    }
}
</style>
@endpush

@section('scripts')
    // Auto-hide success/error messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        });
    });
@endsection

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
