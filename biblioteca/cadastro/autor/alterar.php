<?php
include '../../includes/validar_sessao.php';
include '../../includes/validacoes.php';

// Operação de update - ANTES de qualquer output HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id = $_POST["id"];
    $aut = trim($_POST["autor"]);
    $pseudonimo = trim($_POST["pseudonimo"]);
    $nacionalidade = trim($_POST["nacionalidade"]);
    $endereco_web = trim($_POST["endereco_web"]);
    $email = trim($_POST["email"]);
    $telefone = apenas_numeros($_POST["telefone"]);

    // Validações
    $erros = [];

    if (!empty($email) && !validar_email($email)) {
        $erros[] = msg_erro('email');
    }

    if (!empty($telefone) && !validar_telefone($telefone)) {
        $erros[] = msg_erro('telefone');
    }

    if (!empty($erros)) {
        $_SESSION['mensagem_erro'] = implode("<br>", $erros);
        $conn->close();
        header("location: /cadastro/autor/alterar.php?id=" . $id);
        exit;
    }

    $sql = "UPDATE autor set autor = ?, pseudonimo = ?, nacionalidade = ?, endereco_web = ?, email = ?, telefone = ? WHERE id = ?";
    $update = $conn->prepare($sql);
    $update->bind_param("ssssssi", $aut, $pseudonimo, $nacionalidade, $endereco_web, $email, $telefone, $id);

    if ($update->execute()) {
        $_SESSION['mensagem_sucesso'] = "Autor atualizado com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();
    $conn->close();

    header("location: /cadastro/autor/listar.php");
    exit;
}

// Operação de consulta (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];

        include '../../includes/database.php';

        $sql = "SELECT * FROM autor WHERE id = ?";
        $consulta = $conn->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $autor = $resultado->fetch_assoc();
        } else {
            $_SESSION['mensagem_erro'] = "Autor não encontrado.";
            $consulta->close();
            $conn->close();
            header("location: /cadastro/autor/listar.php");
            exit;
        }
    } else {
        $_SESSION['mensagem_erro'] = "Autor não encontrado.";
        header("location: /cadastro/autor/listar.php");
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
    <title>Alterar Autor</title>
</head>
<body>

<?php include '../../componentes/menu.php'; ?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Alterar Autor</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <input type="hidden" name="id" value="<?php echo escape($autor['id']); ?>">

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="autor">Autor</label>
                <input class="w3-input w3-border" type="text" id="autor" name="autor" value="<?php echo escape($autor['autor']); ?>" required>
            </div>
            <div class="w3-col s4">
                <label for="pseudonimo">Pseudônimo</label>
                <input class="w3-input w3-border" type="text" id="pseudonimo" name="pseudonimo" value="<?php echo escape($autor['pseudonimo']); ?>">
            </div>
            <div class="w3-col s4">
                <label for="nacionalidade">Nacionalidade</label>
                <input class="w3-input w3-border" type="text" id="nacionalidade" name="nacionalidade" value="<?php echo escape($autor['nacionalidade']); ?>">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="email" id="email" name="email" value="<?php echo escape($autor['email']); ?>">
            </div>
            <div class="w3-col s4">
                <label for="telefone">Telefone</label>
                <input class="w3-input w3-border" type="tel" id="telefone" name="telefone" value="<?php echo escape($autor['telefone']); ?>">
            </div>
            <div class="w3-col s4">
                <label for="endereco_web">Endereço Web</label>
                <input class="w3-input w3-border" type="text" id="endereco_web" name="endereco_web" value="<?php echo escape($autor['endereco_web']); ?>">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/cadastro/autor/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
