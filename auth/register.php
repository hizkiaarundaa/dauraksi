<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/dauraksi/css/style.css" />
  <link rel="shortcut icon" href="../public/logo.png" type="image/x-icon" />
  <title>Register ke daurAksi</title>
</head>

<body>
  <div class="flex flex-col items-center justify-center h-screen relative">
    <div class="w-full max-w-md bg-gray-500 rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold text-gray-200 mb-4">Register</h2>

      <?php
      include("db.php");

      if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
        // Escape & sanitize inputs
        $username = mysqli_real_escape_string($con, $_POST["username"]);
        $email = mysqli_real_escape_string($con, $_POST["email"]);
        $password = $_POST["password"];
        $repeat_password = $_POST["repeat_password"];
        $hash_password = mysqli_real_escape_string($con, password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]));

        $errors = [];

        // Validasi
        if (empty($username) || empty($email) || empty($password) || empty($repeat_password))
          $errors[] = "Format semua bidang wajib diisi";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
          $errors[] = "Email tidak valid";
        if (strlen($password) < 8)
          $errors[] = "Password harus lebih dari 8 karakter";
        if ($password !== $repeat_password)
          $errors[] = "Password tidak sama";

        // Cek duplikat
        $check_query = "SELECT * FROM USERS WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($con, $check_query);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
          if ($user["username"] === $username)
            $errors[] = "Username sudah terdaftar";
          if ($user["email"] === $email)
            $errors[] = "Email sudah terdaftar";
        }

        if (count($errors) > 0) {
          echo "<div id='alertBox' class='absolute top-20 left-1/2 transform -translate-x-1/2 z-50 bg-red-600 text-white p-4 rounded-lg shadow-lg transition-opacity duration-300'>";
          foreach ($errors as $error) {
            echo "<p>$error</p>";
          }
          echo "</div>";
        } else {
          $insert_query = "INSERT INTO USERS(username,email,password) VALUES('$username','$email','$hash_password')";
          if (mysqli_query($con, $insert_query)) {
            echo "<div id='alertBox' class='absolute top-20 left-1/2 transform -translate-x-1/2 z-50 alert alert-success text-white p-4 rounded-lg shadow-lg transition-opacity duration-300'>";
            echo "p";
            echo "</div>";
            header("Location: /dauraksi/auth/login.php");
            exit();
          } else {
            echo "<div class='text-white bg-red-600 p-4 rounded'>Terjadi kesalahan saat menyimpan data.</div>";
          }
        }
      }
      ?>

      <form class="flex flex-col" action="register.php" method="post">
        <input placeholder="Username" type="text" name="username" autocomplete="off"
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150" />
        <input placeholder="Email" type="email" name="email" autocomplete="off"
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150" />
        <input placeholder="Password" name="password" type="password" autocomplete="off"
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150" />
        <input placeholder="Konfirmasi Password" name="repeat_password" type="password" autocomplete="off"
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150" />
        <div class="flex items-center justify-between flex-wrap">
          <p class="text-white mt-4">
            Sudah punya akun?
            <a class="text-sm text-green-500 hover:underline mt-4" href="/dauraksi/auth/login.php">Login</a>
          </p>
        </div>
        <button type="submit" name="submit"
          class="bg-gradient-to-r from-green-500 to-lime-500 text-white font-bold py-2 px-4 rounded-md mt-4 hover:bg-green-600 hover:to-lime-600 transition ease-in-out duration-150">
          Daftar
        </button>
      </form>
    </div>

    <script>
      const alertBox = document.getElementById('alertBox');
      if (alertBox) {
        setTimeout(() => {
          alertBox.classList.add('opacity-0');
          setTimeout(() => alertBox.remove(), 300);
        }, 1000);
      }
    </script>
  </div>
</body>

</html>