<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);

include '../auth/db.php';

$result = $con->query("SELECT * FROM hadiah ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DaurAksi</title>
  <link rel="shortcut icon" href="../public/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/style.css" />
  <script src="https://kit.fontawesome.com/c5596080ef.js" crossorigin="anonymous"></script>
</head>

<body class="flex flex-col h-full w-dvw max-w-dvw min-h-dvh">
  <header id="header" class="flex items-center h-24 px-16 bg-green-700 max-h-24">
    <div id="logo_wrapper" class="flex items-center justify-start w-40 max-w-40">
      <img src="/dauraksi/public/logo.png" class="w-20 max-w-20 cursor-pointer" id="logo" onclick="logoClick()"
        alt="logo dauraksi">
    </div>
    <nav class="flex items-center justify-center flex-auto h-full min-h-full gap-8 text-xl text-white">
      <a href="/dauraksi/pages/edukasi.php"
        class="flex items-end h-full hover:-translate-y-0.5 hover:text-warning transition-all ease-in-out active:translate-y-0 active:scale-110 pb-6">Edukasi</a>
      <a href="/dauraksi/pages/layanan.php"
        class="flex items-end h-full hover:-translate-y-0.5 hover:text-warning transition-all ease-in-out active:translate-y-0 active:scale-110 pb-6">Layanan</a>
      <a href="/dauraksi/pages/hadiah.php"
        class="flex items-end h-full hover:-translate-y-0.5 hover:text-warning transition-all ease-in-out active:translate-y-0 active:scale-110 pb-6">Hadiah</a>
    </nav>
    <div id="auth_act" class="flex justify-end items-center w-40 gap-4 max-w-40">
      <?php if ($isLoggedIn): ?>
        <div class="avatar">
          <div
            class="w-16 rounded-full border border-warning overflow-hidden cursor-pointer hover:scale-125 transition-all ease-in-out duration-300">
            <img src="/dauraksi/public/avatar-default.jpeg" alt="Avatar" id="avatar" />
          </div>
        </div>
        <form action="/dauraksi/auth/logout.php" method="post" style="display: inline;">
          <button type="submit"
            class="transition-all ease-in-out btn btn-lg btn-warning hover:scale-125 hover:text-white active:opacity-75 active:translate-y-0.5">
            <i class="fa-solid fa-right-from-bracket"></i>
          </button>
        </form>
      <?php else: ?>
        <button id="login" onclick="handleLogin()"
          class="transition-all ease-in-out btn btn-lg btn-warning hover:scale-125 hover:text-white active:opacity-75 active:translate-y-0.5">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
        <button onclick="handleRegister()" id="register"
          class="transition-all ease-in-out btn btn-lg btn-warning hover:scale-125 hover:text-white active:opacity-75 active:translate-y-0.5">
          <i class="fa-solid fa-user-plus"></i>
        </button>
      <?php endif; ?>
    </div>
  </header>
  <main id="main"
    class="flex-grow flex flex-col h-full w-full relative pb-14 bg-[url('/dauraksi/public/edukasi.jpg')] bg-contain ">
    <?php

    $alert = $_SESSION['alert'] ?? "";
    unset($_SESSION['alert']);
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["redeem"])) {
      if (!$isLoggedIn) {
        $_SESSION['alert'] = "Anda harus login untuk menukar hadiah ini.";
      } else {
        $username = $_SESSION['username'];
        $redeemId = (int) $_POST["redeem_id"]; // aman dari injeksi
        // Dapatkan poin user
        $userRes = $con->query("SELECT points FROM users WHERE username = '$username'");
        $userPoints = $userRes->fetch_assoc()['points'] ?? null;
        // Dapatkan poin hadiah
        $giftRes = $con->query("SELECT point FROM hadiah WHERE id = $redeemId");
        $giftPoints = $giftRes->fetch_assoc()['point'] ?? null;
        if ($userPoints === null || $giftPoints === null) {
          $_SESSION['alert'] = "Terjadi kesalahan saat mendapatkan poin pengguna atau hadiah.";
        } else if ($userPoints < $giftPoints) {
          $_SESSION['alert'] = "Poin Anda tidak cukup untuk menukar hadiah ini.";
        } else {
          $con->query("UPDATE users SET points = points - $giftPoints WHERE username = '$username'");
          $con->query("INSERT INTO redeemhistory (username, hadiah_id) VALUES ('$username', $redeemId)");
          $_SESSION['alert'] = "Hadiah berhasil ditukar! silahkan klaim hadiah di tempat kami.";
        }
      }
      // 🔁 Redirect untuk hindari resubmission
      header("Location: hadiah.php");
      exit();
    }

    ?>
    <?php if (!empty($alert)): ?>
      <div id="alertBox" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 z-50 px-4 py-2 rounded shadow-lg
                <?= strpos($alert, 'berhasil') !== false ? 'bg-green-600 text-white' : 'bg-red-600 text-white' ?>">
        <?= $alert ?>
      </div>
    <?php endif; ?>
    <div class="flex-grow overflow-y-auto grid scrollbar-green">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 p-4 auto-rows-max">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="w-full">
            <div class="card w-full shadow-md overflow-hidden border border-green-200 h-full rounded-lg">
              <div class="relative group flex flex-col justify-between h-full text-white p-4 rounded-lg"
                style="background-image: url('<?= htmlspecialchars($row["imagePath"]) ?>'); background-size: cover; background-position: center;">
                <!-- Overlay -->
                <div
                  class="absolute inset-0 bg-black/40 group-hover:bg-black/60 group-hover:backdrop-blur-sm transition-all duration-300 rounded-lg z-0">
                </div>
                <!-- Content -->
                <div class="relative z-10">
                  <h2 class="text-lg font-semibold mb-2"><?= htmlspecialchars($row['title']) ?></h2>
                  <p class="text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <?= htmlspecialchars($row['description']) ?>
                  </p>
                </div>
                <!-- Point Button -->
                <form action="hadiah.php" method="POST">
                  <input type="hidden" name="redeem_id" value="<?= htmlspecialchars($row['id']) ?>">
                  <div class="relative z-10 mt-4 flex justify-end">
                    <button class="btn btn-warning text-white" type="submit" name="redeem">
                      Point: <?= htmlspecialchars($row["point"]) ?> P
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
  </main>
  <footer id="footer" class="flex items-center justify-center w-full h-12 text-center text-white bg-green-700 -z-10">
    &copy; DaurAksi. <span id="year"></span> </footer>
  <script src="/dauraksi/js/index.js"></script>
  <script>
    const alertBox = document.getElementById('alertBox');
    if (alertBox) {
      setTimeout(() => {
        alertBox.classList.add('opacity-0');
        setTimeout(() => alertBox.remove(), 1000);
      }, 2000);
    }
  </script>
</body>

</html>