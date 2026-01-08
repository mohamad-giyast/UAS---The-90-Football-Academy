<?php
include 'admin_auth.php';
include '../config.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $id = intval($_POST['delete_user_id']);
    $stmt = $conn->prepare('DELETE FROM users WHERE id = ? AND role <> "admin"');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header('Location: admin-user.php?deleted=1');
        exit;
    } else {
        $error = 'Gagal menghapus pengguna.';
    }
}

// Fetch users (exclude admin)
$result = $conn->query('SELECT * FROM users WHERE role <> "admin" ORDER BY id DESC');
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin - Data Pengguna</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="../Assets/logo-trans.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html,body{height:100%;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial}
    .app-bg{background:linear-gradient(180deg,#071022 0%, #0b1220 60%, #071022 100%);}
    .card-glass{background:linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));backdrop-filter:blur(8px);}
    .accent{background:linear-gradient(90deg,#06b6d4,#7c3aed);}
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
        <span class="text-lg font-semibold block">Admin Panel — The 90' : Football Academy</span>
        <div class="text-xs text-slate-400">Manajemen Sistem</div>
      </div>
    </div>

    <!-- Tombol hamburger (mobile) -->
    <button id="menu-toggle" class="md:hidden p-2 rounded-lg card-glass focus:outline-none focus:ring-2 focus:ring-cyan-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Menu utama -->
    <div id="menu" class="hidden md:flex flex-col md:flex-row absolute md:static top-16 right-4 md:right-0 bg-slate-900 md:bg-transparent p-4 md:p-0 rounded-xl shadow-lg md:shadow-none z-50">
      <a href="admin-dashboard.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">Dashboard</a>
      <a href="admin-ssb.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">Data SSB</a>
      <a href="admin-user.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 bg-slate-700/60 font-semibold">Data Pengguna</a>
      <a href="admin-registrations.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">Registrations</a>
      <a href="../index.php" class="px-3 py-2 rounded-lg accent text-sm text-center shadow md:mx-1">Logout</a>
    </div>
  </nav>

  <!-- Konten utama -->
  <main class="flex-1 w-full max-w-6xl mx-auto px-4 pb-10 mt-6">
    <h1 class="text-2xl font-bold mb-6 text-center md:text-left">Data Pengguna</h1>

    <?php if (isset($_GET['deleted'])): ?>
      <div class="mb-4 px-4 py-3 rounded-lg bg-green-700 text-white max-w-md mx-auto md:mx-0">
        Pengguna berhasil dihapus.
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="mb-4 px-4 py-3 rounded-lg bg-red-700 text-white max-w-md mx-auto md:mx-0">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <div class="overflow-x-auto bg-slate-800 rounded-lg shadow-lg">
      <table class="min-w-full text-sm text-left border-collapse">
        <thead class="bg-slate-700 text-slate-200">
          <tr>
            <th class="px-6 py-3">Nama</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Role</th>
            <th class="px-6 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-700">
          <?php while($row = $result->fetch_assoc()): ?>
            <tr class="hover:bg-slate-700/50 transition">
              <td class="px-6 py-4"><?= htmlspecialchars($row['name']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['email']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['role']) ?></td>
              <td class="px-6 py-4 text-center">
                <button class="deleteBtn px-3 py-1 text-red-400 hover:text-red-300 text-sm transition" data-id="<?= $row['id'] ?>">Hapus</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal Konfirmasi -->
  <div id="confirmDelete" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden z-50 flex items-center justify-center">
    <div class="card-glass p-6 rounded-2xl max-w-sm w-full text-center shadow-lg">
      <h3 class="text-lg font-semibold mb-3">Yakin ingin menghapus pengguna ini?</h3>
      <p class="text-slate-400 text-sm mb-5">Tindakan ini akan menghapus data pengguna secara permanen.</p>
      <form method="POST" id="deleteForm">
        <input type="hidden" name="delete_user_id" id="delete_user_id" value="">
        <div class="flex justify-center gap-3">
          <button type="button" id="cancelBtn" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 transition">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-lg accent text-white hover:opacity-90 transition">Hapus</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="py-6 text-center text-slate-400 text-sm border-t border-slate-800">
    © <?= date('Y') ?> The 90' : Football Academy
  </footer>

  <!-- Script -->
  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');
    menuToggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      menu.classList.toggle('flex');
    });

    // Modal hapus
    const modal = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelBtn');
    document.querySelectorAll('.deleteBtn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('delete_user_id').value = btn.dataset.id;
        modal.classList.remove('hidden');
      });
    });
    cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
  </script>

</body>
</html>
