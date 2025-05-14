<?php
session_start();
$username = $_SESSION['username'];
include("../auth/db.php");
$query = "SELECT * FROM USERS WHERE username='$username'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
if (!$row) {
  echo "<script>alert('Pengguna tidak ditemukan.');</script>";
  exit();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="/dauraksi/public/logo.png" type="image/x-icon">
  <title>Profil Pengguna DaurAksi</title>
  <link rel="stylesheet" href="/dauraksi/css/style.css" />
</head>

<body class="bg-gray-50 text-gray-800 font-sans min-h-dvh w-full">
  <div class="flex justify-center items-stretch p-6 h-dvh">
    <div class="bg-white rounded-3xl shadow-xl p-8 max-w-4xl flex flex-col  w-full border border-green-200">
      <!-- Header Profil -->
      <div
        class="bg-white rounded-3xl shadow-xl p-8 max-w-4xl w-full border flex flex-col sm:flex-row justify-start gap-16 border-green-200">
        <!-- Foto Profil -->
        <div class="flex-shrink-0">
          <img class="w-32 h-32 rounded-full border-4 border-green-600 shadow-md"
            src="/dauraksi/public/avatar-default.jpeg" alt="Foto Pengguna" />
        </div>
        <!-- Informasi Pengguna -->
        <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-left">
          <h2 class="text-2xl font-bold text-green-600"><?= htmlspecialchars($row["username"]) ?></h2>
          <p class="text-sm text-gray-500"><?= htmlspecialchars($row["email"]) ?></p>
          <p class="mt-2 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm inline-block">
            🌟 Poin: <?= htmlspecialchars($row["points"]) ?>
          </p>
        </div>
      </div>

      <!-- Bio -->
      <div class="mt-6 flex-auto">
        <h3 class="text-lg font-semibold text-gray-700">Bio</h3>
        <p class="mt-2 text-gray-600 italic">
          <?= htmlspecialchars($row["bio"]) ? htmlspecialchars($row["bio"]) : "Kosong" ?>
        </p>
      </div>

      <!-- Tombol Aksi -->
      <div class="mt-6 text-center flex flex-col gap-4 w-full justify-center items-end">
        <a href="/dauraksi/utils/edit-profile.php"
          class="inline-block transition-all duration-300 ease-in-out btn  btn-warning hover:scale-110 hover:warning hover:bg-green-600 text-white font-medium px-6 py-2 rounded-full shadow-md border-2 border-green-200 hover:border-warning">
          Edit Profil
        </a>
        <a href="/dauraksi/"
          class="inline-block transition-all duration-300 ease-in-out btn  btn-warning hover:scale-110 hover:warning hover:bg-green-600 text-white font-medium px-6 py-2 rounded-full shadow-md border-2 border-green-200 hover:border-warning">
          Kembali
        </a>
      </div>
    </div>
  </div>

</body>

</html>