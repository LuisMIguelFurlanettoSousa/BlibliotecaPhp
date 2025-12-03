<?php
include '../../includes/validar_sessao.php';
include '../../includes/validacoes.php';

// Operação de update - ANTES de qualquer output HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id = $_POST["id"];
    $nome = trim($_POST["nome"]);
    $cpf = apenas_numeros($_POST["cpf"]);
    $telefone = apenas_numeros($_POST["telefone"]);
    $email = trim($_POST["email"]);
    $cep = apenas_numeros($_POST["cep"]);
    $estado = trim($_POST["estado"]);
    $cidade = trim($_POST["cidade"]);
    $endereco = trim($_POST["endereco"]);
    $bairro = trim($_POST["bairro"]);

    // Validações
    $erros = [];

    if (!validar_cpf($cpf)) {
        $erros[] = msg_erro('cpf');
    }

    if (!validar_telefone($telefone)) {
        $erros[] = msg_erro('telefone');
    }

    if (!empty($email) && !validar_email($email)) {
        $erros[] = msg_erro('email');
    }

    if (!validar_cep($cep)) {
        $erros[] = msg_erro('cep');
    }

    // Verificar se CPF já existe (excluindo o atual)
    $check_sql = "SELECT id FROM aluno WHERE cpf = ? AND id != ?";
    $check = $conn->prepare($check_sql);
    $check->bind_param("si", $cpf, $id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $erros[] = "Este CPF já está cadastrado para outro aluno!";
    }
    $check->close();

    if (!empty($erros)) {
        $_SESSION['mensagem_erro'] = implode("<br>", $erros);
        $conn->close();
        header("location: /cadastro/aluno/alterar.php?id=" . $id);
        exit;
    }

    $sql = "UPDATE aluno set nome = ?, cpf = ?, telefone = ?, email = ?, cep = ?, estado = ?, cidade = ?, endereco = ?, bairro = ? WHERE id = ?";
    $update = $conn->prepare($sql);
    $update->bind_param("sssssssssi", $nome, $cpf, $telefone, $email, $cep, $estado, $cidade, $endereco, $bairro, $id);

    if ($update->execute()) {
        $_SESSION['mensagem_sucesso'] = "Aluno atualizado com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();
    $conn->close();

    header("location: /cadastro/aluno/listar.php");
    exit;
}

// Operação de consulta (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];

        include '../../includes/database.php';

        $sql = "SELECT * FROM aluno WHERE id = ?";
        $consulta = $conn->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $aluno = $resultado->fetch_assoc();
        } else {
            $_SESSION['mensagem_erro'] = "Aluno não encontrado.";
            $consulta->close();
            $conn->close();
            header("location: /cadastro/aluno/listar.php");
            exit;
        }
    } else {
        $_SESSION['mensagem_erro'] = "Aluno não encontrado.";
        header("location: /cadastro/aluno/listar.php");
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
    <title>Alterar Aluno</title>
</head>
<body>

<?php include '../../componentes/menu.php'; ?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Alterar Aluno</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <input type="hidden" name="id" value="<?php echo escape($aluno['id']); ?>">

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s6">
                <label for="nome">Nome</label>
                <input class="w3-input w3-border" type="text" id="nome" name="nome" value="<?php echo escape($aluno['nome']); ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="cpf">CPF (apenas números)</label>
                <input class="w3-input w3-border" type="text" id="cpf" name="cpf" value="<?php echo escape($aluno['cpf']); ?>" maxlength="14" required>
            </div>
            <div class="w3-col s2">
                <label for="telefone">Telefone (com DDD)</label>
                <input class="w3-input w3-border" type="tel" id="telefone" name="telefone" value="<?php echo escape($aluno['telefone']); ?>" maxlength="15" required>
            </div>
            <div class="w3-col s2">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="email" id="email" name="email" value="<?php echo escape($aluno['email']); ?>">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s2">
                <label for="cep">CEP (apenas números)</label>
                <input class="w3-input w3-border" type="text" id="cep" name="cep" value="<?php echo escape($aluno['cep']); ?>" maxlength="9" required>
            </div>
            <div class="w3-col s2">
                <label for="estado">Estado</label>
                <select class="w3-select w3-border" id="estado" name="estado" required>
                    <option value="">Selecione...</option>
                    <?php
                    $estados = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
                    foreach ($estados as $uf) {
                        $selected = ($aluno['estado'] == $uf) ? 'selected' : '';
                        echo "<option value=\"$uf\" $selected>$uf</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="w3-col s2">
                <label for="cidade">Cidade</label>
                <input class="w3-input w3-border" type="text" id="cidade" name="cidade" value="<?php echo escape($aluno['cidade']); ?>" required>
            </div>
            <div class="w3-col s4">
                <label for="endereco">Endereço</label>
                <input class="w3-input w3-border" type="text" id="endereco" name="endereco" value="<?php echo escape($aluno['endereco']); ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="bairro">Bairro</label>
                <input class="w3-input w3-border" type="text" id="bairro" name="bairro" value="<?php echo escape($aluno['bairro']); ?>" required>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/cadastro/aluno/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
