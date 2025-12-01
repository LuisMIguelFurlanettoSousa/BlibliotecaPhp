<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Cadastrar Autor</title>
</head>
<body>

<?php
include '../../includes/validar_sessao.php';
include '../../componentes/menu.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../../includes/database.php';

    $autor = $_POST["autor"];
    $pseudonimo = $_POST["pseudonimo"];
    $nacionalidade = $_POST["nacionalidade"];
    $endereco_web = $_POST["endereco_web"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    $sql = "INSERT INTO autor (autor, pseudonimo, nacionalidade, endereco_web, email, telefone) VALUES (?, ?, ?, ?, ?, ?)";
    $insert = $conn->prepare($sql);
    $insert->bind_param("ssssss", $autor, $pseudonimo, $nacionalidade, $endereco_web, $email, $telefone);

    if ($insert->execute()) {
        $_SESSION['mensagem_sucesso'] = "Novo autor inserido com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao inserir autor: " . $conn->error;
    }

    $insert->close();

    header("location: /biblioteca/cadastro/autor/listar.php");
}
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Cadastrar Novo Autor</h2>
    <form action='adicionar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="autor">Autor</label>
                <input class="w3-input w3-border" type="text" id="autor" name="autor" required>
            </div>
            <div class="w3-col s4">
                <label for="pseudonimo">Pseudônimo</label>
                <input class="w3-input w3-border" type="text" id="pseudonimo" name="pseudonimo">
            </div>
            <div class="w3-col s4">
                <label for="nacionalidade">Nacionalidade</label>
                <input class="w3-input w3-border" type="text" id="nacionalidade" name="nacionalidade">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="email" id="email" name="email">
            </div>
            <div class="w3-col s4">
                <label for="telefone">Telefone</label>
                <input class="w3-input w3-border" type="text" id="telefone" name="telefone">
            </div>
            <div class="w3-col s4">
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
