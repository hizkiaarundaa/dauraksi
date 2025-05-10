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
        <div className="avatar">
          <div class="lg:w-14 xl:w-16 rounded-full border border-warning overflow-hidden">
            <img src="public/avatar-default.jpeg" alt="Avatar" />
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
  <main id="main" class="flex flex-auto h-full w-dvw">
    <div></div>
  </main>
  <footer id="footer" class="flex items-center justify-center w-full h-12 text-center text-white bg-green-700 -z-10">
    &copy; DaurAksi. <span id="year"></span> </footer>
  <script src="/dauraksi/js/index.js"></script>
</body>

</html>