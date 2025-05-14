<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DaurAksi</title>
  <link rel="shortcut icon" href="../public/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="/dauraksi/css/layanan.css">
  <script src="/dauraksi/js/index.js" defer></script>
  <script src="https://kit.fontawesome.com/c5596080ef.js" crossorigin="anonymous"></script>
</head>

<body class="flex flex-col h-full w-dvw max-w-dvw min-h-dvh">
  <?php
  if (!$isLoggedIn) {
    echo "<script>handleLogin()</script>";
  }
  ?>
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



  <!-- Main -->
  <main id="main" class="flex-grow flex flex-col h-full w-full ">
    <div id="layanan-form" class="flex flex-grow  ">
      <div class="flex flex-grow p-8">
        <!-- todo FORM -->
        <form action="layanan.php" method="POST"
          class="flex flex-col gap-8 w-full border-2 border-green-500 p-8 rounded-xl text-white min-h-0 relative">
          <h2 class="text-2xl font-bold  ">Layanan Penjemputan Sampah</h2>
          <?php
          include("../auth/db.php");
          if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
            $username = $_POST["username"];
            $alamat = $_POST["alamat"];
            $sampahTotal = $_POST["jumlah_sampah"];
            $errors = [];
            if (empty($username) || empty($alamat) || empty($sampahTotal)) {
              $errors[] = "Semua field wajib diisi.";
            } elseif (!is_numeric($sampahTotal)) {
              $errors[] = "Jumlah sampah harus berupa angka.";
            }

            if (count($errors) > 0) {
              foreach ($errors as $error) {
                echo "<div id='alertBox' class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-red-600 text-white p-4 rounded-lg shadow-lg transition-opacity duration-300'>$error</div>";
              }
            } else {
              $result = "INSERT INTO LAYANAN(username, alamat, sampahTotal) VALUES('$username', '$alamat', '$sampahTotal')";
              if (mysqli_query($con, $result)) {
                // Tambahkan 15 poin ke user
                $addPoints = "UPDATE USERS SET points = points + 15 WHERE username = '$username'";
                mysqli_query($con, $addPoints);
                echo "<div id='alertBox' class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-green-600 text-white p-4 rounded-lg shadow-lg transition-opacity duration-300'>Layanan diproses...<br> Anda mendapatkan 15 point 🎉!</div>";
                echo "<script>
                        setTimeout(() => {
                        window.location.href = '/dauraksi/pages/hadiah.php';
                        }, 2500);
                      </script>";
              } else {
                echo "<div id='alertBox' class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-red-600 text-white p-4 rounded-lg shadow-lg transition-opacity duration-300'>Gagal menyimpan data.</div>";
              }
            }
          }
          ?>
          <div class="flex-grow  gap-8 flex flex-col">
            <div class="grid place-content-center">
              <label class="floating-label bg-transparent" for="alamat">
                <span class="!bg-black !text-xl border border-success !rounded-xl !px-4">Alamat</span>
                <input type="text" name="alamat" class="input input-success w-xl bg-transparent text-white "
                  placeholder="Pilih alamat" list="alamat" />
                <datalist id="alamat">
                  <option value="Mapanget">Mapanget</option>
                  <option value="Malalayang">Malalayang</option>
                  <option value="Singkil">Singkil</option>
                  <option value="Paal 2">Paal 2</option>
                  <option value="Wanea">Wanea</option>
                </datalist>
              </label>
            </div>
            <!-- Jumlah Sampah -->
            <div class="grid place-content-center">
              <label class="floating-label bg-transparent" for="jumlah_sampah">
                <span class="!bg-black !text-xl border border-success !rounded-xl !px-4">Jumlah Sampah (Kg)</span>
                <input type="number" min="1" class="input input-success w-xl bg-transparent text-white "
                  id="jumlah_sampah" name="jumlah_sampah" placeholder="Jumlah Sampah" />
              </label>
            </div>
            <?php if ($isLoggedIn): ?>
              <div class="grid place-content-center">
                <label class="floating-label bg-transparent" for="username">
                  <span class="!bg-black !text-xl border border-success !rounded-xl !px-4">Username</span>
                  <input type="text" class="input input-success w-xl !bg-transparent text-white" id="username"
                    name="username" placeholder="Username" value="<?= htmlspecialchars($_SESSION['username']) ?>"
                    readonly />
                </label>
              </div>
            <?php endif; ?>
          </div>
          <div class="flex justify-end">
            <button class="btn btn-warning btn-xl" type="submit" name="submit"><i
                class="fa-solid fa-truck-fast"></i></button>
          </div>
        </form>
        <!-- FORM END -->
      </div>
    </div>
  </main>
  <footer id="footer" class="flex items-center justify-center w-full h-12 text-center text-white bg-green-700 -z-10">
    &copy; DaurAksi. <span id="year"></span> </footer>
  <?php if (!$isLoggedIn): ?>
    <script>
      window.addEventListener("DOMContentLoaded", () => {
        alert("Anda Harus Login Untuk Mengakses Layanan ini")
        handleLogin();
        window.location.href = "/dauraksi/" // pastikan handleLogin hanya dipanggil setelah JS sudah siap
      });
    </script>
  <?php endif; ?>
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