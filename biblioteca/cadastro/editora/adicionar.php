<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Cadastrar Editora</title>
</head>
<body>

<?php
include '../../includes/validar_sessao.php';
include '../../componentes/menu.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../../includes/database.php';

    $editora = $_POST["editora"];
    $cnpj = $_POST["cnpj"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $cep = $_POST["cep"];
    $estado = $_POST["estado"];
    $cidade = $_POST["cidade"];
    $bairro = $_POST["bairro"];
    $endereco = $_POST["endereco"];
    $nacionalidade = $_POST["nacionalidade"];
    $endereco_web = $_POST["endereco_web"];

    $sql = "INSERT INTO editora (editora, cnpj, email, telefone, cep, estado, cidade, bairro, endereco, nacionalidade, endereco_web) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert = $conn->prepare($sql);
    $insert->bind_param("sssssssssss", $editora, $cnpj, $email, $telefone, $cep, $estado, $cidade, $bairro, $endereco, $nacionalidade, $endereco_web);

    if ($insert->execute()) {
        $_SESSION['mensagem_sucesso'] = "Nova editora inserida com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao inserir editora: " . $conn->error;
    }

    $insert->close();

    header("location: /biblioteca/cadastro/editora/listar.php");
}
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Cadastrar Nova Editora</h2>
    <form action='adicionar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="editora">Editora</label>
                <input class="w3-input w3-border" type="text" id="editora" name="editora" required>
            </div>
            <div class="w3-col s3">
                <label for="cnpj">CNPJ</label>
                <input class="w3-input w3-border" type="text" id="cnpj" name="cnpj">
            </div>
            <div class="w3-col s3">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="email" id="email" name="email">
            </div>
            <div class="w3-col s2">
                <label for="telefone">Telefone</label>
                <input class="w3-input w3-border" type="text" id="telefone" name="telefone">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s2">
                <label for="cep">CEP</label>
                <input class="w3-input w3-border" type="text" id="cep" name="cep">
            </div>
            <div class="w3-col s2">
                <label for="estado">Estado</label>
                <input class="w3-input w3-border" type="text" id="estado" name="estado">
            </div>
            <div class="w3-col s2">
                <label for="cidade">Cidade</label>
                <input class="w3-input w3-border" type="text" id="cidade" name="cidade">
            </div>
            <div class="w3-col s2">
                <label for="bairro">Bairro</label>
                <input class="w3-input w3-border" type="text" id="bairro" name="bairro">
            </div>
            <div class="w3-col s4">
                <label for="endereco">Endereço</label>
                <input class="w3-input w3-border" type="text" id="endereco" name="endereco">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="nacionalidade">Nacionalidade</label>
                <input class="w3-input w3-border" type="text" id="nacionalidade" name="nacionalidade">
            </div>
            <div class="w3-col s8">
                <label for="endereco_web">Endereço Web</label>
                <input class="w3-input w3-border" type="text" id="endereco_web" name="endereco_web">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-bottom" >
            <button type="submit" class="w3-button w3-blue w3-margin-top ">Salvar</button>
        </div>
    </form>
</div>
</body>
</html>
