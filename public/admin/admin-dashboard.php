<?php 
include 'admin_auth.php'; 
include '../config.php'; 
?> 

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="../Assets/logo-trans.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html, body {
      height: 100%;
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

    /* Loader animasi */
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

    /* Transisi loading */
    #dashboard-loading {
      transition: opacity 0.6s ease;
    }
  </style>
</head>

<body class="app-bg text-slate-100 antialiased min-h-screen flex flex-col relative overflow-hidden">

  <!-- Loading screen -->
  <div id="dashboard-loading" class="fixed inset-0 bg-slate-950 flex flex-col items-center justify-center z-50">
    <img src="../Assets/logo-trans.png" alt="Logo" class="w-20 h-auto animate-bounce mb-4">
    <div class="loader"></div>
    <p class="mt-3 text-slate-300 text-sm tracking-wide">Memuat Dashboard...</p>
  </div>

  <!-- Navbar -->
  <nav class="px-4 py-4 flex items-center justify-between max-w-6xl mx-auto w-full relative">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-2xl overflow-hidden shadow-lg flex-shrink-0">
        <img src="../Assets/logo.png" alt="Logo" class="w-full h-full object-cover">
      </div>
      <div>
        <span class="text-lg font-semibold block">
          Admin Panel — The 90' : Football Academy
        </span>
        <div class="text-xs text-slate-400">Manajemen Sistem</div>
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
      class="hidden md:flex flex-col md:flex-row absolute md:static 
             top-16 right-4 md:right-0 bg-slate-900 md:bg-transparent 
             p-4 md:p-0 rounded-xl shadow-lg md:shadow-none z-50"
    >
      <a href="admin-dashboard.php" 
         class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">
        Dashboard
      </a>
      <a href="admin-ssb.php" 
         class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">
        Data SSB
      </a>
      <a href="admin-user.php" 
         class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">
        Data Pengguna
      </a>
      <a href="admin-registrations.php" 
         class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">
        Registrations
      </a>
      <a href="../index.php" 
         class="px-3 py-2 rounded-lg accent text-sm text-center shadow md:mx-1">
        Logout
      </a>
    </div>
  </nav>

  <!-- Konten utama -->
  <main class="flex-1 w-full max-w-6xl mx-auto px-4 pb-10 mt-6">
    <div class="mt-8 bg-slate-800 p-6 rounded-lg shadow-lg">
      <p class="text-lg font-semibold">Selamat datang di area admin!</p>
      <p class="text-slate-400 mt-2">
        Gunakan menu di atas untuk mengelola data SSB dan pendaftaran pengguna.
      </p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="py-6 text-center text-slate-400 text-sm border-t border-slate-800">
    © 2025 The 90' : Football Academy
  </footer>

  <!-- Script -->
  <script>
    // Toggle menu responsif
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');
    menuToggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      menu.classList.toggle('flex');
    });

    // Efek loading dashboard
    window.addEventListener("load", () => {
      const loader = document.getElementById("dashboard-loading");
      setTimeout(() => {
        loader.style.opacity = "0";
        setTimeout(() => loader.remove(), 600);
      }, 2400); // durasi loading (ms)
    });
  </script>

</body>
</html>
