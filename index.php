<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiPagu | Sistem informasi pengelolaan anggaran dan uang honor</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="./assets/css/custom.css">

    <!-- favicon include -->
    <?php include './includes/header.php'; ?>
</head>
<body>
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Navigation -->
    <nav class="nav" id="navbar">
        <div class="container">
            <div class="nav-content">
                <a href="#" class="logo">
                    <div class="logo-container">
                        <!-- Logo Foto -->
                        <div class="logo-image">
                            <img src="./assets/img/logoSiPagu.png" alt="SiPagu Logo" class="logo-img">
                        </div>
                        <!-- Teks Logo -->
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
                    <a href="#home" class="nav-link active">
                        <i class="fas fa-home"></i>
                        Beranda
                    </a>
                    <a href="#features" class="nav-link">
                        <i class="fas fa-star"></i>
                        Fitur
                    </a>
                    <a href="#how-it-works" class="nav-link">
                        <i class="fas fa-cogs"></i>
                        Cara Kerja
                    </a>
                    <a href="#cta" class="nav-link">
                        <i class="fas fa-play-circle"></i>
                        Demo
                    </a>
                    <a href="login.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="spacer"></div>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content fade-in">
                <div class="hero-badge">
                    <i class="fas fa-lightbulb"></i>
                    Solusi Digitalisasi Penghonoran
                </div>
                
                <h1 class="hero-title">Sistem informasi pengelolaan anggaran dan uang honor</h1>
                <p class="hero-subtitle">SiPagu adalah sistem terpadu yang menyelesaikan masalah sinkronisasi penghonoran kegiatan fakultas dengan standar universitas dan perhitungan pajak yang akurat.</p>
                
                <div class="hero-actions">
                    <a href="#cta" class="btn btn-primary">
                        <i class="fas fa-play-circle"></i>
                        Lihat Demo
                    </a>
                    <a href="#features" class="btn btn-secondary">
                        <i class="fas fa-list-check"></i>
                        Lihat Fitur Lengkap
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title fade-in">Solusi <span>Terintegrasi</span> untuk Efisiensi Administrasi</h2>
            
            <div class="feature-grid">
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3 class="feature-title">Standarisasi Pagu Honor</h3>
                    <p class="feature-desc">Otomatisasi perhitungan honor berdasarkan standar universitas yang berlaku. Sistem akan menyesuaikan dengan ketentuan terbaru secara otomatis.</p>
                    <ul class="feature-benefits">
                        <li><i class="fas fa-check-circle"></i> Sinkron dengan regulasi universitas</li>
                        <li><i class="fas fa-check-circle"></i> Update standar otomatis</li>
                        <li><i class="fas fa-check-circle"></i> Validasi pagu real-time</li>
                    </ul>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="feature-title">Perhitungan Pajak Otomatis</h3>
                    <p class="feature-desc">Sistem menghitung PPh 21 secara akurat berdasarkan ketentuan perpajakan terkini. Minimalkan kesalahan dan siap untuk audit.</p>
                    <ul class="feature-benefits">
                        <li><i class="fas fa-check-circle"></i> Perhitungan PPh 21 otomatis</li>
                        <li><i class="fas fa-check-circle"></i> Update tarif pajak otomatis</li>
                        <li><i class="fas fa-check-circle"></i> Bukti potong elektronik</li>
                    </ul>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h3 class="feature-title">Distribusi Tanda Terima Digital</h3>
                    <p class="feature-desc">Kirim tanda terima honor secara digital ke penerima. Penerima dapat mengakses dan mengunduh dokumen kapan saja.</p>
                    <ul class="feature-benefits">
                        <li><i class="fas fa-check-circle"></i> Tanda terima digital</li>
                        <li><i class="fas fa-check-circle"></i> Notifikasi otomatis</li>
                        <li><i class="fas fa-check-circle"></i> Arsip terpusat dan aman</li>
                    </ul>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="feature-title">Rekap dan Pelaporan</h3>
                    <p class="feature-desc">Generate laporan keuangan komprehensif dengan satu klik. Siap untuk kebutuhan audit internal dan eksternal.</p>
                    <ul class="feature-benefits">
                        <li><i class="fas fa-check-circle"></i> Laporan real-time</li>
                        <li><i class="fas fa-check-circle"></i> Ekspor multiple format</li>
                        <li><i class="fas fa-check-circle"></i> Dashboard monitoring</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2 class="section-title fade-in">Cara Kerja <span>Sistem SiPagu</span></h2>
            
            <div class="steps">
                <div class="step fade-in">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3 class="step-title">Input Data Kegiatan</h3>
                        <p class="step-desc">Masukkan data kegiatan fakultas beserta peserta yang berhak menerima honor. Sistem akan menyesuaikan dengan standar honor universitas.</p>
                    </div>
                </div>
                
                <div class="step fade-in">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3 class="step-title">Validasi dan Kalkulasi Otomatis</h3>
                        <p class="step-desc">Sistem melakukan validasi pagu dan menghitung honor serta pajak secara otomatis berdasarkan ketentuan yang berlaku.</p>
                    </div>
                </div>
                
                <div class="step fade-in">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3 class="step-title">Distribusi Digital</h3>
                        <p class="step-desc">Tanda terima honor dikirim secara digital ke email penerima. Penerima dapat mengakses dan mengunduh dokumen dengan mudah.</p>
                    </div>
                </div>
                
                <div class="step fade-in">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3 class="step-title">Monitoring dan Pelaporan</h3>
                        <p class="step-desc">Pantau seluruh proses penghonoran melalui dashboard real-time dan generate laporan dengan format standar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="container">
            <h2 class="cta-title fade-in">Siap Mengoptimalkan Penghonoran Fakultas Anda?</h2>
            <p class="cta-subtitle fade-in">Bergabung dengan fakultas yang telah menggunakan SiPagu untuk standarisasi penghonoran yang lebih efisien dan akurat.</p>
            
            <div class="cta-stats fade-in">
                <div class="cta-stat">
                    <div class="cta-stat-number">12+</div>
                    <div class="cta-stat-label">Fakultas Tergabung</div>
                </div>
                <div class="cta-stat">
                    <div class="cta-stat-number">95%</div>
                    <div class="cta-stat-label">Pengurangan Kesalahan</div>
                </div>
                <div class="cta-stat">
                    <div class="cta-stat-number">70%</div>
                    <div class="cta-stat-label">Penghematan Waktu</div>
                </div>
            </div>
            
            <div class="cta-actions">
                <a href="login.html" class="btn btn-white fade-in">
                    <i class="fas fa-rocket"></i>
                    Mulai Sekarang
                </a>
                <a href="#contact" class="btn btn-outline-white fade-in">
                    <i class="fas fa-calendar-alt"></i>
                    Jadwalkan Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <a href="#" class="footer-logo">
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
                        <li><a href="#features"><i class="fas fa-circle" style="color: var(--info);"></i> Standarisasi Pagu</a></li>
                        <li><a href="#features"><i class="fas fa-circle" style="color: var(--danger);"></i> Kalkulasi Pajak</a></li>
                        <li><a href="#features"><i class="fas fa-circle" style="color: var(--success);"></i> Distribusi Digital</a></li>
                        <li><a href="#features"><i class="fas fa-circle" style="color: var(--purple);"></i> Pelaporan</a></li>
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


<script src="./assets/js/landing.js"></script>
</body>
</html>