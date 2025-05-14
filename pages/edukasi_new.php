<?php
session_start();
include '../auth/db.php';

if (!isset($_SESSION['username'])) {
  header("Location: /dauraksi/auth/login.php");
  exit;
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buat Postingan Edukasi</title>
  <link rel="stylesheet" href="/dauraksi/css/style.css" />
  <link rel="stylesheet" href="/dauraksi/css/edukasi.css">
</head>

<body class="bg-green-50 min-h-screen flex flex-col">

  <!-- Header -->
  <header class="bg-green-700 text-white h-16 flex items-center px-6">
    <h1 class="text-xl font-bold">Buat Postingan Edukasi</h1>
  </header>

  <!-- Form -->
  <main class="flex-grow flex justify-center items-center p-4">
    <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-md">

      <form method="POST">
        <?php
        $errors = [];
        $title = '';
        $description = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $title = trim($_POST['title'] ?? '');
          $description = trim($_POST['description'] ?? '');
          $username = $_SESSION['username'];

          if (empty($title)) {
            $errors[] = "Judul tidak boleh kosong.";
          }

          if (empty($description)) {
            $errors[] = "Deskripsi tidak boleh kosong.";
          }

          if (empty($errors)) {
            $title = mysqli_real_escape_string($con, $title);
            $description = mysqli_real_escape_string($con, $description);
            $username = mysqli_real_escape_string($con, $username);

            $query = "INSERT INTO edukasi (title, description, username) VALUES ('$title', '$description', '$username')";
            if (mysqli_query($con, $query)) {
              $addPoints = "UPDATE USERS SET points = points + 15 WHERE username = '$username'";
              mysqli_query($con, $addPoints);
              echo "<div id='alertBox' class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-green-600 text-white p-4 rounded-lg shadow-lg transition-opacity duration-300'>Postingan Berhasil dibuat.<br> Anda mendapatkan 15 point 🎉!</div>";
              echo "<script>
              setTimeout(() => {
              window.location.href = '/dauraksi/pages/edukasi.php';
              }, 2500);
            </script>";
              exit();
            } else {
              $errors[] = "Gagal menyimpan data: " . mysqli_error($con);
            }
          } else {
            foreach ($errors as $error) {
              echo "<div id='alertBox' class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-red-600 text-white p-4 rounded-lg shadow-lg transition-opacity duration-300'>$error</div>";
            }
          }
        }
        ?>
        <div class="mb-4">
          <label for="title" class="block font-semibold mb-1">Judul</label>
          <input type="text" name="title" id="title" value="<?= htmlspecialchars($title) ?>"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>
        <div class="mb-6">
          <label for="description" class="block font-semibold mb-1">Deskripsi</label>
          <textarea name="description" id="description" rows="6"
            class="w-full border border-gray-300 rounded px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-green-500"><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="flex justify-end gap-2">
          <a href="/dauraksi/pages/edukasi.php"
            class="btn bg-gray-300 text-gray-800 hover:bg-gray-400 px-4 py-2 rounded">Batal</a>
          <button type="submit"
            class="btn bg-green-700 text-white hover:bg-green-800 px-4 py-2 rounded">Posting</button>
        </div>
      </form>
    </div>
  </main>
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