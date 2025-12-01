<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Cadastrar Aluno</title>
</head>
<body>

<?php 
include '../../componentes/menu.html';

//operacao de insert
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../../includes/database.php'; 

    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $cep = $_POST["cep"];
    $estado = $_POST["estado"];
    $cidade = $_POST["cidade"];
    $endereco = $_POST["endereco"];
    $bairro = $_POST["bairro"];

    // Preparar e executar a inserção do novo registro
    $sql = "INSERT INTO aluno (nome, cpf, telefone, email, cep, estado, cidade, endereco, bairro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert = $conn->prepare($sql);
    $insert->bind_param("sssssssss", $nome, $cpf, $telefone, $email, $cep, $estado, $cidade, $endereco, $bairro);

    if ($insert->execute()) {
        $_SESSION['mensagem_sucesso'] = "Novo registro inserido com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao inserir registro: " . $conn->error;
    }

    $insert->close();
    
    header("location: /app/cadastro/aluno/listar.php");
}
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Cadastrar Novo Aluno</h2>
    <form action='adicionar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top"> <!-- primeira linha de campos -->
            <div class="w3-col s6">
                <label for="nome">Nome</label>
                <input class="w3-input w3-border" type="text" id="nome" name="nome" required>
            </div>
            <div class="w3-col s2">
                <label for="cpf">CPF</label>
                <input class="w3-input w3-border" type="text" id="cpf" name="cpf" required>
            </div>
            <div class="w3-col s2">
                <label for="telefone">Telefone</label>
                <input class="w3-input w3-border" type="text" id="telefone" name="telefone" required>
            </div>
            <div class="w3-col s2">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="text" id="email" name="email"  required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top"> <!-- segunda linha de campos -->
            <div class="w3-col s2">
                <label for="cep">CEP</label>
                <input class="w3-input w3-border" type="text" id="cep" name="cep" required>
            </div>
            <div class="w3-col s2">
                <label for="estado">Estado</label>
                <input class="w3-input w3-border" type="text" id="estado" name="estado" required>
            </div>
            <div class="w3-col s2">
                <label for="cidade">Cidade</label>
                <input class="w3-input w3-border" type="text" id="cidade" name="cidade" required>
            </div>
            <div class="w3-col s4">
                <label for="endereco">Endereço</label>
                <input class="w3-input w3-border" type="text" id="endereco" name="endereco"  required>
            </div>
            <div class="w3-col s2">
                <label for="bairro">Bairro</label>
                <input class="w3-input w3-border" type="text" id="bairro" name="bairro" required>
            </div>
            
        </div>
        <div class="w3-row-padding w3-margin-bottom" >  <!-- terceira linha de campos -->
            <button type="submit" class="w3-button w3-blue w3-margin-top ">Salvar</button>    
        </div>
    </form>    
</div>
</body>
</html>
