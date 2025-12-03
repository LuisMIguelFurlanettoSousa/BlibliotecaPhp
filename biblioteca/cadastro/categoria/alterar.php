<?php
include '../../includes/validar_sessao.php';
include '../../includes/validacoes.php';

// Operação de update - ANTES de qualquer output HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id = $_POST["id"];
    $cat = trim($_POST["categoria"]);
    $descricao = trim($_POST["descricao"]);

    $sql = "UPDATE categoria set categoria = ?, descricao = ? WHERE id = ?";
    $update = $conn->prepare($sql);
    $update->bind_param("ssi", $cat, $descricao, $id);

    if ($update->execute()) {
        $_SESSION['mensagem_sucesso'] = "Categoria atualizada com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();
    $conn->close();

    header("location: /cadastro/categoria/listar.php");
    exit;
}

// Operação de consulta (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];

        include '../../includes/database.php';

        $sql = "SELECT * FROM categoria WHERE id = ?";
        $consulta = $conn->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $categoria = $resultado->fetch_assoc();
        } else {
            $_SESSION['mensagem_erro'] = "Categoria não encontrada.";
            $consulta->close();
            $conn->close();
            header("location: /cadastro/categoria/listar.php");
            exit;
        }
    } else {
        $_SESSION['mensagem_erro'] = "Categoria não encontrada.";
        header("location: /cadastro/categoria/listar.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alterar Categoria</title>
</head>
<body>

<?php include '../../componentes/menu.php'; ?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Alterar Categoria</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <input type="hidden" name="id" value="<?php echo escape($categoria['id']); ?>">

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="categoria">Categoria</label>
                <input class="w3-input w3-border" type="text" id="categoria" name="categoria" value="<?php echo escape($categoria['categoria']); ?>" required>
            </div>
            <div class="w3-col s8">
                <label for="descricao">Descrição</label>
                <input class="w3-input w3-border" type="text" id="descricao" name="descricao" value="<?php echo escape($categoria['descricao']); ?>">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/cadastro/categoria/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
