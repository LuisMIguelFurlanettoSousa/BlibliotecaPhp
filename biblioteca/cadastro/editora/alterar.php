<?php
include '../../includes/validar_sessao.php';
include '../../includes/validacoes.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];

        include '../../includes/database.php';

        $sql = "SELECT * FROM editora WHERE id = ?";
        $consulta = $conn->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $editora = $resultado->fetch_assoc();
        } else {
            $_SESSION['mensagem_erro'] = "Editora não encontrada.";

            $consulta->close();
            $conn->close();

            header("location: /cadastro/editora/listar.php");
            exit;
        }
        $consulta->close();
        $conn->close();
    } else {
        $_SESSION['mensagem_erro'] = "Editora não encontrada.";
        header("location: /cadastro/editora/listar.php");
        exit;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id = $_POST["id"];
    $edit = trim($_POST["editora"]);
    $cnpj = apenas_numeros($_POST["cnpj"]);
    $email = trim($_POST["email"]);
    $telefone = apenas_numeros($_POST["telefone"]);
    $cep = apenas_numeros($_POST["cep"]);
    $estado = trim($_POST["estado"]);
    $cidade = trim($_POST["cidade"]);
    $bairro = trim($_POST["bairro"]);
    $endereco = trim($_POST["endereco"]);
    $nacionalidade = trim($_POST["nacionalidade"]);
    $endereco_web = trim($_POST["endereco_web"]);

    // Validações
    $erros = [];

    if (!empty($cnpj) && !validar_cnpj($cnpj)) {
        $erros[] = msg_erro('cnpj');
    }

    if (!empty($telefone) && !validar_telefone($telefone)) {
        $erros[] = msg_erro('telefone');
    }

    if (!empty($email) && !validar_email($email)) {
        $erros[] = msg_erro('email');
    }

    if (!empty($cep) && !validar_cep($cep)) {
        $erros[] = msg_erro('cep');
    }

    // Verificar se CNPJ já existe em outra editora (se foi informado)
    if (!empty($cnpj)) {
        $check_sql = "SELECT id FROM editora WHERE cnpj = ? AND id != ?";
        $check = $conn->prepare($check_sql);
        $check->bind_param("si", $cnpj, $id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $erros[] = "Este CNPJ já está cadastrado em outra editora!";
        }
        $check->close();
    }

    if (!empty($erros)) {
        $_SESSION['mensagem_erro'] = implode("<br>", $erros);
        $conn->close();
        header("location: /cadastro/editora/alterar.php?id=$id");
        exit;
    }

    $sql = "UPDATE editora set editora = ?, cnpj = ?, email = ?, telefone = ?, cep = ?, estado = ?, cidade = ?, bairro = ?, endereco = ?, nacionalidade = ?, endereco_web = ? WHERE id = ?";
    $update = $conn->prepare($sql);
    $update->bind_param("sssssssssssi", $edit, $cnpj, $email, $telefone, $cep, $estado, $cidade, $bairro, $endereco, $nacionalidade, $endereco_web, $id);

    if ($update->execute()) {
        $_SESSION['mensagem_sucesso'] = "Editora atualizada com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();
    $conn->close();

    header("location: /cadastro/editora/listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alterar Editora</title>
</head>
<body>

<?php include '../../componentes/menu.php'; ?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Alterar Editora</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <input type="hidden" name="id" value="<?php echo escape($editora['id']); ?>">

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="editora">Editora</label>
                <input class="w3-input w3-border" type="text" id="editora" name="editora" value="<?php echo escape($editora['editora']); ?>" required>
            </div>
            <div class="w3-col s3">
                <label for="cnpj">CNPJ (apenas números)</label>
                <input class="w3-input w3-border" type="text" id="cnpj" name="cnpj" maxlength="18" placeholder="00000000000000" value="<?php echo escape($editora['cnpj']); ?>">
            </div>
            <div class="w3-col s3">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="email" id="email" name="email" value="<?php echo escape($editora['email']); ?>">
            </div>
            <div class="w3-col s2">
                <label for="telefone">Telefone (com DDD)</label>
                <input class="w3-input w3-border" type="tel" id="telefone" name="telefone" maxlength="15" placeholder="11999999999" value="<?php echo escape($editora['telefone']); ?>">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s2">
                <label for="cep">CEP (apenas números)</label>
                <input class="w3-input w3-border" type="text" id="cep" name="cep" maxlength="9" placeholder="00000000" value="<?php echo escape($editora['cep']); ?>">
            </div>
            <div class="w3-col s2">
                <label for="estado">Estado</label>
                <input class="w3-input w3-border" type="text" id="estado" name="estado" maxlength="2" placeholder="SP" value="<?php echo escape($editora['estado']); ?>">
            </div>
            <div class="w3-col s2">
                <label for="cidade">Cidade</label>
                <input class="w3-input w3-border" type="text" id="cidade" name="cidade" value="<?php echo escape($editora['cidade']); ?>">
            </div>
            <div class="w3-col s2">
                <label for="bairro">Bairro</label>
                <input class="w3-input w3-border" type="text" id="bairro" name="bairro" value="<?php echo escape($editora['bairro']); ?>">
            </div>
            <div class="w3-col s4">
                <label for="endereco">Endereço</label>
                <input class="w3-input w3-border" type="text" id="endereco" name="endereco" value="<?php echo escape($editora['endereco']); ?>">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="nacionalidade">Nacionalidade</label>
                <input class="w3-input w3-border" type="text" id="nacionalidade" name="nacionalidade" placeholder="Brasileira" value="<?php echo escape($editora['nacionalidade']); ?>">
            </div>
            <div class="w3-col s8">
                <label for="endereco_web">Endereço Web</label>
                <input class="w3-input w3-border" type="text" id="endereco_web" name="endereco_web" placeholder="https://www.editora.com.br" value="<?php echo escape($editora['endereco_web']); ?>">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/cadastro/editora/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
