<?php
if (isset($_POST['createGroup'])) {
  include_once('config.php');

// Procesar los usuarios seleccionados
if (isset($_POST['createGroup'])) {
  $groupName = $_POST['groupName'];
  $selectedUsers = explode(',', $_POST['selectedUsers']); // Convertir los IDs a un array

  // Insertar el grupo en la base de datos
  $query = "INSERT INTO groups (name) VALUES ('$groupName')";
  mysqli_query($conn, $query);
  $groupId = mysqli_insert_id($conn);

  // Relacionar los usuarios con el grupo
  foreach ($selectedUsers as $userId) {
    $query = "INSERT INTO group_users (group_id, user_id) VALUES ('$groupId', '$userId')";
    mysqli_query($conn, $query);
  }

  // Redirigir al chat del grupo
  header("Location: chat_grupal.php?group_id=$groupId");
  exit();


}}
?>
