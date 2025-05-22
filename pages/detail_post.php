<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
include '../auth/db.php';

if (!isset($_GET['id'])) {
  // Redirect jika tidak ada ID
  header("Location: edukasi.php");
  exit();
}

$id = intval($_GET['id']);
$stmt = $con->prepare("SELECT * FROM edukasi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Postingan tidak ditemukan.";
  exit();
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Postingan - <?= htmlspecialchars($post['title']) ?></title>
  <link rel="shortcut icon" href="/dauraksi/public/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="/dauraksi/css/style.css" />
  <link rel="stylesheet" href="/dauraksi/css/edukasi.css">
  <script src="https://kit.fontawesome.com/c5596080ef.js" crossorigin="anonymous"></script>
</head>

<body
  class="flex flex-col min-h-dvh bg-green-50 p-4 bg-[url('/dauraksi/public/edukasi.jpg')] bg-center bg-cover bg-no-repeat">
  <!-- overlay disini -->
  <div class="absolute inset-0 bg-black/60 z-0"></div>
  <!-- Main Detail Content -->
  <main
    class="relative flex-grow p-4 w-2xl max-w-4xl mx-auto shadow-xl mt-6 rounded-lg border-4 border-green-300 flex flex-col z-50 ">
    <h1 class="text-3xl font-bold text-green-500 mb-4"><?= htmlspecialchars($post['title']) ?></h1>
    <div class="mb-2 text-sm text-white">
      Diposting oleh <span class="font-semibold text-green-600"><?= htmlspecialchars($post['username']) ?></span>
    </div>
    <p class="text-justify leading-relaxed text-white whitespace-pre-line flex-grow">
      <?= nl2br(htmlspecialchars($post['description'])) ?>
    </p>
    <div class="flex justify-end items-center">
      <a href="/dauraksi/pages/edukasi.php"
        class="inline-block transition-all duration-300 ease-in-out btn  btn-warning hover:scale-110 hover:warning hover:bg-green-600 text-white font-medium px-6 py-2 rounded-full shadow-md">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Edukasi
      </a>
    </div>
  </main>

</body>

</html>