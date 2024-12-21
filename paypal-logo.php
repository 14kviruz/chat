<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago PayPal</title>
    <style>
        /* Estilo general */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #0070ba;
            margin-bottom: 20px;
        }

        /* Estilo de los botones de PayPal */
        .paypal-btn {
            width: 100%;
            padding: 15px;
            background-color: #0070ba;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .paypal-btn:hover {
            background-color: #005fa3;
        }

        .paypal-btn:active {
            background-color: #003b7d;
        }

        /* Modal de pago */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button {
            background-color: #0070ba;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .modal-content button:hover {
            background-color: #005fa3;
        }

        .modal-content .close {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-top: 10px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        /* Mensaje de éxito */
        .success-message {
            display: none;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin-top: 10px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Contenedor de botones PayPal -->
    <div class="container">
        <h1>Pagar con PayPal</h1>
        <button class="paypal-btn" id="payButton">Pagar con PayPal</button>
    </div>

    <!-- Modal de pago -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <h2>Pago con Tarjeta</h2>
            <input type="text" placeholder="Número de tarjeta" id="cardNumber" required>
            <input type="text" placeholder="Nombre en la tarjeta" id="cardName" required>
            <input type="text" placeholder="Fecha de vencimiento (MM/AA)" id="expiryDate" required>
            <input type="text" placeholder="CVV" id="cvv" required>
            <button id="confirmPayment">Confirmar Pago</button>
            <button class="close" id="closeModal">Cancelar</button>
        </div>
    </div>

    <!-- Mensaje de éxito -->
    <div class="success-message" id="successMessage">
        ¡Pago Exitoso! Ahora eres miembro Premium.
    </div>

    <script>
        // Mostrar el modal de pago
        document.getElementById('payButton').addEventListener('click', function() {
            document.getElementById('paymentModal').style.display = 'flex';
        });

        // Cerrar el modal de pago
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('paymentModal').style.display = 'none';
        });

        // Confirmar el pago
        document.getElementById('confirmPayment').addEventListener('click', function() {
            // Aquí simulas el procesamiento del pago
            var cardNumber = document.getElementById('cardNumber').value;
            var cardName = document.getElementById('cardName').value;
            var expiryDate = document.getElementById('expiryDate').value;
            var cvv = document.getElementById('cvv').value;

            if (cardNumber && cardName && expiryDate && cvv) {
                // Simulamos que el pago fue exitoso
                setTimeout(function() {
                    document.getElementById('paymentModal').style.display = 'none';
                    document.getElementById('successMessage').style.display = 'block';

                    // Ocultar el mensaje de éxito después de unos segundos
                    setTimeout(function() {
                        document.getElementById('successMessage').style.display = 'none';
                    }, 3000);
                }, 1000); // Simula un tiempo de procesamiento
            } else {
                alert('Por favor, complete todos los campos.');
            }
        });
    </script>

</body>
</html>
