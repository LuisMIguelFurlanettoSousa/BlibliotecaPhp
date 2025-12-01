<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include '../includes/database.php';

    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Consulta no banco de dados usando prepared statement
    $sql = "SELECT * FROM usuario WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifica a senha (assumindo que está armazenada em texto plano - melhor usar password_hash/password_verify)
        if ($pass === $row['senha']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nome'] = $row['nome'];

            header("Location: /biblioteca/index.php");
            exit;
        } else {
            header("Location: /biblioteca/login/login.php?status=1");
        }
    } else {
        header("Location: /biblioteca/login/login.php?status=1");
    }

    $stmt->close();
    $conn->close();
}
?>
