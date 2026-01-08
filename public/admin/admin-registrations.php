<?php 
include 'admin_auth.php';
include '../config.php';

// --- Aksi tombol ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'confirm') {
        $conn->query("UPDATE registrations SET status = 'Dikonfirmasi' WHERE id = $id");
    } elseif ($action === 'cancel') {
        $conn->query("UPDATE registrations SET status = 'Dibatalkan' WHERE id = $id");
    }

    header("Location: admin-registrations.php");
    exit();
}

// --- Ambil data ---
$query = "
    SELECT r.id, u.name AS user_name, s.name AS ssb_name, r.date_registered, r.status
    FROM registrations r
    JOIN users u ON r.user_id = u.id
    JOIN ssb s ON r.ssb_id = s.id
    ORDER BY r.date_registered DESC
";
$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Data Pendaftaran SSB - Admin</title>
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
  </style>
</head>

<body class="app-bg text-slate-100 antialiased min-h-screen flex flex-col">

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
      <a href="admin-dashboard.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">
        Dashboard
      </a>
      <a href="admin-ssb.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">
        Data SSB
      </a>
      <a href="admin-user.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">
        Data Pengguna
      </a>
      <a href="admin-registrations.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center bg-slate-700/50 font-semibold mb-2 md:mb-0 md:mx-1">
        Registrations
      </a>
      <a href="../index.php" class="px-3 py-2 rounded-lg accent text-sm text-center shadow md:mx-1">
        Logout
      </a>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="flex-1 w-full max-w-6xl mx-auto px-4 pb-10 mt-6">
    <h1 class="text-2xl font-bold mb-6 text-center md:text-left">Data Pendaftaran SSB</h1>

    <div class="overflow-x-auto bg-slate-800 rounded-lg shadow-lg">
      <table class="min-w-full text-sm text-left border-collapse">
        <thead class="bg-slate-700 text-slate-200">
          <tr>
            <th class="px-6 py-3">Nama Pengguna</th>
            <th class="px-6 py-3">SSB</th>
            <th class="px-6 py-3">Tanggal Daftar</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-700">
          <?php while ($row = mysqli_fetch_assoc($result)) { 
              $status = strtolower(trim($row['status']));
          ?>
          <tr class="hover:bg-slate-700/50 transition">
            <td class="px-6 py-4"><?= htmlspecialchars($row['user_name']) ?></td>
            <td class="px-6 py-4"><?= htmlspecialchars($row['ssb_name']) ?></td>
            <td class="px-6 py-4"><?= htmlspecialchars($row['date_registered']) ?></td>
            <td class="px-6 py-4 font-medium">
              <?php if (in_array($status, ['menunggu','pending'])) { ?>
                <span class="text-yellow-400">Menunggu Konfirmasi</span>
              <?php } elseif (in_array($status, ['dikonfirmasi','confirmed'])) { ?>
                <span class="text-green-400">Terdaftar</span>
              <?php } elseif (in_array($status, ['dibatalkan','cancelled'])) { ?>
                <span class="text-red-400">Dibatalkan</span>
              <?php } ?>
            </td>
            <td class="px-6 py-4 text-center">
              <?php if (in_array($status, ['menunggu','pending'])) { ?>
                <a href="?action=confirm&id=<?= $row['id'] ?>" class="px-3 py-2 bg-green-600 rounded hover:bg-green-700 text-white text-sm">Konfirmasi</a>
                <a href="?action=cancel&id=<?= $row['id'] ?>" class="px-3 py-2 bg-red-600 rounded hover:bg-red-700 text-white text-sm" onclick="return confirm('Batalkan pendaftaran ini?')">Batalkan</a>
              <?php } elseif (in_array($status, ['dikonfirmasi','confirmed'])) { ?>
                <span class="text-green-500">✅ Terdaftar</span>
              <?php } elseif (in_array($status, ['dibatalkan','cancelled'])) { ?>
                <span class="text-red-500">❌ Dibatalkan</span>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <p class="text-sm text-slate-500 mt-6 text-center md:text-left">
      © <?= date('Y') ?> The90' Academy Admin Panel
    </p>
  </main>

  <!-- Script untuk toggle menu -->
  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');
    menuToggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      menu.classList.toggle('flex');
    });
  </script>

</body>
</html>
