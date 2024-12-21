<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']); // ID del destinatario

    $output = "";

    // Consulta los mensajes entre los dos usuarios usando los IDs
    $sql = "SELECT * FROM messages 
            LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
            OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) 
            ORDER BY msg_id";  // Ordenamos los mensajes por msg_id (por orden de envío)
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            // Si el mensaje fue enviado por el usuario actual (outgoing)
            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' . $row['msg'] . '</p>
                                </div>
                            </div>';
            } else {
                // Si el mensaje fue enviado por el destinatario (incoming)
                $output .= '<div class="chat incoming">
                                <img src="php/images/' . $row['img'] . '" alt="">
                                <div class="details">
                                    <p>' . $row['msg'] . '</p>
                                </div>
                            </div>';
            }
        }
    } else {
        // Si no hay mensajes aún entre los dos usuarios
        $output .= '<div class="text">No hay mensajes para mostrar.</div>';
    }

    // Mostrar el resultado
    echo $output;
} else {
    header("location: ../login.php"); // Si no hay sesión activa, redirigir al login
}
?>
