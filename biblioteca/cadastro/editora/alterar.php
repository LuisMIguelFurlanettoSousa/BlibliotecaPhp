<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alterar Editora</title>
</head>
<body>

<?php
include '../../includes/validar_sessao.php';
include '../../componentes/menu.php';

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

            header("location: /biblioteca/cadastro/editora/listar.php");
        }
    } else {
        $_SESSION['mensagem_erro'] = "Editora não encontrada.";
        header("location: /biblioteca/cadastro/editora/listar.php");
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id = $_POST["id"];
    $edit = $_POST["editora"];
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

    $sql = "UPDATE editora set editora = ?, cnpj = ?, email = ?, telefone = ?, cep = ?, estado = ?, cidade = ?, bairro = ?, endereco = ?, nacionalidade = ?, endereco_web = ? WHERE id = ?";
    $update = $conn->prepare($sql);
    $update->bind_param("sssssssssssi", $edit, $cnpj, $email, $telefone, $cep, $estado, $cidade, $bairro, $endereco, $nacionalidade, $endereco_web, $id);

    if ($update->execute()) {
        $_SESSION['mensagem_sucesso'] = "Editora atualizada com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();

    header("location: /biblioteca/cadastro/editora/listar.php");

}
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Alterar Editora</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <input type="hidden" name="id" value="<?php echo $editora['id']; ?>">

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="editora">Editora</label>
                <input class="w3-input w3-border" type="text" id="editora" name="editora" value="<?php echo $editora['editora']; ?>" required>
            </div>
            <div class="w3-col s3">
                <label for="cnpj">CNPJ</label>
                <input class="w3-input w3-border" type="text" id="cnpj" name="cnpj" value="<?php echo $editora['cnpj']; ?>">
            </div>
            <div class="w3-col s3">
                <label for="email">Email</label>
                <input class="w3-input w3-border" type="email" id="email" name="email" value="<?php echo $editora['email']; ?>">
            </div>
            <div class="w3-col s2">
                <label for="telefone">Telefone</label>
                <input class="w3-input w3-border" type="text" id="telefone" name="telefone" value="<?php echo $editora['telefone']; ?>">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s2">
                <label for="cep">CEP</label>
                <input class="w3-input w3-border" type="text" id="cep" name="cep" value="<?php echo $editora['cep']; ?>">
            </div>
            <div class="w3-col s2">
                <label for="estado">Estado</label>
                <input class="w3-input w3-border" type="text" id="estado" name="estado" value="<?php echo $editora['estado']; ?>">
            </div>
            <div class="w3-col s2">
                <label for="cidade">Cidade</label>
                <input class="w3-input w3-border" type="text" id="cidade" name="cidade" value="<?php echo $editora['cidade']; ?>">
            </div>
            <div class="w3-col s2">
                <label for="bairro">Bairro</label>
                <input class="w3-input w3-border" type="text" id="bairro" name="bairro" value="<?php echo $editora['bairro']; ?>">
            </div>
            <div class="w3-col s4">
                <label for="endereco">Endereço</label>
                <input class="w3-input w3-border" type="text" id="endereco" name="endereco" value="<?php echo $editora['endereco']; ?>">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s4">
                <label for="nacionalidade">Nacionalidade</label>
                <input class="w3-input w3-border" type="text" id="nacionalidade" name="nacionalidade" value="<?php echo $editora['nacionalidade']; ?>">
            </div>
            <div class="w3-col s8">
                <label for="endereco_web">Endereço Web</label>
                <input class="w3-input w3-border" type="text" id="endereco_web" name="endereco_web" value="<?php echo $editora['endereco_web']; ?>">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
        </div>
    </form>
</div>

</body>
</html>
