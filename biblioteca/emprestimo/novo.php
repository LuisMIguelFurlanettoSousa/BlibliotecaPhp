<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Novo Empréstimo</title>
</head>
<body>

<?php
include '../includes/validar_sessao.php';
include '../componentes/menu.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../includes/database.php';

    $id_aluno = $_POST["id_aluno"];
    $data_emprestimo = $_POST["data_emprestimo"];
    $data_devolucao_prevista = $_POST["data_devolucao_prevista"];
    $livros = isset($_POST["livros"]) ? $_POST["livros"] : [];
    $id_usuario = $_SESSION['user_id'];

    if (empty($livros)) {
        $_SESSION['mensagem_erro'] = "Selecione pelo menos um livro para o empréstimo.";
        header("location: /biblioteca/emprestimo/novo.php");
        exit;
    }

    // Inserir emprestimo
    $sql = "INSERT INTO emprestimo (id_usuario, id_aluno, data_emprestimo, data_devolucao_prevista) VALUES (?, ?, ?, ?)";
    $insert = $conn->prepare($sql);
    $insert->bind_param("iiss", $id_usuario, $id_aluno, $data_emprestimo, $data_devolucao_prevista);

    if ($insert->execute()) {
        $id_emprestimo = $conn->insert_id;

        // Inserir livros do emprestimo
        $sql_livro = "INSERT INTO emprestimo_livro (id_emprestimo, id_livro) VALUES (?, ?)";
        $insert_livro = $conn->prepare($sql_livro);

        foreach ($livros as $id_livro) {
            $insert_livro->bind_param("ii", $id_emprestimo, $id_livro);
            $insert_livro->execute();
        }

        $insert_livro->close();

        $_SESSION['mensagem_sucesso'] = "Empréstimo realizado com sucesso!";
        header("location: /biblioteca/emprestimo/listar.php");
        exit;
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao realizar empréstimo: " . $conn->error;
    }

    $insert->close();
}

// Buscar alunos e livros
include '../includes/database.php';
$alunos = $conn->query("SELECT * FROM aluno ORDER BY nome");
$livros = $conn->query("SELECT l.*, c.categoria, e.editora FROM livro l
                        LEFT JOIN categoria c ON l.id_categoria = c.id
                        LEFT JOIN editora e ON l.id_editora = e.id
                        ORDER BY l.titulo");
?>

<div class="w3-container">
    <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../componentes/mensagem_erro.php";
      }
    ?>

    <h2 class="w3-margin-top">Novo Empréstimo</h2>
    <form action='novo.php' method='post' class="w3-container w3-card-2 w3-margin-top">
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s6">
                <label for="id_aluno">Aluno</label>
                <select class="w3-select w3-border" id="id_aluno" name="id_aluno" required>
                    <option value="">Selecione...</option>
                    <?php
                    if ($alunos->num_rows > 0) {
                        while($aluno = $alunos->fetch_assoc()) {
                            echo "<option value='" . $aluno['id'] . "'>" . $aluno['nome'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="w3-col s3">
                <label for="data_emprestimo">Data Empréstimo</label>
                <input class="w3-input w3-border" type="date" id="data_emprestimo" name="data_emprestimo" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="w3-col s3">
                <label for="data_devolucao_prevista">Data Devolução Prevista</label>
                <input class="w3-input w3-border" type="date" id="data_devolucao_prevista" name="data_devolucao_prevista" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col s12">
                <label>Livros</label>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                    <?php
                    if ($livros->num_rows > 0) {
                        while($livro = $livros->fetch_assoc()) {
                            echo "<label>";
                            echo "<input type='checkbox' name='livros[]' value='" . $livro['id'] . "'> ";
                            echo $livro['titulo'] . " - " . $livro['categoria'] . " (" . $livro['editora'] . ")";
                            echo "</label><br>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-bottom" >
            <button type="submit" class="w3-button w3-blue w3-margin-top ">Realizar Empréstimo</button>
        </div>
    </form>
</div>
</body>
</html>
