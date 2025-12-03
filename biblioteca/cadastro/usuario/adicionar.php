<?php
include '../../includes/validar_sessao.php';
include '../../includes/validacoes.php';

// Operacao de insert - ANTES de qualquer output HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../../includes/database.php';

    $nome = trim($_POST["nome"]);
    $usuario = trim($_POST["usuario"]);
    $senha = $_POST["senha"];

    // Validação de senha
    if (!validar_senha($senha)) {
        $_SESSION['mensagem_erro'] = msg_erro('senha');
        header("location: /cadastro/usuario/adicionar.php");
        exit;
    }

    // Verificar se usuário já existe
    $check_sql = "SELECT id FROM usuario WHERE usuario = ?";
    $check = $conn->prepare($check_sql);
    $check->bind_param("s", $usuario);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['mensagem_erro'] = "Este nome de usuário já está em uso!";
        $check->close();
        $conn->close();
        header("location: /cadastro/usuario/adicionar.php");
        exit;
    }
    $check->close();

    // Hash da senha para armazenamento seguro
    $senha_hash = hash_senha($senha);

    // Preparar e executar a inserção do novo registro
    $sql = "INSERT INTO usuario (nome, usuario, senha) VALUES (?, ?, ?)";
    $insert = $conn->prepare($sql);
    $insert->bind_param("sss", $nome, $usuario, $senha_hash);

    if ($insert->execute()) {
        $_SESSION['mensagem_sucesso'] = "Usuário cadastrado com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao cadastrar usuário: " . $conn->error;
    }

    $insert->close();
    $conn->close();

    header("location: /cadastro/usuario/listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Cadastrar Usuário</title>
</head>
<body>

<?php include '../../componentes/menu.php'; ?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Cadastrar Novo Usuário</h2>
    <form action='adicionar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="nome">Nome Completo</label>
                <input class="w3-input w3-border" type="text" id="nome" name="nome" required>
            </div>
            <div class="w3-col s4">
                <label for="usuario">Nome de Usuário</label>
                <input class="w3-input w3-border" type="text" id="usuario" name="usuario" required>
            </div>
            <div class="w3-col s4">
                <label for="senha">Senha (mínimo 6 caracteres)</label>
                <input class="w3-input w3-border" type="password" id="senha" name="senha" minlength="6" required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/cadastro/usuario/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
