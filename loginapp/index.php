<?php
    include("./validar_sessao.php");
?>

<!DOCTYPE html>
<html>
<head><title>Painel</title></head>
<body>
    <h1>Bem-vindo, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Você está logado.</p>
    <p><a href="pagina_protegida.php">Outra página protegida</a></p>
    <a href="logout.php">Sair</a>
</body>
</html>