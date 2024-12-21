<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php
        $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
        if (mysqli_num_rows($sql) > 0) {
          $row = mysqli_fetch_assoc($sql);
        } else {
          header("location: users.php");
        }
        ?>
         <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
    <img src="php/images/<?php echo $row['img']; ?>" alt="">
    <div class="details">
        <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
        <p><?php echo $row['status']; ?></p>
    </div>
    <!-- Botón de tres rayas -->
    <button id="hamburger-menu" class="hamburger-icon" onclick="redirectToPaypal()">
    <i class="fas fa-dollar-sign"></i>
    </button>
      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
  <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
  <input type="text" name="message" class="input-field" placeholder="Escribir•••" autocomplete="off">
  <button id="send-button" type="button">
    <i id="icon" class="fas fa-microphone"></i>
  </button>
</form>

    </section>
  </div>
  <style>
/* Estilos para el contenedor del header */
header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Espacia elementos a los lados */
    padding: 10px 15px;
    background-color:rgb(100, 177, 245);
    border-bottom: 1px solid #ddd;
}

.hamburger-icon {
    background-color:rgba(0, 255, 21, 0.33); /* Color dorado para la corona */
    color: #fff; /* Color del ícono */
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: transform 0.3s, background-color 0.3s;
}

.hamburger-icon:hover {
    background-color:rgb(26, 255, 0); /* Color más oscuro al pasar el mouse */
    transform: scale(1.1); /* Aumenta ligeramente el tamaño */
}

.hamburger-icon i {
    font-size: 24px;
}


/* Imagen y detalles */
header img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.details {
    margin-left: 10px;
    flex-grow: 1;
}

.details span {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.details p {
    font-size: 14px;
    color: #666;
    margin: 0;
}







.typing-area {
  display: flex;
  align-items: center;
  padding: 10px;
  border-top: 1px solid #ccc;
}

.input-field {
  flex: 1;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 20px;
  margin-right: 10px;
  outline: none;
  font-size: 16px;
}

#send-button {
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

#send-button:hover {
  background-color: #0056b3;
}

#send-button i {
  font-size: 20px;
}

#send-button i.recording {
  color: red;
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
}

</style>

<script>
  function redirectToPaypal() {
    // Redirige al archivo paypal_logo.php
    window.location.href = "paypal-logo.php";
  }
</script>


  <script src="javascript/chat.js"></script>

</body>

</html>