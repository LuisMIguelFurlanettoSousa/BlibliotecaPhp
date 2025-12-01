<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Cadastrar Livro</title>
</head>
<body>

<?php
include '../../includes/validar_sessao.php';
include '../../componentes/menu.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../../includes/database.php';

    $id_categoria = $_POST["id_categoria"];
    $id_editora = $_POST["id_editora"];
    $titulo = $_POST["titulo"];
    $ano_publicacao = $_POST["ano_publicacao"];
    $isbn = $_POST["isbn"];
    $autores = isset($_POST["autores"]) ? $_POST["autores"] : [];

    // Inserir livro
    $sql = "INSERT INTO livro (id_categoria, id_editora, titulo, ano_publicacao, isbn) VALUES (?, ?, ?, ?, ?)";
    $insert = $conn->prepare($sql);
    $insert->bind_param("iisss", $id_categoria, $id_editora, $titulo, $ano_publicacao, $isbn);

    if ($insert->execute()) {
        $id_livro = $conn->insert_id;

        // Inserir relacionamentos com autores
        if (!empty($autores)) {
            $sql_autor = "INSERT INTO livro_autor (id_livro, id_autor) VALUES (?, ?)";
            $insert_autor = $conn->prepare($sql_autor);

            foreach ($autores as $id_autor) {
                $insert_autor->bind_param("ii", $id_livro, $id_autor);
                $insert_autor->execute();
            }

            $insert_autor->close();
        }

        $_SESSION['mensagem_sucesso'] = "Novo livro inserido com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao inserir livro: " . $conn->error;
    }

    $insert->close();

    header("location: /biblioteca/cadastro/livro/listar.php");
    exit;
}

// Buscar categorias, editoras e autores
include '../../includes/database.php';
$categorias = $conn->query("SELECT * FROM categoria ORDER BY categoria");
$editoras = $conn->query("SELECT * FROM editora ORDER BY editora");
$autores = $conn->query("SELECT * FROM autor ORDER BY autor");
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Cadastrar Novo Livro</h2>
    <form action='adicionar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s6">
                <label for="titulo">Título</label>
                <input class="w3-input w3-border" type="text" id="titulo" name="titulo" required>
            </div>
            <div class="w3-col s3">
                <label for="ano_publicacao">Ano Publicação</label>
                <input class="w3-input w3-border" type="text" id="ano_publicacao" name="ano_publicacao">
            </div>
            <div class="w3-col s3">
                <label for="isbn">ISBN</label>
                <input class="w3-input w3-border" type="text" id="isbn" name="isbn">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s6">
                <label for="id_categoria">Categoria</label>
                <select class="w3-select w3-border" id="id_categoria" name="id_categoria" required>
                    <option value="">Selecione...</option>
                    <?php
                    if ($categorias->num_rows > 0) {
                        while($cat = $categorias->fetch_assoc()) {
                            echo "<option value='" . $cat['id'] . "'>" . $cat['categoria'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="w3-col s6">
                <label for="id_editora">Editora</label>
                <select class="w3-select w3-border" id="id_editora" name="id_editora" required>
                    <option value="">Selecione...</option>
                    <?php
                    if ($editoras->num_rows > 0) {
                        while($ed = $editoras->fetch_assoc()) {
                            echo "<option value='" . $ed['id'] . "'>" . $ed['editora'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s12">
                <label>Autores</label>
                <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                    <?php
                    if ($autores->num_rows > 0) {
                        while($aut = $autores->fetch_assoc()) {
                            echo "<label>";
                            echo "<input type='checkbox' name='autores[]' value='" . $aut['id'] . "'> ";
                            echo $aut['autor'];
                            echo "</label><br>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-bottom" >
            <button type="submit" class="w3-button w3-blue w3-margin-top ">Salvar</button>
        </div>
    </form>
</div>
</body>
</html>
