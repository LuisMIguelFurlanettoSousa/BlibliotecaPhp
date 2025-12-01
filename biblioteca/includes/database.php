<?php
$servidor = "localhost";
$usuario = "root";
$senha = "12345678";
$banco = "biblioteca";

$message="";

// Conectar ao banco de dados
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
