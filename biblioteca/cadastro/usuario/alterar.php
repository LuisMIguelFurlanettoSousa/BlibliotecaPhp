<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alterar Usuário</title>
</head>
<body>

<?php
session_start();
include '../../includes/validar_sessao.php';
include '../../componentes/menu.php';

// Operação de consulta
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];

        include '../../includes/database.php';

        // Preparar e executar a consulta para buscar o usuário
        $sql = "SELECT * FROM usuario WHERE id = ?";
        $consulta = $conn->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        // Verifica se o usuário foi encontrado
        if ($resultado->num_rows > 0) {
            $usuario_data = $resultado->fetch_assoc();
        } else {
            $_SESSION['mensagem_erro'] = "Usuário não encontrado.";
            $consulta->close();
            $conn->close();
            header("location: /biblioteca/cadastro/usuario/listar.php");
            exit;
        }
    } else {
        $_SESSION['mensagem_erro'] = "ID do usuário não informado.";
        header("location: /biblioteca/cadastro/usuario/listar.php");
        exit;
    }
// Operação de update
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    // Verificar se usuário já existe (excluindo o atual)
    $check_sql = "SELECT id FROM usuario WHERE usuario = ? AND id != ?";
    $check = $conn->prepare($check_sql);
    $check->bind_param("si", $usuario, $id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['mensagem_erro'] = "Este nome de usuário já está em uso!";
        $check->close();
        $conn->close();
        header("location: /biblioteca/cadastro/usuario/alterar.php?id=" . $id);
        exit;
    }
    $check->close();

    // Se senha foi preenchida, atualiza com nova senha
    if (!empty($senha)) {
        $sql = "UPDATE usuario SET nome = ?, usuario = ?, senha = ? WHERE id = ?";
        $update = $conn->prepare($sql);
        $update->bind_param("sssi", $nome, $usuario, $senha, $id);
    } else {
        // Se senha vazia, mantém a senha atual
        $sql = "UPDATE usuario SET nome = ?, usuario = ? WHERE id = ?";
        $update = $conn->prepare($sql);
        $update->bind_param("ssi", $nome, $usuario, $id);
    }

    if ($update->execute()) {
        $_SESSION['mensagem_sucesso'] = "Usuário atualizado com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();
    $conn->close();

    header("location: /biblioteca/cadastro/usuario/listar.php");
    exit;
}
?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Alterar Usuário</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <input type="hidden" name="id" value="<?php echo $usuario_data['id']; ?>">

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="nome">Nome Completo</label>
                <input class="w3-input w3-border" type="text" id="nome" name="nome" value="<?php echo $usuario_data['nome']; ?>" required>
            </div>
            <div class="w3-col s4">
                <label for="usuario">Nome de Usuário</label>
                <input class="w3-input w3-border" type="text" id="usuario" name="usuario" value="<?php echo $usuario_data['usuario']; ?>" required>
            </div>
            <div class="w3-col s4">
                <label for="senha">Nova Senha (deixe em branco para manter)</label>
                <input class="w3-input w3-border" type="password" id="senha" name="senha">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/biblioteca/cadastro/usuario/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
