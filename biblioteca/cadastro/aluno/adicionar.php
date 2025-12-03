<?php
include '../../includes/validar_sessao.php';
include '../../includes/validacoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

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

    // Verificar se CPF já existe
    $check_sql = "SELECT id FROM aluno WHERE cpf = ?";
    $check = $conn->prepare($check_sql);
    $check->bind_param("s", $cpf);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $erros[] = "Este CPF já está cadastrado!";
    }
    $check->close();

    if (!empty($erros)) {
        $_SESSION['mensagem_erro'] = implode("<br>", $erros);
        $conn->close();
        header("location: /cadastro/aluno/adicionar.php");
        exit;
    }

    $sql = "INSERT INTO aluno (nome, cpf, telefone, email, cep, estado, cidade, endereco, bairro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert = $conn->prepare($sql);
    $insert->bind_param("sssssssss", $nome, $cpf, $telefone, $email, $cep, $estado, $cidade, $endereco, $bairro);

    if ($insert->execute()) {
        $_SESSION['mensagem_sucesso'] = "Novo aluno inserido com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao inserir aluno: " . $conn->error;
    }

    $insert->close();
    $conn->close();

    header("location: /cadastro/aluno/listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Cadastrar Aluno</title>
</head>
<body>

<?php include '../../componentes/menu.php'; ?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Cadastrar Novo Aluno</h2>
    <form action='adicionar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s6">
                <label for="nome">Nome</label>
                <input class="w3-input w3-border" type="text" id="nome" name="nome" required>
            </div>
            <div class="w3-col s2">
                <label for="cpf">CPF (apenas números)</label>
                <input class="w3-input w3-border" type="text" id="cpf" name="cpf" maxlength="14" placeholder="00000000000" required>
            </div>
            <div class="w3-col s2">
                <label for="telefone">Telefone (com DDD)</label>
                <input class="w3-input w3-border" type="tel" id="telefone" name="telefone" maxlength="15" placeholder="11999999999" required>
            </div>
            <div class="w3-col s2">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="email" id="email" name="email">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s2">
                <label for="cep">CEP (apenas números)</label>
                <input class="w3-input w3-border" type="text" id="cep" name="cep" maxlength="9" placeholder="00000000" required>
            </div>
            <div class="w3-col s2">
                <label for="estado">Estado</label>
                <select class="w3-select w3-border" id="estado" name="estado" required>
                    <option value="">Selecione...</option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AP">AP</option>
                    <option value="AM">AM</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MT">MT</option>
                    <option value="MS">MS</option>
                    <option value="MG">MG</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PR">PR</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RS">RS</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="SC">SC</option>
                    <option value="SP">SP</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                </select>
            </div>
            <div class="w3-col s2">
                <label for="cidade">Cidade</label>
                <input class="w3-input w3-border" type="text" id="cidade" name="cidade" required>
            </div>
            <div class="w3-col s4">
                <label for="endereco">Endereço</label>
                <input class="w3-input w3-border" type="text" id="endereco" name="endereco" required>
            </div>
            <div class="w3-col s2">
                <label for="bairro">Bairro</label>
                <input class="w3-input w3-border" type="text" id="bairro" name="bairro" required>
            </div>

        </div>
        <div class="w3-row-padding w3-margin-bottom" >
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/cadastro/aluno/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
