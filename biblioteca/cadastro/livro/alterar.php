<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alterar Livro</title>
</head>
<body>

<?php
include '../../includes/validar_sessao.php';
include '../../componentes/menu.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["id"])) {
        $id = $_GET["id"];

        include '../../includes/database.php';

        $sql = "SELECT * FROM livro WHERE id = ?";
        $consulta = $conn->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $livro = $resultado->fetch_assoc();

            // Buscar autores atuais do livro
            $sql_autores_livro = "SELECT id_autor FROM livro_autor WHERE id_livro = ?";
            $stmt_autores = $conn->prepare($sql_autores_livro);
            $stmt_autores->bind_param("i", $id);
            $stmt_autores->execute();
            $result_autores = $stmt_autores->get_result();

            $autores_selecionados = [];
            while($row = $result_autores->fetch_assoc()) {
                $autores_selecionados[] = $row['id_autor'];
            }
        } else {
            $_SESSION['mensagem_erro'] = "Livro não encontrado.";
            $consulta->close();
            $conn->close();
            header("location: /biblioteca/cadastro/livro/listar.php");
            exit;
        }
    } else {
        $_SESSION['mensagem_erro'] = "Livro não encontrado.";
        header("location: /biblioteca/cadastro/livro/listar.php");
        exit;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id = $_POST["id"];
    $id_categoria = $_POST["id_categoria"];
    $id_editora = $_POST["id_editora"];
    $titulo = $_POST["titulo"];
    $ano_publicacao = $_POST["ano_publicacao"];
    $isbn = $_POST["isbn"];
    $autores = isset($_POST["autores"]) ? $_POST["autores"] : [];

    $sql = "UPDATE livro set id_categoria = ?, id_editora = ?, titulo = ?, ano_publicacao = ?, isbn = ? WHERE id = ?";
    $update = $conn->prepare($sql);
    $update->bind_param("iisssi", $id_categoria, $id_editora, $titulo, $ano_publicacao, $isbn, $id);

    if ($update->execute()) {
        // Remover relacionamentos antigos
        $sql_del = "DELETE FROM livro_autor WHERE id_livro = ?";
        $stmt_del = $conn->prepare($sql_del);
        $stmt_del->bind_param("i", $id);
        $stmt_del->execute();
        $stmt_del->close();

        // Inserir novos relacionamentos
        if (!empty($autores)) {
            $sql_autor = "INSERT INTO livro_autor (id_livro, id_autor) VALUES (?, ?)";
            $insert_autor = $conn->prepare($sql_autor);

            foreach ($autores as $id_autor) {
                $insert_autor->bind_param("ii", $id, $id_autor);
                $insert_autor->execute();
            }

            $insert_autor->close();
        }

        $_SESSION['mensagem_sucesso'] = "Livro atualizado com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados: " . $conn->error;
    }

    $update->close();

    header("location: /biblioteca/cadastro/livro/listar.php");
    exit;
}

// Buscar dados para os selects
$categorias = $conn->query("SELECT * FROM categoria ORDER BY categoria");
$editoras = $conn->query("SELECT * FROM editora ORDER BY editora");
$autores = $conn->query("SELECT * FROM autor ORDER BY autor");
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Alterar Livro</h2>
    <form action='alterar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <input type="hidden" name="id" value="<?php echo $livro['id']; ?>">

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s6">
                <label for="titulo">Título</label>
                <input class="w3-input w3-border" type="text" id="titulo" name="titulo" value="<?php echo $livro['titulo']; ?>" required>
            </div>
            <div class="w3-col s3">
                <label for="ano_publicacao">Ano Publicação</label>
                <input class="w3-input w3-border" type="text" id="ano_publicacao" name="ano_publicacao" value="<?php echo $livro['ano_publicacao']; ?>">
            </div>
            <div class="w3-col s3">
                <label for="isbn">ISBN</label>
                <input class="w3-input w3-border" type="text" id="isbn" name="isbn" value="<?php echo $livro['isbn']; ?>">
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
                            $selected = ($cat['id'] == $livro['id_categoria']) ? 'selected' : '';
                            echo "<option value='" . $cat['id'] . "' $selected>" . $cat['categoria'] . "</option>";
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
                            $selected = ($ed['id'] == $livro['id_editora']) ? 'selected' : '';
                            echo "<option value='" . $ed['id'] . "' $selected>" . $ed['editora'] . "</option>";
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
                            $checked = in_array($aut['id'], $autores_selecionados) ? 'checked' : '';
                            echo "<label>";
                            echo "<input type='checkbox' name='autores[]' value='" . $aut['id'] . "' $checked> ";
                            echo $aut['autor'];
                            echo "</label><br>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom">
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
        </div>
    </form>
</div>

</body>
</html>
