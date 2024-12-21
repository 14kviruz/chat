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
    <section class="users">
      <header>
        <div class="content">
          <?php
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
          if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
          }
          ?>
          <img src="php/images/<?php echo $row['img']; ?>" alt="">
          <div class="details">
            <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>

        <!-- Contenedor de los botones -->
        <div id="buttonContainer" style="display: flex; align-items: center; justify-content: flex-end;">
          <!-- Botón Crear Grupo -->
          <button id="createGroupBtn">Crear Grupo</button>
          <!-- Botón Cerrar Sesión -->
          <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Cerrar Sesión</a>
        </div>

      </header>

      <div class="search">
        <span class="text">Con quien quieres hablar</span>
        <input type="text" placeholder="Introduzca el nombre para buscar...">
        <button><i class="fas fa-search"></i></button>
      </div>

      <div class="users-list">

       <!-- Modal de crear grupo -->
<div id="createGroupModal" style="display: none;">
  <div class="modal-content">
    <h2>Crear Grupo</h2>
    <form id="createGroupForm" method="POST" action="grupo_chat.php">
      <input type="text" id="groupName" name="groupName" placeholder="Nombre del Grupo" required>

      <label for="users">Seleccionar Miembros</label>
      <div id="selectedUsers"></div> <!-- Aquí se mostrarán los usuarios seleccionados -->

      <button type="submit" name="createGroup">Crear Grupo</button>
    </form>
    <button onclick="closeModal()">Cerrar</button>
  </div>
</div>


      </div>
    </section>
  </div>

  <style>
    /* Estilo para los botones */
    #createGroupBtn {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
    }

    #createGroupBtn:hover {
      background-color: #45a049;
    }

    /* Estilo para el contenedor de botones */
    #buttonContainer {
      display: flex;
      justify-content: flex-end;
      gap: 10px; /* Espacio entre los botones */
      align-items: center;
    }

    /* Estilo del modal */
    #createGroupModal {
  display: none; /* Inicialmente oculto */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Asegurarse de que esté por encima de otros elementos */
}


    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h2 {
      font-size: 24px;
      margin-bottom: 15px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input[type="text"], select {
      padding: 10px;
      margin-bottom: 1px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #45a049;
    }

    button:last-child {
      background-color: #f44336;
    }

    button:last-child:hover {
      background-color: #e53935;
    }

  </style>

  <script>
    // Función para abrir el modal
    document.getElementById('createGroupBtn').onclick = function () {
      document.getElementById('createGroupModal').style.display = 'flex';
    };

    // Función para cerrar el modal
    function closeModal() {
      document.getElementById('createGroupModal').style.display = 'none';
    }
  </script>

  <script src="javascript/users.js"></script>
</body>
</html>
