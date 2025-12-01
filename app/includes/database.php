<?php
$servidor = "localhost";  // Endereço do servidor
$usuario = "root"; // Nome de usuário do banco de dados
$senha = "12345678";     // Senha do banco de dados
$banco = "biblioteca"; // Nome do banco de dados

$message="";

// Conectar ao banco de dados
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>