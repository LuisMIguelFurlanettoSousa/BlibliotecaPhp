<?php
    //comando responsavel por criar o conceito de sessao
    session_start();
    
    // Usuário e senha fictícios (normalmente estariam no banco de dados)
    $usuario_correto = "admin";
    $senha_correta = "123456";
    // Subsituir o codigo acima, por um código que conecte no banco de dados 
    // e faça a validação do usuário e senha com base nos dados do banco
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // usuario que veio da tela de login
        $user = $_POST['username'];
        // senha que veio da tela de login
        $pass = $_POST['password'];

        // comparar usuario e senha da tela de login com o que veio do banco de dados
        if ($user === $usuario_correto && $pass === $senha_correta) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user;
            
            header("Location: /loginapp/index.php");
            exit;
        } else {
            header("Location: /loginapp/login.php?status=1");
        }
    }
?>