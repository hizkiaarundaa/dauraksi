<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/dauraksi/css/style.css">
  <link rel="shortcut icon" href="../public/logo.png" type="image/x-icon">
  <title>Lupa Password</title>
</head>

<body>
<div class="flex flex-col items-center justify-center h-screen dark">
  <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-200 mb-4">Lupa Password</h2>
    <form class="flex flex-col">
    <input placeholder="Username" type="text" name="username" required autocomplete="off"
    class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150">
    <input placeholder="Masukkan password baru" type="password" name="password" required autocomplete="off"
    class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mb-4 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-green-700 transition ease-in-out duration-150">
      <button class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white font-bold py-2 px-4 rounded-md mt-4 hover:bg-indigo-600 hover:to-blue-600 transition ease-in-out duration-150" type="submit">Ganti Password</button>
    </form>
  </div>
</div>
</body>

</html>