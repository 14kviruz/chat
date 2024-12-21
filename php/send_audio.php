<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['audio'])) {
        $audio = $_FILES['audio'];
        $uploadDirectory = 'uploads/';
        $uploadFile = $uploadDirectory . basename($audio['name']);

        if (move_uploaded_file($audio['tmp_name'], $uploadFile)) {
            echo json_encode(["message" => "Audio recibido y guardado.", "file" => $uploadFile]);
        } else {
            echo json_encode(["message" => "Error al guardar el archivo."]);
        }
    }
}
?>
