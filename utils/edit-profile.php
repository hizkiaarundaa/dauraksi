<?php
session_start();
$username = $_SESSION['username'];

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  header("Location: /dauraksi/auth/login.php");
  exit();
}

include("../auth/db.php");

// Ambil data pengguna saat ini
$query = "SELECT * FROM USERS WHERE username='$username'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);

if (!$row) {
  echo "<script>alert('Pengguna tidak ditemukan.');</script>";
  header("Location: /dauraksi/index.php");
  exit();
}

// Proses update profil jika form disubmit
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil data dari form
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $bio = mysqli_real_escape_string($con, $_POST['bio']);
  $currentPassword = $_POST['current_password'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  // Update email dan bio
  $updateQuery = "UPDATE USERS SET email='$email', bio='$bio' WHERE username='$username'";

  // Jika pengguna ingin mengubah password juga
  if (!empty($currentPassword)) {
    // Verifikasi password saat ini
    if (password_verify($currentPassword, $row['password'])) {
      // Periksa apakah password baru diisi dengan benar
      if (!empty($newPassword) && $newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        $updateQuery = "UPDATE USERS SET email='$email', bio='$bio', password='$hashedPassword' WHERE username='$username'";
      } elseif (!empty($newPassword)) {
        $error = "Password baru dan konfirmasi password tidak cocok!";
      }
    } else {
      $error = "Password saat ini tidak benar!";
    }
  }

  // Eksekusi query jika tidak ada error
  if (empty($error)) {
    if (mysqli_query($con, $updateQuery)) {
      $message = "Profil berhasil diperbarui!";
      // Ambil data yang sudah diupdate
      echo "<script>
              setTimeout(() =>{
                window.location.href = '/dauraksi/pages/profile.php'
              })
            </script>";
      $result = mysqli_query($con, $query);
      $row = mysqli_fetch_array($result);
    } else {
      $error = "Gagal memperbarui profil: " . mysqli_error($con);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="/dauraksi/public/logo.png" type="image/x-icon">
  <title>Edit Profil - DaurAksi</title>
  <link rel="stylesheet" href="/dauraksi/css/style.css" />
</head>

<body class="bg-gray-50 text-gray-800 font-sans min-h-dvh w-full">
  <div class="flex justify-center items-center p-6 min-h-dvh">
    <div class="bg-white rounded-3xl shadow-xl p-8 max-w-2xl w-full border border-green-200">
      <h1 class="text-2xl font-bold text-green-600 mb-6 text-center">Edit Profil</h1>

      <?php if (!empty($message)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
          <p><?= $message ?></p>
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
          <p><?= $error ?></p>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <!-- Foto Profil -->
        <div class="mb-6 flex flex-col items-center">
          <img class="w-32 h-32 rounded-full border-4 border-green-600 shadow-md mb-4"
            src="/dauraksi/public/avatar-default.jpeg" alt="Foto Profil" />
          <p class="text-sm text-gray-500">Foto profil tidak dapat diubah saat ini</p>
        </div>

        <!-- Username (tidak dapat diubah) -->
        <div class="mb-6">
          <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
          <input type="text" id="username" value="<?= htmlspecialchars($row['username']) ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <!-- Email -->
        <div class="mb-6">
          <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-300" disabled required />
          <p class="text-sm text-gray-500 mt-1">Email tidak dapat diubah</p>
        </div>

        <!-- Bio -->
        <div class="mb-6">
          <label for="bio" class="block text-gray-700 font-medium mb-2">Bio</label>
          <textarea id="bio" name="bio" rows="4"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"><?= htmlspecialchars($row['bio']) ?></textarea>
        </div>

        <!-- Ganti Password -->
        <div class="mb-6 border-t border-gray-200 pt-6">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Ganti Password (opsional)</h3>

          <div class="mb-4">
            <label for="current_password" class="block text-gray-700 font-medium mb-2">Password Saat Ini</label>
            <input type="password" id="current_password" name="current_password"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
          </div>

          <div class="mb-4">
            <label for="new_password" class="block text-gray-700 font-medium mb-2">Password Baru</label>
            <input type="password" id="new_password" name="new_password"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
          </div>

          <div class="mb-4">
            <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Konfirmasi Password Baru</label>
            <input type="password" id="confirm_password" name="confirm_password"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col md:flex-row gap-4 justify-between">
          <a href="/dauraksi/pages/profile.php"
            class="text-center transition-all duration-300 ease-in-out bg-gray-500 hover:bg-gray-600 text-white font-medium px-6 py-2 rounded-full shadow-md">
            Batal
          </a>
          <button type="submit"
            class="transition-all duration-300 ease-in-out bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded-full shadow-md">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>