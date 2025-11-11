<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1ec7e6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Bantuan - Sistem Absensi</title>
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

        .header {
            background: linear-gradient(135deg, #1ec7e6, #0ea5e9);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        .content {
            padding: 20px;
        }

        .search-box {
            background: white;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .search-input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 14px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .faq-section {
            margin-bottom: 24px;
        }

        .faq-item {
            background: white;
            border-radius: 12px;
            margin-bottom: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .faq-question {
            padding: 16px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s ease;
        }

        .faq-question:hover {
            background: #f9fafb;
        }

        .faq-q-text {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            flex: 1;
        }

        .faq-icon {
            font-size: 18px;
            color: #6b7280;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
        }

        .faq-a-text {
            padding: 0 16px 16px 16px;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        .help-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .help-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .help-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .help-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
        }

        .help-card-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .help-card-btn {
            background: #1ec7e6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .contact-btn {
            background: white;
            border: 2px solid #e5e7eb;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #374151;
        }

        .contact-btn:hover {
            border-color: #1ec7e6;
            background: #f0f9ff;
        }

        .contact-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .contact-label {
            font-size: 13px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-btn" onclick="goBack()">←</button>
        <div class="header-title">Bantuan</div>
    </div>

    <div class="content">
        <div class="search-box">
            <span>🔍</span>
            <input type="text" class="search-input" placeholder="Cari bantuan..." id="searchInput" oninput="searchFAQ()">
        </div>

        <div class="faq-section">
            <div class="section-title">
                <span>❓</span> Pertanyaan Umum (FAQ)
            </div>

            <div class="faq-item" data-keywords="absen clock in masuk">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <div class="faq-q-text">Bagaimana cara melakukan absensi?</div>
                    <div class="faq-icon">▼</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-a-text">
                        Untuk melakukan absensi:<br>
                        1. Buka menu "Absen" di dashboard<br>
                        2. Pilih "Clock In" untuk masuk atau "Clock Out" untuk pulang<br>
                        3. Ambil foto selfie Anda<br>
                        4. Pastikan lokasi GPS aktif<br>
                        5. Klik tombol "Absen"
                    </div>
                </div>
            </div>

            <div class="faq-item" data-keywords="lupa password reset">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <div class="faq-q-text">Lupa password, bagaimana cara reset?</div>
                    <div class="faq-icon">▼</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-a-text">
                        Untuk reset password:<br>
                        1. Di halaman login, klik "Lupa Password"<br>
                        2. Masukkan email terdaftar<br>
                        3. Cek email untuk link reset password<br>
                        4. Klik link dan buat password baru<br>
                        Atau hubungi HRD untuk bantuan.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-keywords="izin cuti sakit">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <div class="faq-q-text">Bagaimana cara mengajukan izin/cuti?</div>
                    <div class="faq-icon">▼</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-a-text">
                        Untuk mengajukan izin:<br>
                        1. Buka menu "Izin"<br>
                        2. Klik "Buat Pengajuan Baru"<br>
                        3. Pilih jenis izin (Sakit/Cuti/Keperluan)<br>
                        4. Isi tanggal dan alasan<br>
                        5. Upload surat keterangan (jika ada)<br>
                        6. Kirim dan tunggu persetujuan atasan
                    </div>
                </div>
            </div>

            <div class="faq-item" data-keywords="lembur overtime">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <div class="faq-q-text">Bagaimana cara mencatat lembur?</div>
                    <div class="faq-icon">▼</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-a-text">
                        Untuk mencatat lembur:<br>
                        1. Buka menu "Clock Overtime"<br>
                        2. Mulai lembur sebelum jam kerja berakhir<br>
                        3. Akhiri lembur saat selesai<br>
                        4. Sistem akan otomatis menghitung jam lembur<br>
                        5. Data lembur akan muncul di riwayat
                    </div>
                </div>
            </div>

            <div class="faq-item" data-keywords="riwayat history absensi">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <div class="faq-q-text">Dimana saya bisa melihat riwayat absensi?</div>
                    <div class="faq-icon">▼</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-a-text">
                        Anda bisa melihat riwayat absensi di:<br>
                        - Menu "Riwayat" di dashboard<br>
                        - Menu "Laporan" untuk data lebih detail<br>
                        - Pilih periode yang ingin dilihat<br>
                        - Download laporan dalam format Excel/PDF
                    </div>
                </div>
            </div>

            <div class="faq-item" data-keywords="gps lokasi">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <div class="faq-q-text">Kenapa GPS tidak bisa terdeteksi?</div>
                    <div class="faq-icon">▼</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-a-text">
                        Solusi masalah GPS:<br>
                        1. Pastikan izin lokasi sudah diaktifkan<br>
                        2. Aktifkan GPS/Location di pengaturan HP<br>
                        3. Gunakan mode akurasi tinggi<br>
                        4. Coba keluar dan masuk ruangan<br>
                        5. Restart aplikasi browser
                    </div>
                </div>
            </div>

            <div class="faq-item" data-keywords="edit profil data">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <div class="faq-q-text">Bagaimana cara mengubah data profil?</div>
                    <div class="faq-icon">▼</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-a-text">
                        Untuk mengubah profil:<br>
                        1. Buka menu "Profil"<br>
                        2. Klik "Edit Profile"<br>
                        3. Ubah data yang diperlukan<br>
                        4. Klik "Simpan"<br>
                        Catatan: Beberapa data seperti ID dan Jabatan hanya bisa diubah oleh HRD.
                    </div>
                </div>
            </div>
        </div>

        <div class="section-title">
            <span>📞</span> Butuh Bantuan Lebih Lanjut?
        </div>

        <div class="help-card">
            <div class="help-card-header">
                <div class="help-icon">💬</div>
                <div class="help-card-title">Hubungi Kami</div>
            </div>
            <div class="help-card-text">
                Tim support kami siap membantu Anda. Pilih salah satu cara di bawah untuk menghubungi kami.
            </div>
            <div class="contact-grid">
                <a href="contact" class="contact-btn">
                    <div class="contact-icon">📧</div>
                    <div class="contact-label">Email</div>
                </a>
                <a href="tel:+6281234567890" class="contact-btn">
                    <div class="contact-icon">📱</div>
                    <div class="contact-label">Telepon</div>
                </a>
                <a href="https://wa.me/6281234567890" class="contact-btn" target="_blank">
                    <div class="contact-icon">💬</div>
                    <div class="contact-label">WhatsApp</div>
                </a>
                <a href="customer-report" class="contact-btn">
                    <div class="contact-icon">📝</div>
                    <div class="contact-label">Laporan</div>
                </a>
            </div>
        </div>

        <div class="help-card">
            <div class="help-card-header">
                <div class="help-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">📚</div>
                <div class="help-card-title">Panduan Lengkap</div>
            </div>
            <div class="help-card-text">
                Baca panduan lengkap penggunaan aplikasi untuk memaksimalkan fitur-fitur yang tersedia.
            </div>
            <button class="help-card-btn" onclick="alert('Panduan PDF akan segera tersedia untuk didownload')">
                Download Panduan PDF
            </button>
        </div>
    </div>

    <script>
        function toggleFAQ(element) {
            const faqItem = element.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Toggle current item
            if (!isActive) {
                faqItem.classList.add('active');
            }
        }

        function searchFAQ() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const text = item.querySelector('.faq-q-text').textContent.toLowerCase();
                const answer = item.querySelector('.faq-a-text').textContent.toLowerCase();
                const keywords = item.getAttribute('data-keywords');
                
                if (text.includes(searchTerm) || answer.includes(searchTerm) || keywords.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function goBack() {
            if (typeof smartGoBack === 'function') {
                smartGoBack('{{ route("dashboard") }}');
            } else {
                // Fallback navigation
                if (window.history.length > 1 && document.referrer && 
                    document.referrer !== window.location.href &&
                    !document.referrer.includes('login')) {
                    try {
                        window.history.back();
                    } catch (error) {
                        window.location.href = '{{ route("dashboard") }}';
                    }
                } else {
                    window.location.href = '{{ route("dashboard") }}';
                }
            }
        }
    </script>
</body>
</html>
