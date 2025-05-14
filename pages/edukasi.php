<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
include '../auth/db.php';

$result = $con->query("SELECT * FROM edukasi ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DaurAksi</title>
  <link rel="shortcut icon" href="/dauraksi/public/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="/dauraksi/css/style.css" />
  <link rel="stylesheet" href="/dauraksi/css/edukasi.css">
  <script src="https://kit.fontawesome.com/c5596080ef.js" crossorigin="anonymous"></script>
</head>

<body class="flex flex-col min-h-dvh overflow-y-auto">

  <!-- Header -->
  <header id="header" class="flex items-center h-24 px-16 bg-green-700">
    <div class="flex items-center justify-start w-40">
      <img src="/dauraksi/public/logo.png" class="w-20 cursor-pointer" id="logo" onclick="logoClick()"
        alt="logo dauraksi">
    </div>
    <nav class="flex items-center justify-center flex-auto h-full gap-8 text-xl text-white">
      <a href="/dauraksi/pages/edukasi.php"
        class="flex items-end h-full hover:-translate-y-0.5 hover:text-warning transition-all ease-in-out active:translate-y-0 active:scale-110 pb-6">Edukasi</a>
      <a href="/dauraksi/pages/layanan.php"
        class="flex items-end h-full hover:-translate-y-0.5 hover:text-warning transition-all ease-in-out active:translate-y-0 active:scale-110 pb-6">Layanan</a>
      <a href="/dauraksi/pages/hadiah.php"
        class="flex items-end h-full hover:-translate-y-0.5 hover:text-warning transition-all ease-in-out active:translate-y-0 active:scale-110 pb-6">Hadiah</a>
    </nav>

    <div class="flex justify-end items-center w-40 gap-4">
      <?php if ($isLoggedIn): ?>
        <div class="avatar">
          <div
            class="w-16 rounded-full border border-warning overflow-hidden cursor-pointer hover:scale-125 transition-all ease-in-out duration-300">
            <img src="/dauraksi/public/avatar-default.jpeg" alt="Avatar" id="avatar" />
          </div>
        </div>
        <form action="/dauraksi/auth/logout.php" method="post">
          <button type="submit"
            class="btn btn-lg btn-warning transition-all ease-in-out hover:scale-125 hover:text-white active:opacity-75 active:translate-y-0.5">
            <i class="fa-solid fa-right-from-bracket"></i>
          </button>
        </form>
      <?php else: ?>
        <button onclick="handleLogin()"
          class="btn btn-lg btn-warning transition-all ease-in-out hover:scale-125 hover:text-white active:opacity-75 active:translate-y-0.5">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
        <button onclick="handleRegister()"
          class="btn btn-lg btn-warning transition-all ease-in-out hover:scale-125 hover:text-white active:opacity-75 active:translate-y-0.5">
          <i class="fa-solid fa-user-plus"></i>
        </button>
      <?php endif; ?>
    </div>
  </header>

  <!-- Main Content -->
  <main id="main"
    class="flex-grow flex flex-col h-full  w-full relative pb-14 bg-[url('/dauraksi/public/edukasi.jpg')] bg-contain">
    <div class="flex-grow overflow-y-auto grid  scrollbar-green">
      <div
        class="grid justify-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 p-4 auto-rows-max">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="w-full flex justify-center">
            <div class="card w-full max-w-sm bg-base-100 shadow-sm">
              <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($row['title']) ?></h2>
                <p class="text-justify">
                  <?= nl2br(htmlspecialchars(strlen($row['description']) > 100
                    ? substr($row['description'], 0, 100) . '...'
                    : $row['description'])) ?>
                </p>
                <div class="card-actions flex justify-center items-center mt-auto gap-2 text-center">
                  <p class="text-xs sm:text-sm font-bold border border-green-200 px-3 py-1 rounded-3xl text-green-500">
                    <?= htmlspecialchars($row['username']) ?>
                  </p>
                  <a href="/dauraksi/pages/detail_post.php?id=<?= urlencode($row['id']) ?>">
                    <button
                      class="btn-sm sm:btn-md btn btn-soft btn-warning border-warning bg-green-800 hover:bg-warning hover:border-green-200 hover:text-white hover:scale-105 transition-all ease-in-out">
                      Lihat Postingan
                    </button>
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <?php if ($isLoggedIn): ?>
        <div class="fixed right-2 bottom-12 sm:bottom-16 sm:p-2 xl:px-6 xl:pb-6 xl:pt-4 z-50">
          <a href="/dauraksi/pages/edukasi_new.php">
            <button
              class="group flex items-center bg-green-700 hover:bg-green-900 text-white rounded-full px-4 py-3 transition-all duration-300 overflow-hidden">
              <i class="fas fa-plus w-5 h-5 group-hover:hidden"></i>
              <span
                class="max-w-0 group-hover:max-w-xs overflow-hidden transition-all duration-300 group-hover:ml-2 whitespace-nowrap">
                Buat Postingan
              </span>
            </button>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer
    class="fixed bottom-0 left-0 z-10 flex items-center justify-center w-full h-12 text-white text-center bg-green-700">
    &copy; DaurAksi. <span id="year"></span>
  </footer>

  <script src="/dauraksi/js/index.js"></script>
</body>

</html>