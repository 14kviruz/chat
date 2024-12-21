<?php
session_start();
include_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_id = $_POST['user_id'];

  // Actualizar columna emoji_access para otorgar acceso
  $query = "UPDATE users SET emoji_access = 1 WHERE unique_id = '$user_id'";
  if (mysqli_query($conn, $query)) {
    echo "<script>
            alert('Pago exitoso. ¡Ahora puedes usar los emojis!');
            window.location.href = '../index.php';
          </script>";
  } else {
    echo "<script>
            alert('Error procesando el pago. Inténtalo de nuevo.');
            window.location.href = '../index.php';
          </script>";
  }
} else {
  header("Location: ../index.php");
}
?>
