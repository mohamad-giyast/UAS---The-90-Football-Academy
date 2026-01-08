<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND role = "admin" LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if ($password === $user['password'] || password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_role'] = $user['role'];
            header('Location: admin/admin-dashboard.php');
            exit;
        } else {
            $error = 'Password salah.';
        }
    } else {
        $error = 'Akun admin tidak ditemukan.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Admin - The 90' Football Academy</title>
  <link rel="icon" type="image/png" href="Assets/logo-trans.png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html,body{height:100%;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial}
    .app-bg{background:linear-gradient(180deg,#071022 0%, #0b1220 60%, #071022 100%);}
    .card-glass{background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));backdrop-filter:blur(8px);}
    
    /* ✅ Splash Screen Styles */
    #splash {
      position: fixed;
      inset: 0;
      background-color: #071022;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      z-index: 9999;
      transition: opacity 1s ease;
    }
    #splash img {
      width: 160px;
      height: auto;
      animation: bounce 1.6s infinite;
    }
    .loader {
      margin-top: 24px;
      border: 3px solid #fff;
      border-top-color: transparent;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    @keyframes bounce {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-12px); }
    }
  </style>
</head>
<body class="app-bg text-slate-100 antialiased flex items-center justify-center min-h-screen px-4">

  <!-- ✅ Splash Screen -->
  <div id="splash">
    <img src="Assets/logo-trans.png" alt="Logo The 90 Football Academy">
    <div class="loader"></div>
  </div>

  <!-- ✅ Login Form -->
  <div class="card-glass rounded-2xl p-6 sm:p-8 w-full max-w-md shadow-xl">
    <h1 class="text-2xl font-bold text-center mb-2">Login Admin</h1>
    <p class="text-center text-slate-400 mb-6">The 90' Football Academy</p>

    <?php if ($error): ?>
      <div class="bg-red-700/50 text-red-200 px-3 py-2 rounded mb-4 text-sm">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 text-sm">Email</label>
        <input type="email" name="email" required 
               class="w-full px-3 py-2 rounded bg-slate-900/60 border border-slate-700 focus:border-cyan-500 outline-none text-slate-100" />
      </div>

      <div>
        <label class="block mb-1 text-sm">Password</label>
        <input type="password" name="password" required 
               class="w-full px-3 py-2 rounded bg-slate-900/60 border border-slate-700 focus:border-cyan-500 outline-none text-slate-100" />
      </div>

      <button type="submit" class="w-full py-2 rounded bg-cyan-600 hover:bg-cyan-700 text-white font-semibold">
        Masuk
      </button>

      <div class="text-center text-sm text-slate-400 mt-4">
        Kembali ke <a href="index.php" class="text-cyan-400 hover:underline">Halaman Utama</a>
      </div>
    </form>
  </div>

  <!-- ✅ Script Splash -->
  <script>
    window.addEventListener("load", () => {
      setTimeout(() => {
        const splash = document.getElementById("splash");
        splash.style.opacity = "0";
        setTimeout(() => splash.style.display = "none", 800);
      }, 2500); // tampil 2.5 detik
    });
  </script>

</body>
</html>
