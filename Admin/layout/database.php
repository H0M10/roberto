<?php
            $servername = "localhost";
            $username = "root";
            $password = "hanniel";
            $dbname = "roberto";
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }
            ?>