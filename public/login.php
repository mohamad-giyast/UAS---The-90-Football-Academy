<?php
session_start();
include "config.php";

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $is_admin = isset($_POST["is_admin"]);

    // Ambil user berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
  
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if ($is_admin && $user['role'] != 'admin') {
                $login_error = "Akun ini bukan admin!";
            } else {
                if ($user['role'] == 'admin') {
                    header("Location: admin/admin-dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            }
        } else {
            $login_error = "Password salah!";
        }
    } else {
        $login_error = "Email tidak ditemukan!";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - The 90' Football Academy</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="Assets/logo-trans.png">
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

  <!-- ✅ Login Form (TIDAK DIUBAH SAMA SEKALI) -->
  <div class="card-glass rounded-2xl p-6 sm:p-8 w-full max-w-md shadow-xl">
    <h1 class="text-2xl font-bold text-center mb-2">Login</h1>
    <p class="text-center text-slate-400 mb-6">The 90' Football Academy</p>

    <?php if ($login_error): ?>
      <div class="bg-red-700/50 text-red-200 px-3 py-2 rounded mb-4 text-sm">
        <?= htmlspecialchars($login_error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 text-sm">Email</label>
        <input type="email" name="email" required class="w-full px-3 py-2 rounded bg-slate-900/60 border border-slate-700 focus:border-cyan-500 outline-none text-slate-100" />
      </div>

      <div>
        <label class="block mb-1 text-sm">Password</label>
        <input type="password" name="password" required class="w-full px-3 py-2 rounded bg-slate-900/60 border border-slate-700 focus:border-cyan-500 outline-none text-slate-100" />
      </div>

      <button type="submit" class="w-full py-2 rounded bg-cyan-600 hover:bg-cyan-700 text-white font-semibold">Masuk</button>

      <div class="text-center text-sm text-slate-400 mt-4">
        Belum punya akun? <a href="register.php" class="text-cyan-400 hover:underline">Daftar di sini</a>
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
