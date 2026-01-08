<?php 
session_start();
include 'config.php'; 

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$user_role = $_SESSION['role'] ?? 'guest';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>The 90' : Football Academy</title>
  <link rel="icon" type="image/png" href="Assets/logo-trans.png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html, body {
      min-height: 100%;
      overflow-y: auto;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial;
    }
    .app-bg {
      background: linear-gradient(180deg, #071022 0%, #0b1220 60%, #071022 100%);
    }
    .card-glass {
      background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
      backdrop-filter: blur(8px);
    }
    .accent {
      background: linear-gradient(90deg, #06b6d4, #7c3aed);
    }
    .loader {
      border: 3px solid rgba(255,255,255,0.2);
      border-top: 3px solid #06b6d4;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      animation: spin 0.9s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    #dashboard-loading {
      transition: opacity 0.6s ease;
    }

    /* Animasi gambar (zoom-in fade) */
    .img-animate {
      opacity: 0;
      transform: scale(0.9);
      transition: opacity 0.8s ease, transform 0.8s ease;
    }
    .img-animate.show {
      opacity: 1;
      transform: scale(1);
    }
  </style>
</head>

<body class="app-bg text-slate-100 antialiased min-h-screen flex flex-col relative">

  <!-- Splash Loading -->
  <div id="dashboard-loading" class="fixed inset-0 bg-slate-950 flex flex-col items-center justify-center z-50">
    <img src="Assets/logo-trans.png" alt="Logo" class="w-20 h-auto animate-bounce mb-4">
    <div class="loader"></div>
    <p class="mt-3 text-slate-300 text-sm tracking-wide">Memuat Dashboard...</p>
  </div>

  <!-- Navbar -->
  <nav class="px-4 py-4 flex items-center justify-between max-w-6xl mx-auto w-full relative">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-2xl overflow-hidden shadow-lg flex-shrink-0">
        <img src="Assets/logo.png" alt="Logo" class="w-full h-full object-cover">
      </div>
      <div>
        <span class="text-lg font-semibold block">The 90' : Football Academy</span>
        <div class="text-xs text-slate-400">Pendaftaran Sekolah Sepak Bola</div>
      </div>
    </div>

    <!-- Tombol hamburger (mobile) -->
    <button 
      id="menu-toggle" 
      class="md:hidden p-2 rounded-lg card-glass focus:outline-none focus:ring-2 focus:ring-cyan-500"
    >
      <svg xmlns="http://www.w3.org/2000/svg" 
           class="h-6 w-6" fill="none" 
           viewBox="0 0 24 24" 
           stroke="currentColor">
        <path stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Menu utama -->
    <div 
      id="menu"
      class="hidden md:flex flex-col md:flex-row 
             absolute md:static top-full left-0 right-0 
             bg-slate-900 md:bg-transparent 
             p-4 md:p-0 mt-2 md:mt-0 
             rounded-xl md:rounded-none 
             shadow-lg md:shadow-none 
             z-50 transition-all duration-300 ease-in-out"
    >
      <a href="index.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 bg-slate-800 hover:bg-slate-700">Beranda</a>
      <a href="ssb-list.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-slate-700">Cari SSB</a>
      <a href="daftar-ssb.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-slate-700">Daftar SSB</a>

      <?php if ($is_logged_in): ?>
        <a href="profile.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-slate-700">Profil</a>
        <?php if ($user_role === 'admin'): ?>
          <a href="admin/admin-dashboard.php" class="px-3 py-2 rounded-lg bg-purple-800 text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-purple-700">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php" class="px-3 py-2 rounded-lg accent text-sm text-center shadow md:mx-1 hover:opacity-90">Keluar</a>
      <?php else: ?>
        <a href="login.php" class="px-3 py-2 rounded-lg accent text-sm text-center text-white font-semibold shadow md:mx-1 hover:opacity-90">Masuk</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Konten utama -->
  <main class="flex-1 w-full max-w-5xl mx-auto px-4 pb-10 mt-6">

    <!-- Bagian dengan video background -->
    <div class="relative rounded-2xl overflow-hidden mt-4">
      <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="Assets/video1.mp4" type="video/mp4">
      </video>
      <div class="absolute inset-0 bg-black/50"></div>
      <div class="relative p-6 sm:p-8 z-10 text-white">
        <h1 class="text-3xl font-bold mb-4">Selamat Datang di The 90'</h1>
        <?php if ($is_logged_in): ?>
          <p class="text-slate-300 mb-3">Halo, <span class="font-semibold text-white"><?= htmlspecialchars($user_name) ?></span>!</p>
        <?php else: ?>
          <p class="text-slate-300 mb-3">Silakan login atau daftar untuk mulai bergabung dengan akademi sepak bola kami.</p>
        <?php endif; ?>
        <p class="text-slate-300 leading-relaxed">
          The 90' : Football Academy membantu anak-anak dan remaja menyalurkan bakat sepak bola mereka ke Sekolah Sepak Bola (SSB) terbaik di Indonesia.
          Kami menyediakan daftar SSB terverifikasi, pendaftaran mudah, dan fitur pembayaran yang aman.
        </p>
      </div>
    </div>

    <!-- Berita -->
    <section class="mt-10">
      <h2 class="text-2xl font-bold mb-5">⚽ Berita Sepak Bola Terbaru</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="card-glass rounded-xl p-4 hover:scale-[1.02] transition-transform duration-300">
          <img src="Assets/berita1.jpeg" alt="Berita 1" class="rounded-lg mb-3 w-full h-40 object-cover img-animate">
          <h3 class="font-semibold text-lg mb-2">Timnas U-17 Menang Telak di Laga Persahabatan</h3>
          <p class="text-slate-400 text-sm">Skuad muda Indonesia tampil gemilang dengan skor 4-1 melawan tim tamu dari Jepang.</p>
        </div>
        <div class="card-glass rounded-xl p-4 hover:scale-[1.02] transition-transform duration-300">
          <img src="Assets/berita2.jpeg" alt="Berita 2" class="rounded-lg mb-3 w-full h-40 object-cover img-animate">
          <h3 class="font-semibold text-lg mb-2">SSB Lokal Berhasil Masuk Kompetisi Nasional</h3>
          <p class="text-slate-400 text-sm">Beberapa SSB binaan The 90' kini berkesempatan tampil di kejuaraan nasional usia dini.</p>
        </div>
        <div class="card-glass rounded-xl p-4 hover:scale-[1.02] transition-transform duration-300">
          <img src="Assets/berita3.jpeg" alt="Berita 3" class="rounded-lg mb-3 w-full h-40 object-cover img-animate">
          <h3 class="font-semibold text-lg mb-2">Latihan Fisik Penting untuk Pemain Muda</h3>
          <p class="text-slate-400 text-sm">Pelatih menekankan pentingnya disiplin dan latihan fisik teratur sejak usia dini.</p>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-slate-900 border-t border-slate-800">
    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
      <div>
        <h3 class="text-lg font-semibold mb-3 text-white">Tentang Kami</h3>
        <p class="text-slate-400 text-sm leading-relaxed">
          The 90' : Football Academy adalah platform yang membantu generasi muda menyalurkan bakat sepak bola mereka ke berbagai SSB terbaik di Indonesia.
        </p>
      </div>

      <div>
        <h3 class="text-lg font-semibold mb-3 text-white">Kontak</h3>
        <ul class="text-slate-400 text-sm space-y-2">
          <li>Email: <a href="mailto:info@the90academy.com" class="text-cyan-400 hover:underline">info@the90academy.com</a></li>
          <li>Telepon: +62 812 3456 7890</li>
          <li>Alamat: Sumedang, Jawa Barat</li>
        </ul>
      </div>

      <div>
        <h3 class="text-lg font-semibold mb-3 text-white">Tautan Cepat</h3>
        <ul class="text-slate-400 text-sm space-y-2">
          <li><a href="index.php" class="hover:text-cyan-400">Beranda</a></li>
          <li><a href="ssb-list.php" class="hover:text-cyan-400">Daftar SSB</a></li>
          <li><a href="daftar-ssb.php" class="hover:text-cyan-400">Pendaftaran</a></li>
          <li><a href="login.php" class="hover:text-cyan-400">Masuk</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-lg font-semibold mb-3 text-white">Ikuti Kami</h3>
        <div class="flex gap-4 mt-2">
          <a href="#" class="text-slate-400 hover:text-cyan-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.2 4.2 0 001.84-2.32c-.81.48-1.7.83-2.65 1A4.18 4.18 0 0015.5 4c-2.33 0-4.21 1.93-4.21 4.31 0 .34.04.67.11.98-3.5-.18-6.61-1.9-8.69-4.52-.36.65-.56 1.4-.56 2.21 0 1.52.75 2.86 1.89 3.65-.7-.02-1.36-.22-1.94-.54v.05c0 2.12 1.46 3.89 3.39 4.29-.36.1-.74.15-1.13.15-.28 0-.55-.03-.81-.08.55 1.75 2.15 3.02 4.05 3.06A8.37 8.37 0 012 19.54a11.8 11.8 0 006.29 1.88c7.55 0 11.68-6.4 11.68-11.94v-.55c.8-.6 1.5-1.33 2.06-2.17z"/></svg>
          </a>
        </div>
      </div>
    </div>

    <div class="border-t border-slate-800 py-4 text-center text-slate-500 text-sm">
      © 2025 The 90' : Football Academy — All Rights Reserved
    </div>
  </footer>

  <!-- Script -->
  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');
    menuToggle.addEventListener('click', () => {
      const isHidden = menu.classList.contains('hidden');
      if (isHidden) {
        menu.classList.remove('hidden');
        menu.classList.add('flex');
      } else {
        menu.classList.add('hidden');
        menu.classList.remove('flex');
      }
    });

    // Splash screen + animasi gambar
    window.addEventListener('load', () => {
      const loader = document.getElementById('dashboard-loading');
      loader.style.opacity = '0';
      setTimeout(() => loader.style.display = 'none', 600);

      const imgs = document.querySelectorAll('.img-animate');
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('show');
          } else {
            entry.target.classList.remove('show');
          }
        });
      }, { threshold: 0.3 });

      imgs.forEach(img => observer.observe(img));
    });
  </script>
</body>
</html>
