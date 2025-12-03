<?php
session_start();

// caso o usuario já esteja logado rediciona para a pagina principal
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: /index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Sistema Biblioteca</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-light-grey">
    <div class="w3-display-middle">
        <div class="w3-card-4 w3-white" style="max-width:400px">
            <div class="w3-container w3-blue">
                <h2>Login - Biblioteca</h2>
            </div>
            <form class="w3-container" method="POST" action="/login/check_login.php">
                <p>
                    <label>Usuário</label>
                    <input class="w3-input" type="text" name="username" required>
                </p>
                <p>
                    <label>Senha</label>
                    <input class="w3-input" type="password" name="password" required>
                </p>
                <p>
                    <button class="w3-button w3-blue w3-block" type="submit">Entrar</button>
                </p>
                <div>
                    <?php
                        if (isset($_GET['status'])){
                            if ($_GET['status'] == 1) {
                                echo "<p style='color:red;'>Usuário ou senha inválidos!</p>";
                            } else if ($_GET['status'] == 2) {
                                echo "<p style='color:green;'>Você foi deslogado com sucesso!</p>";
                            } else if ($_GET['status'] == 3) {
                                echo "<p style='color:red;'>Você deve estar logado para acessar essa página!</p>";
                            }
                        }
                    ?>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
