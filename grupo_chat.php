<?php
include('config.php');

$groupId = $_GET['group_id'];

// Obtener usuarios del grupo
$query = "SELECT u.* FROM users u
          JOIN group_users gu ON u.unique_id = gu.user_id
          WHERE gu.group_id = '$groupId'";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
  echo "<div class='user-chat'>
          <img src='php/images/{$row['img']}' alt=''>
          <span>{$row['username']}</span>
        </div>";
}
?>


<h1>Grupo de Chat: <?php echo $group['group_name']; ?></h1>
<h3>Miembros: <?php echo implode(", ", $memberNames); ?></h3>

<!-- Chat messages -->
<div id="chatMessages"></div>

<!-- Formulario para enviar mensaje -->
<form id="sendMessageForm">
    <input type="text" id="message" placeholder="Escribe un mensaje" required>
    <button type="submit">Enviar</button>
</form>

<script src="javascript/chat.js"></script>
<script>
  function redirectToEmojiPurchase() {
    // Redirige al archivo donde se gestiona la compra de emojis
    window.location.href = "paypal-logo.php";
  }
</script>

<style>
/* Botón de tres rayas */
.hamburger-icon {
    background: none;
    border: none;
    color: #333;
    font-size: 24px;
    cursor: pointer;
    margin-left: auto;
    display: flex;
    align-items: center;
}

.hamburger-icon i {
    font-size: 1.5rem;
}

.hamburger-icon:hover {
    color: #0070ba;
}
</style>
