<?php
include '../../includes/validar_sessao.php';
include '../../includes/validacoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/database.php';

    $id_categoria = trim($_POST["id_categoria"]);
    $id_editora = trim($_POST["id_editora"]);
    $titulo = trim($_POST["titulo"]);
    $ano_publicacao = trim($_POST["ano_publicacao"]);
    $isbn = trim($_POST["isbn"]);
    $autores = isset($_POST["autores"]) ? $_POST["autores"] : [];

    // Validações
    $erros = [];

    if (empty($titulo)) {
        $erros[] = msg_erro('obrigatorio') . " (Título)";
    }

    if (empty($id_categoria)) {
        $erros[] = msg_erro('obrigatorio') . " (Categoria)";
    }

    if (empty($id_editora)) {
        $erros[] = msg_erro('obrigatorio') . " (Editora)";
    }

    if (!empty($isbn)) {
        $isbn_numeros = apenas_numeros($isbn);
        if (!validar_isbn($isbn_numeros)) {
            $erros[] = msg_erro('isbn');
        }
        $isbn = $isbn_numeros;
    }

    if (!empty($ano_publicacao)) {
        if (!validar_ano($ano_publicacao)) {
            $erros[] = msg_erro('ano');
        }
    }

    // Verificar se categoria existe
    if (!empty($id_categoria)) {
        $check_cat = $conn->prepare("SELECT id FROM categoria WHERE id = ?");
        $check_cat->bind_param("i", $id_categoria);
        $check_cat->execute();
        if ($check_cat->get_result()->num_rows == 0) {
            $erros[] = "Categoria selecionada não existe!";
        }
        $check_cat->close();
    }

    // Verificar se editora existe
    if (!empty($id_editora)) {
        $check_ed = $conn->prepare("SELECT id FROM editora WHERE id = ?");
        $check_ed->bind_param("i", $id_editora);
        $check_ed->execute();
        if ($check_ed->get_result()->num_rows == 0) {
            $erros[] = "Editora selecionada não existe!";
        }
        $check_ed->close();
    }

    // Verificar se ISBN já existe (se informado)
    if (!empty($isbn)) {
        $check_isbn = $conn->prepare("SELECT id FROM livro WHERE isbn = ?");
        $check_isbn->bind_param("s", $isbn);
        $check_isbn->execute();
        if ($check_isbn->get_result()->num_rows > 0) {
            $erros[] = "Este ISBN já está cadastrado!";
        }
        $check_isbn->close();
    }

    if (!empty($erros)) {
        $_SESSION['mensagem_erro'] = implode("<br>", $erros);
        $conn->close();
        header("location: /cadastro/livro/adicionar.php");
        exit;
    }

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
    $conn->close();

    header("location: /cadastro/livro/listar.php");
    exit;
}

// Buscar categorias, editoras e autores
include '../../includes/database.php';
$categorias = $conn->query("SELECT * FROM categoria ORDER BY categoria");
$editoras = $conn->query("SELECT * FROM editora ORDER BY editora");
$autores = $conn->query("SELECT * FROM autor ORDER BY autor");

$ano_atual = (int)date('Y');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Cadastrar Livro</title>
</head>
<body>

<?php include '../../componentes/menu.php'; ?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Cadastrar Novo Livro</h2>
    <form action='adicionar.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s6">
                <label for="titulo">Título</label>
                <input class="w3-input w3-border" type="text" id="titulo" name="titulo" required>
            </div>
            <div class="w3-col s3">
                <label for="ano_publicacao">Ano Publicação</label>
                <input class="w3-input w3-border" type="number" id="ano_publicacao" name="ano_publicacao" min="1000" max="<?php echo $ano_atual + 1; ?>">
            </div>
            <div class="w3-col s3">
                <label for="isbn">ISBN (10 ou 13 dígitos)</label>
                <input class="w3-input w3-border" type="text" id="isbn" name="isbn" maxlength="13" placeholder="Apenas números">
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
                            echo "<option value='" . escape($cat['id']) . "'>" . escape($cat['categoria']) . "</option>";
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
                            echo "<option value='" . escape($ed['id']) . "'>" . escape($ed['editora']) . "</option>";
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
                            echo "<input type='checkbox' name='autores[]' value='" . escape($aut['id']) . "'> ";
                            echo escape($aut['autor']);
                            echo "</label><br>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-bottom" >
            <button type="submit" class="w3-button w3-blue w3-margin-top">Salvar</button>
            <a href="/cadastro/livro/listar.php" class="w3-button w3-grey w3-margin-top">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
