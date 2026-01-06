<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiPagu | Sistem informasi pengelolaan anggaran dan uang honor</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- favicon include -->
    <?php include './includes/header.php'; ?>
</head>
<body>
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Background Pattern -->
    <div class="login-bg-pattern"></div>

    <!-- Navigation -->
    <nav class="nav" id="navbar">
        <div class="container">
            <div class="nav-content">
                <!-- Logo dengan gambar -->
                <a href="index.php" class="logo">
                    <div class="logo-container">
                        <div class="logo-image">
                            <img src="./assets/img/logoSiPagu.png" alt="SiPagu Logo" class="logo-img">
                        </div>
                        <div class="logo-text">
                            Si<span>Pagu</span>
                        </div>
                    </div>
                </a>
                
                <!-- Modern Hamburger Menu -->
                <button class="hamburger-menu" id="hamburgerMenu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                
                <!-- Menu Overlay (Mobile) -->
                <div class="menu-overlay" id="menuOverlay"></div>
                
                <!-- Navigation Links -->
                <div class="nav-links" id="navLinks">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Login Content -->
    <main class="login-main">
        <div class="login-card fade-in">
            <!-- Login Header dengan Logo Foto -->
            <div class="login-header">
                <!-- Logo Foto saja (tanpa teks) -->
                <div class="login-logo-container">
                    <div class="login-logo-image">
                        <img src="assets/img/logoSiPagu.png" alt="SiPagu Logo" class="login-logo-img">
                    </div>
                </div>
                <h1 class="login-title">Masuk ke Sistem</h1>
                <p class="login-subtitle">Akses sistem terintegrasi untuk pengelolaan honor fakultas</p>
            </div>
            
            <!-- Login Form -->
            <div class="login-form">
                
                <form action="login_aksi.php" method="post">
                    <div class="form-group">
                        <label for="npp" class="form-label">NPP</label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" name="npp_user" class="form-control" placeholder="masukkan NPP" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" name="pw_user" class="form-control" placeholder="Masukkan kata sandi" required>
                            <button class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" value="1">
                            <label>Ingat saya</label>
                        </div>
                        <a href="#" class="forgot-password">Lupa kata sandi?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk ke Sistem
                    </button>
                    
                    <div class="login-footer">
                        <p>Belum punya akun? <a href="index.html#contact">Hubungi administrator</a></p>
                    </div>
                </form>
                
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <a href="index.php" class="footer-logo">
                        Si<span>Pagu</span>
                    </a>
                    <p class="footer-desc">Sistem Penghonoran Fakultas Terintegrasi yang menyelesaikan masalah sinkronisasi penghonoran dengan standar universitas dan perhitungan pajak yang akurat.</p>
                    
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            info@sipagu.ac.id
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            (021) 1234-5678
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            Gedung Administrasi Kampus
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="footer-links-title">Fitur Utama</h4>
                    <ul class="footer-links">
                        <li><a href="index.php#features"><i class="fas fa-circle" style="color: var(--info);"></i> Standarisasi Pagu</a></li>
                        <li><a href="index.php#features"><i class="fas fa-circle" style="color: var(--danger);"></i> Kalkulasi Pajak</a></li>
                        <li><a href="index.php#features"><i class="fas fa-circle" style="color: var(--success);"></i> Distribusi Digital</a></li>
                        <li><a href="index.php#features"><i class="fas fa-circle" style="color: var(--purple);"></i> Pelaporan</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="footer-links-title">Dukungan</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--warning);"></i> Panduan Pengguna</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--teal);"></i> FAQ</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--info);"></i> Kontak Teknis</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--success);"></i> Update Sistem</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="footer-links-title">Legal</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--purple);"></i> Kebijakan Privasi</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--primary);"></i> Syarat Layanan</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--danger);"></i> SLA</a></li>
                        <li><a href="#"><i class="fas fa-circle" style="color: var(--warning);"></i> Sertifikasi Keamanan</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2025 cik & az. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    <script src="assets/js/landing.js"></script>
    <script>
        // Pastikan DOM sudah sepenuhnya dimuat
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Login Page');
            
            // Toggle password visibility
            const toggleBtn = document.getElementById('togglePassword');
            
            if (toggleBtn) {
                console.log('Toggle button found');
                
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('Toggle button clicked');
                    
                    const passwordInput = document.getElementById('password');
                    const eyeIcon = this.querySelector('i');
                    
                    if (passwordInput) {
                        console.log('Password input found:', passwordInput.type);
                        
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.classList.remove('fa-eye');
                            eyeIcon.classList.add('fa-eye-slash');
                            console.log('Password changed to text');
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.classList.remove('fa-eye-slash');
                            eyeIcon.classList.add('fa-eye');
                            console.log('Password changed to password');
                        }
                    } else {
                        console.error('Password input not found!');
                    }
                });
            } else {
                console.error('Toggle button not found!');
            }
            
            // Optional: Form submission handling
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const nppInput = document.getElementById('npp');
                    const passwordInput = document.getElementById('password');
                    
                    if (!nppInput.value.trim()) {
                        e.preventDefault();
                        alert('Silakan masukkan NPP');
                        nppInput.focus();
                        return false;
                    }
                    
                    if (!passwordInput.value.trim()) {
                        e.preventDefault();
                        alert('Silakan masukkan kata sandi');
                        passwordInput.focus();
                        return false;
                    }
                    
                    // Menampilkan loading state
                    const loginButton = document.getElementById('loginButton');
                    if (loginButton) {
                        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                        loginButton.disabled = true;
                    }
                });
            }
        });
        
        // Fallback jika DOMContentLoaded tidak terpanggil
        window.addEventListener('load', function() {
            console.log('Window loaded');
            
            // Jika toggle button belum punya event listener, tambahkan lagi
            const toggleBtn = document.getElementById('togglePassword');
            if (toggleBtn && !toggleBtn.hasAttribute('data-listener-added')) {
                toggleBtn.setAttribute('data-listener-added', 'true');
                
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const passwordInput = document.getElementById('password');
                    const eyeIcon = this.querySelector('i');
                    
                    if (passwordInput && eyeIcon) {
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.className = 'fas fa-eye-slash';
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.className = 'fas fa-eye';
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>