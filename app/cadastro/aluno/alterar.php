<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alterar Aluno</title>
</head>
<body>

<?php 
include '../../componentes/menu.html';

// Operação de consulta
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];

        include '../../includes/database.php'; 

        // Preparar e executar a consulta para buscar o aluno
        $sql = "SELECT * FROM aluno WHERE id = ?";
        $consulta = $conn->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        // Verifica se o aluno foi encontrado
        if ($resultado->num_rows > 0) {
            $aluno = $resultado->fetch_assoc();
        } else {
            $_SESSION['mensagem_erro'] = "Aluno não encontrado.";

            $consulta->close();
            $conn->close(); 

            header("location: /app/cadastro/aluno/listar.php");
        }
    } else {
        $_SESSION['mensagem_erro'] = "Aluno não encontrado.";

        $consulta->close();
        $conn->close(); 

        header("location: /app/cadastro/aluno/listar.php");
    }
// Operação de update
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php'; 
    
    $id = $_POST["id"];
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
    $sql = "UPDATE aluno set nome = ?, cpf = ?, telefone = ?, email = ?, cep = ?, estado = ?, cidade = ?, endereco = ?, bairro = ? WHERE id = ?";
    $update = $conn->prepare($sql);
    $update->bind_param("sssssssssi", $nome, $cpf, $telefone, $email, $cep, $estado, $cidade, $endereco, $bairro, $id);

    session_start(); // Inicia a sessão para acessar variáveis de sessão

    if ($update->execute()) {
        $_SESSION['mensagem_sucesso'] = "Aluno atualizado com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();
    
    header("location: /app/cadastro/aluno/listar.php");

}
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Alterar Aluno</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <!-- Escondendo o ID do aluno -->
        <input type="hidden" name="id" value="<?php echo $aluno['id']; ?>"> 
        
        <div class="w3-row-padding w3-margin-top"> <!-- Primeira linha de campos -->
            <div class="w3-col s6">
                <label for="nome">Nome</label>
                <input class="w3-input w3-border" type="text" id="nome" name="nome" value="<?php echo $aluno['nome']; ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="cpf">CPF</label>
                <input class="w3-input w3-border" type="text" id="cpf" name="cpf" value="<?php echo $aluno['cpf']; ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="telefone">Telefone</label>
                <input class="w3-input w3-border" type="text" id="telefone" name="telefone" value="<?php echo $aluno['telefone']; ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="text" id="email" name="email" value="<?php echo $aluno['email']; ?>" required>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top"> <!-- Segunda linha de campos -->
            <div class="w3-col s2">
                <label for="cep">CEP</label>
                <input class="w3-input w3-border" type="text" id="cep" name="cep" value="<?php echo $aluno['cep']; ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="estado">Estado</label>
                <input class="w3-input w3-border" type="text" id="estado" name="estado" value="<?php echo $aluno['estado']; ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="cidade">Cidade</label>
                <input class="w3-input w3-border" type="text" id="cidade" name="cidade" value="<?php echo $aluno['cidade']; ?>" required>
            </div>
            <div class="w3-col s4">
                <label for="endereco">Endereço</label>
                <input class="w3-input w3-border" type="text" id="endereco" name="endereco" value="<?php echo $aluno['endereco']; ?>" required>
            </div>
            <div class="w3-col s2">
                <label for="bairro">Bairro</label>
                <input class="w3-input w3-border" type="text" id="bairro" name="bairro" value="<?php echo $aluno['bairro']; ?>" required>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom"> <!-- Terceira linha de campos -->
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>    
        </div>
    </form>    
</div>

</body>
</html>
