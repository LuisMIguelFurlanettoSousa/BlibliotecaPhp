<?php
session_start();
include '../includes/validacoes.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include '../includes/database.php';

    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    // Consulta no banco de dados usando prepared statement
    $sql = "SELECT * FROM usuario WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifica a senha - suporta hash E texto plano (para migração)
        $senha_valida = false;

        // Primeiro tenta verificar como hash
        if (verificar_senha($pass, $row['senha'])) {
            $senha_valida = true;
        }
        // Se não for hash, verifica como texto plano (senhas antigas)
        elseif ($pass === $row['senha']) {
            $senha_valida = true;
            // Atualiza para hash automaticamente (migração)
            $nova_senha_hash = hash_senha($pass);
            $update_sql = "UPDATE usuario SET senha = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $nova_senha_hash, $row['id']);
            $update_stmt->execute();
            $update_stmt->close();
        }

        if ($senha_valida) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nome'] = $row['nome'];

            header("Location: /index.php");
            exit;
        } else {
            header("Location: /login/login.php?status=1");
            exit;
        }
    } else {
        header("Location: /login/login.php?status=1");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
