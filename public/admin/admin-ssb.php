<?php
include 'admin_auth.php';
include '../config.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create') {
    $stmt = $conn->prepare("INSERT INTO ssb (name, city, level, coach, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssd', $_POST['name'], $_POST['city'], $_POST['level'], $_POST['coach'], $_POST['price']);
    if ($stmt->execute()) $success = "SSB berhasil ditambahkan."; else $error = "Gagal menambahkan SSB.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
    $stmt = $conn->prepare("UPDATE ssb SET name=?, city=?, level=?, coach=?, price=? WHERE id=?");
    $stmt->bind_param('sssdsi', $_POST['name'], $_POST['city'], $_POST['level'], $_POST['coach'], $_POST['price'], $_POST['id']);
    if ($stmt->execute()) $success = "SSB berhasil diupdate."; else $error = "Gagal mengupdate SSB.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $stmt = $conn->prepare("DELETE FROM ssb WHERE id=?");
    $stmt->bind_param('i', $_POST['id']);
    if ($stmt->execute()) $success = "SSB berhasil dihapus."; else $error = "Gagal menghapus SSB.";
}

$res = $conn->query("SELECT * FROM ssb ORDER BY id DESC");
$ssb_list = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin — Kelola SSB</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="../Assets/logo-trans.png">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  html,body{height:100%;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial}
  .app-bg{background:linear-gradient(180deg,#071022 0%, #0b1220 60%, #071022 100%);}
  .card-glass{background:linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02)); backdrop-filter: blur(8px);}
  .accent{background:linear-gradient(90deg,#06b6d4, #7c3aed);}
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
    <a href="admin-ssb.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1 bg-slate-800">Data SSB</a>
    <a href="admin-user.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">Data Pengguna</a>
    <a href="admin-registrations.php" class="px-3 py-2 rounded-lg card-glass text-sm text-center mb-2 md:mb-0 md:mx-1">Registrations</a>
    <a href="../index.php" class="px-3 py-2 rounded-lg accent text-sm text-center shadow md:mx-1">Logout</a>
  </div>
</nav>

<main class="flex-1 w-full max-w-6xl mx-auto px-4 pb-10 mt-6">
  <?php if($success): ?><div class="p-3 bg-green-600 rounded mb-3"><?=htmlspecialchars($success)?></div><?php endif; ?>
  <?php if($error): ?><div class="p-3 bg-red-600 rounded mb-3"><?=htmlspecialchars($error)?></div><?php endif; ?>

  <div class="mb-6 p-4 bg-slate-800 rounded">
    <h2 class="font-semibold mb-2">Tambah SSB</h2>
    <form method="POST" class="grid gap-2 sm:grid-cols-2">
      <input name="name" placeholder="Nama SSB" required class="p-2 bg-slate-700 rounded w-full" />
      <input name="city" placeholder="Kota" required class="p-2 bg-slate-700 rounded w-full" />
      <input name="level" placeholder="Level" class="p-2 bg-slate-700 rounded w-full" />
      <input name="coach" placeholder="Coach" class="p-2 bg-slate-700 rounded w-full" />
      <input name="price" placeholder="Harga (angka)" type="number" step="0.01" class="p-2 bg-slate-700 rounded w-full" />
      <input type="hidden" name="action" value="create" />
      <button class="col-span-2 p-2 bg-cyan-600 rounded hover:bg-cyan-500 transition">Tambah SSB</button>
    </form>
  </div>

  <div class="bg-slate-800 p-4 rounded overflow-x-auto">
    <h2 class="font-semibold mb-3">Daftar SSB</h2>
    <table class="text-sm text-left text-slate-300 w-full border-collapse min-w-[600px]">
      <thead class="text-slate-400 border-b border-slate-700">
        <tr>
          <th class="py-2 px-1">#</th>
          <th class="px-1">Nama</th>
          <th class="px-1">Kota</th>
          <th class="px-1">Level</th>
          <th class="px-1">Coach</th>
          <th class="px-1">Harga</th>
          <th class="px-1">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($ssb_list as $s): ?>
        <tr class="border-b border-slate-700">
          <td class="py-2 px-1"><?=htmlspecialchars($s['id'])?></td>
          <td class="px-1"><?=htmlspecialchars($s['name'])?></td>
          <td class="px-1"><?=htmlspecialchars($s['city'])?></td>
          <td class="px-1"><?=htmlspecialchars($s['level'])?></td>
          <td class="px-1"><?=htmlspecialchars($s['coach'])?></td>
          <td class="px-1">Rp <?=number_format($s['price'],0,',','.')?></td>
          <td class="flex flex-wrap gap-1">
            <button onclick='openEdit(<?=json_encode($s)?>)' class="px-2 py-1 bg-yellow-500 rounded text-black text-xs">Edit</button>
            <form method="POST" onsubmit="return confirm('Hapus SSB ini?')">
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?= $s['id'] ?>" />
              <button type="submit" class="px-2 py-1 bg-red-600 rounded text-xs">Hapus</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal Edit -->
  <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
    <div class="bg-white p-6 rounded text-black w-full max-w-lg mx-2">
      <h3 class="font-bold mb-3">Edit SSB</h3>
      <form method="POST" id="editForm" class="grid gap-2">
        <input type="text" name="name" id="e_name" class="p-2 border rounded" required />
        <input type="text" name="city" id="e_city" class="p-2 border rounded" />
        <input type="text" name="level" id="e_level" class="p-2 border rounded" />
        <input type="text" name="coach" id="e_coach" class="p-2 border rounded" />
        <input type="number" step="0.01" name="price" id="e_price" class="p-2 border rounded" />
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="id" id="e_id" />
        <div class="flex gap-2 mt-2 justify-end">
          <button type="button" onclick="closeEdit()" class="px-3 py-2 bg-slate-400 rounded">Batal</button>
          <button type="submit" class="px-3 py-2 bg-cyan-600 rounded text-white hover:bg-cyan-500">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</main>

<footer class="py-6 text-center text-slate-400 text-sm border-t border-slate-800">© 2025 The 90' : Football Academy</footer>

<script>
  const menuToggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('menu');
  menuToggle.addEventListener('click', () => {
    menu.classList.toggle('hidden');
    menu.classList.toggle('flex');
  });

  function openEdit(obj){
    document.getElementById('e_id').value = obj.id;
    document.getElementById('e_name').value = obj.name;
    document.getElementById('e_city').value = obj.city;
    document.getElementById('e_level').value = obj.level;
    document.getElementById('e_coach').value = obj.coach;
    document.getElementById('e_price').value = obj.price;
    document.getElementById('editModal').classList.remove('hidden');
  }
  function closeEdit(){
    document.getElementById('editModal').classList.add('hidden');
  }
</script>

</body>
</html>
