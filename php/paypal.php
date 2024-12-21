<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']));
}

$user_id = $_SESSION['unique_id'];

// Credenciales de PayPal
$clientID = "TU_CLIENT_ID"; // Cambia por tu Client ID
$clientSecret = "TU_SECRET"; // Cambia por tu Secret

// Validar la transacción en PayPal
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['orderID'])) {
    $orderID = $data['orderID'];

    // Obtener token de acceso
    $ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientID:$clientSecret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    if (!isset($result['access_token'])) {
        die(json_encode(['status' => 'error', 'message' => 'No se pudo obtener el token de acceso']));
    }

    $accessToken = $result['access_token'];

    // Validar el estado de la orden
    $ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json",
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $order = json_decode($response, true);

    if ($order['status'] === 'COMPLETED') {
        // Actualizar base de datos
        $stmt = $conn->prepare("UPDATE users SET is_premium = 1 WHERE unique_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Pago realizado con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Transacción no completada']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de orden no proporcionado']);
}
?>



