<?php
include('config.php');

$data = json_decode(file_get_contents('php://input'), true);
$groupId = $data['groupId'];
$message = $data['message'];
$outgoingMsgId = $_SESSION['user_id'];  // ID del usuario que envía el mensaje

// Obtener el primer miembro del grupo como destinatario
$query = "SELECT * FROM group_chat WHERE id = '$groupId'";
$result = mysqli_query($conn, $query);
$group = mysqli_fetch_assoc($result);
$members = explode(",", $group['members']);
$incomingMsgId = $members[0];  // Suponemos que el primer miembro es el destinatario para los mensajes de prueba

// Insertar mensaje en la tabla `messages`
$query = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES ('$incomingMsgId', '$outgoingMsgId', '$message')";
echo json_encode(["message" => "Mensaje recibido", "text" => $message]);

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
