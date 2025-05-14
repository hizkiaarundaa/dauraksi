<?php
session_start();
session_unset();
session_destroy();
?>
<script>
  alert("Anda telah berhasil keluar");
  // Redirect to the homepage or login page
  window.location.href = "/dauraksi/";
</script>