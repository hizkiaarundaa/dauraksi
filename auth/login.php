<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/dauraksi/css/style.css">
  <link rel="shortcut icon" href="../public/logo.png" type="image/x-icon">
  <title>Login ke daurAksi</title>
</head>

<body>
  <div class="flex flex-col items-center justify-center h-screen">
    <div class="w-full max-w-md bg-gray-500 rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold text-gray-200 mb-4">Login</h2>

      <?php
      include("db.php");


      if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
        $username = mysqli_real_escape_string($con, $_POST["username"]);
        $password = $_POST["password"];

        $query = "SELECT * FROM USERS WHERE username='$username' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
          $user = mysqli_fetch_assoc($result);
          if (password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["username"] = $user["username"];
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["welcome"] = $user["username"];
            echo "<script>
                    window.opener.location.reload();
                    window.close();
                  </script>";
            exit();
          } else {
            echo "<div class='text-white bg-red-600 p-4 rounded mb-4'>Password salah.</div>";
          }
        } else {
          echo "<div class='text-white bg-red-600 p-4 rounded mb-4'>Username tidak ditemukan.</div>";
        }
      }
      ?>
      <form class="flex flex-col" action="login.php" method="post">
        <input placeholder="Username" type="text" name="username" required autocomplete="off"
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4">
        <input placeholder="Password" name="password" type="password" required autocomplete="off"
          class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4">
        <div class="flex items-center justify-between">
          <a href="/dauraksi/auth/forget.password.php" class="text-sm text-green-300 hover:underline">Lupa Password?</a>
          <a href="/dauraksi/auth/register.php" class="text-sm text-green-300 hover:underline">Belum punya akun?</a>
        </div>
        <button type="submit" name="submit"
          class="bg-gradient-to-r from-green-500 to-lime-500 text-white font-bold py-2 px-4 rounded-md mt-4 hover:bg-green-600 hover:to-lime-600">
          Login
        </button>
      </form>
    </div>
  </div>
</body>

</html>