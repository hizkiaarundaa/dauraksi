<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/dauraksi/css/style.css" />
  <link rel="shortcut icon" href="../public/logo.png" type="image/x-icon" />
  <title>Lupa Password - DaurAksi</title>
</head>

<body>
  <div class="flex flex-col items-center justify-center h-screen relative">
    <div class="w-full max-w-md bg-gray-500 rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold text-gray-200 mb-4">Lupa Password</h2>
      <?php
      session_start();
      include("db.php");

      $errors = [];

      if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
        $email = mysqli_real_escape_string($con, $_POST["email"]);
        $newPassword = $_POST["password"];
        $repeatPassword = $_POST["repeat_password"];

        // Validasi
        if (empty($email) || empty($newPassword) || empty($repeatPassword))
          $errors[] = "Semua bidang wajib diisi";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
          $errors[] = "Email tidak valid";

        if (strlen($newPassword) < 8)
          $errors[] = "Password minimal 8 karakter";

        if ($newPassword !== $repeatPassword)
          $errors[] = "Konfirmasi password tidak cocok";

        if (empty($errors)) {
          // Cek apakah email terdaftar
          $checkQuery = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
          $result = mysqli_query($con, $checkQuery);
          $user = mysqli_fetch_assoc($result);

          if ($user) {
            // Update password
            $hashedPassword = mysqli_real_escape_string($con, password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]));
            $updateQuery = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";

            if (mysqli_query($con, $updateQuery)) {
              header("Location: login.php?reset=success");
              exit();
            } else {
              $errors[] = "Gagal mengubah password. Silakan coba lagi.";
            }
          } else {
            $errors[] = "Email tidak ditemukan.";
          }
        }
      }
      ?>

      <?php if (!empty($errors)): ?>
        <div id="alertBox"
          class="absolute top-20 left-1/2 transform -translate-x-1/2 z-50 bg-red-600 text-white p-4 rounded-lg shadow-lg transition-opacity duration-300">
          <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form class="flex flex-col" method="POST" action="forget.password.php">
        <input placeholder="Email" type="email" name="email" autocomplete="off" required
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150" />
        <input placeholder="Password Baru" type="password" name="password" autocomplete="off" required
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150" />
        <input placeholder="Ulangi Password Baru" type="password" name="repeat_password" autocomplete="off" required
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150" />

        <div class="flex items-center justify-between flex-wrap">
          <p class="text-white mt-4">
            Sudah ingat password?
            <a class="text-sm text-green-500 hover:underline mt-4" href="/dauraksi/auth/login.php">Login</a>
          </p>
        </div>

        <button type="submit" name="submit" onsubmit=""
          class="bg-gradient-to-r from-green-500 to-lime-500 text-white font-bold py-2 px-4 rounded-md mt-4 hover:bg-green-600 hover:to-lime-600 transition ease-in-out duration-150">
          Reset Password
        </button>
      </form>
    </div>

    <script>
      const alertBox = document.getElementById('alertBox');
      if (alertBox) {
        setTimeout(() => {
          alertBox.classList.add('opacity-0');
          setTimeout(() => alertBox.remove(), 300);
        }, 1500);
      }
    </script>
  </div>
</body>

</html>