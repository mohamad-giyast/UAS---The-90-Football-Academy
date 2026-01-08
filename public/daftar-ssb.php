<?php
session_start();
include 'config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['role'] ?? 'user';

// Ambil daftar SSB
$ssb_result = $conn->query("SELECT id, name, location, price FROM ssb ORDER BY name ASC");

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ssb_id = $_POST['ssb_id'];
    $payment_method = $_POST['payment_method'];
    $status = 'Menunggu';

    $conn->query("INSERT INTO registrations (user_id, ssb_id, status, payment_method, date_registered) 
                  VALUES ('$user_id', '$ssb_id', '$status', '$payment_method', NOW())");

    // Kirim trigger ke JavaScript
    echo "<script>localStorage.setItem('showPaymentSuccess', 'true'); window.location='daftar-ssb.php';</script>";
    exit();
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar SSB - The 90'</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="Assets/logo-trans.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html,body{height:100%;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial}
    .app-bg{background:linear-gradient(180deg,#071022 0%, #0b1220 60%, #071022 100%);}
    .card-glass{background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));backdrop-filter:blur(8px);}
    .accent{background:linear-gradient(90deg,#06b6d4,#7c3aed);}
    .loader {
      border: 3px solid rgba(255,255,255,0.2);
      border-top: 3px solid #06b6d4;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      animation: spin 0.9s linear infinite;
    }
    @keyframes spin {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}
    #dashboard-loading {transition: opacity 0.6s ease;}

    /* === Popup === */
    .popup-overlay {
      background: rgba(0,0,0,0.7);
      position: fixed;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 100;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    .popup-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }
    .popup-box {
      background: linear-gradient(180deg,#0f172a,#1e293b);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 1rem;
      padding: 2rem;
      text-align: center;
      width: 90%;
      max-width: 380px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.4);
      transform: scale(0.8);
      transition: transform 0.3s ease;
    }
    .popup-overlay.active .popup-box {
      transform: scale(1);
    }
  </style>
</head>

<body class="app-bg text-slate-100 antialiased min-h-screen flex flex-col relative overflow-hidden">

  <!-- Loading screen -->
  <div id="dashboard-loading" class="fixed inset-0 bg-slate-950 flex flex-col items-center justify-center z-50">
    <img src="Assets/logo-trans.png" alt="Logo" class="w-20 h-auto animate-bounce mb-4">
    <div class="loader"></div>
    <p class="mt-3 text-slate-300 text-sm tracking-wide">Memuat Halaman...</p>
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
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Menu utama (perbaikan responsif) -->
    <div 
      id="menu"
      class="hidden md:flex flex-col md:flex-row 
             absolute md:static top-full right-0 left-0 
             bg-slate-900 md:bg-transparent 
             p-4 md:p-0 mt-2 md:mt-0 
             rounded-xl md:rounded-none 
             shadow-lg md:shadow-none 
             z-50 transition-all duration-300 ease-in-out"
    >
      <a href="index.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-slate-700">Beranda</a>
      <a href="ssb-list.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-slate-700">Cari SSB</a>
      <a href="daftar-ssb.php" class="px-3 py-2 rounded-lg card-glass text-sm bg-slate-800 text-center mb-2 md:mb-0 md:mx-1">Daftar SSB</a>
      <a href="profile.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-slate-700">Profil</a>
      <?php if ($user_role === 'admin'): ?>
        <a href="admin/admin-dashboard.php" class="px-3 py-2 rounded-lg bg-purple-800 text-sm text-center mb-2 md:mb-0 md:mx-1 hover:bg-purple-700">Admin Panel</a>
      <?php endif; ?>
      <a href="logout.php" class="px-3 py-2 rounded-lg accent text-sm text-center shadow md:mx-1 hover:opacity-90">Keluar</a>
    </div>
  </nav>

  <!-- Konten -->
  <main class="flex-1 w-full max-w-5xl mx-auto px-4 pb-10 mt-6">
    <div class="card-glass rounded-2xl p-6 sm:p-8 mt-4">
      <h1 class="text-3xl font-bold mb-6">Formulir Pendaftaran SSB</h1>

      <form method="POST" class="space-y-5">
        <div>
          <label for="ssb_id" class="block text-sm mb-2 font-medium text-slate-300">Pilih Sekolah Sepak Bola</label>
          <select id="ssb_id" name="ssb_id" required class="w-full p-3 rounded-lg bg-slate-800 text-white">
            <option value="">-- Pilih SSB --</option>
            <?php while ($ssb = $ssb_result->fetch_assoc()): ?>
              <option value="<?= $ssb['id'] ?>" data-price="<?= $ssb['price'] ?>">
                <?= htmlspecialchars($ssb['name']) ?> - <?= htmlspecialchars($ssb['location']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div id="harga_box" class="hidden">
          <p class="text-slate-300 mt-2">Biaya pendaftaran: <span id="harga_text" class="font-semibold text-cyan-400"></span></p>
        </div>

        <div>
          <label for="payment_method" class="block text-sm mb-2 font-medium text-slate-300">Metode Pembayaran</label>
          <select id="payment_method" name="payment_method" required class="w-full p-3 rounded-lg bg-slate-800 text-white">
            <option value="">-- Pilih Metode Pembayaran --</option>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="DANA">DANA</option>
            <option value="Tunai">Tunai</option>
          </select>
        </div>

        <div id="payment_detail" class="hidden bg-slate-800 p-4 rounded-lg text-slate-300 text-sm"></div>

        <div class="pt-4">
          <button type="submit" class="px-6 py-2 accent rounded-lg text-white font-semibold hover:opacity-90">Daftar Sekarang</button>
        </div>
      </form>
    </div>
  </main>

  <footer class="py-6 text-center text-slate-400 text-sm border-t border-slate-800">
    © 2025 The 90' : Football Academy
  </footer>

  <!-- ✅ Popup sukses -->
  <div id="popup" class="popup-overlay">
    <div class="popup-box">
      <img src="Assets/check.png" alt="Sukses" class="w-16 mx-auto mb-3">
      <h2 class="text-xl font-semibold mb-2 text-cyan-400">Pendaftaran Berhasil!</h2>
      <p class="text-sm text-slate-300 mb-4">Permintaan kamu telah dikirim dan menunggu konfirmasi admin.</p>
      <button id="popup-ok" class="px-5 py-2 rounded-lg accent text-white font-semibold hover:opacity-90">OK</button>
    </div>
  </div>

  <script>
    // Toggle menu responsif
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');
    menuToggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      menu.classList.toggle('flex');
    });

    // Efek loading
    window.addEventListener("load", () => {
      const loader = document.getElementById("dashboard-loading");
      setTimeout(() => {
        loader.style.opacity = "0";
        setTimeout(() => loader.remove(), 600);
      }, 2000);
    });

    // Harga otomatis
    document.getElementById('ssb_id').addEventListener('change', function() {
      const selected = this.options[this.selectedIndex];
      const price = selected.getAttribute('data-price');
      const hargaBox = document.getElementById('harga_box');
      const hargaText = document.getElementById('harga_text');

      if (price) {
        hargaBox.classList.remove('hidden');
        hargaText.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
      } else {
        hargaBox.classList.add('hidden');
      }
    });

    // Detail pembayaran
    document.getElementById('payment_method').addEventListener('change', function() {
      const detailBox = document.getElementById('payment_detail');
      const value = this.value;
      detailBox.classList.remove('hidden');

      if (value === 'Transfer Bank') {
        detailBox.innerHTML = `
          <p>Silakan transfer ke rekening berikut:</p>
          <p><strong>Bank BCA</strong> - No. Rek: <strong>1234567890</strong> a.n. The 90' Academy</p>
          <p>Kirim bukti transfer ke admin melalui WhatsApp untuk konfirmasi.</p>`;
      } else if (value === 'DANA') {
        detailBox.innerHTML = `
          <p>Pembayaran melalui DANA:</p>
          <p><strong>Nomor DANA:</strong> 0812-3456-7890 a.n. The 90' Academy</p>`;
      } else if (value === 'Tunai') {
        detailBox.innerHTML = `
          <p>Silakan lakukan pembayaran tunai langsung kepada pelatih atau petugas SSB saat pertemuan pertama.</p>`;
      } else {
        detailBox.classList.add('hidden');
      }
    });

    // ✅ Popup sukses
    const popup = document.getElementById('popup');
    const popupOk = document.getElementById('popup-ok');

    if (localStorage.getItem('showPaymentSuccess') === 'true') {
      popup.classList.add('active');
      localStorage.removeItem('showPaymentSuccess');
    }

    popupOk.addEventListener('click', () => {
      popup.classList.remove('active');
      window.location = 'profile.php';
    });
  </script>
</body>
</html>
