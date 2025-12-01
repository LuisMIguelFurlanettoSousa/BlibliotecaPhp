<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Devolução de Livros</title>
</head>
<body>

<?php
include '../includes/validar_sessao.php';
include '../componentes/menu.php';
include '../includes/database.php';

// Processar devolução
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['devolver'])) {
    $livros_devolver = isset($_POST["livros_devolver"]) ? $_POST["livros_devolver"] : [];
    $data_devolucao = date('Y-m-d');

    if (!empty($livros_devolver)) {
        $sql_dev = "UPDATE emprestimo_livro SET data_devolucao = ? WHERE id = ?";
        $stmt_dev = $conn->prepare($sql_dev);

        foreach ($livros_devolver as $id_emprestimo_livro) {
            $stmt_dev->bind_param("si", $data_devolucao, $id_emprestimo_livro);
            $stmt_dev->execute();
        }

        $stmt_dev->close();
        $_SESSION['mensagem_sucesso'] = "Devolução registrada com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Selecione pelo menos um livro para devolver.";
    }
}

// Buscar empréstimos com livros pendentes
$sql = "SELECT DISTINCT e.id, e.data_emprestimo, e.data_devolucao_prevista, a.nome as aluno_nome
        FROM emprestimo e
        JOIN emprestimo_livro el ON e.id = el.id_emprestimo
        JOIN aluno a ON e.id_aluno = a.id
        WHERE el.data_devolucao IS NULL
        ORDER BY e.data_emprestimo DESC";
$emprestimos = $conn->query($sql);

// Se foi selecionado um empréstimo específico
$emprestimo_selecionado = null;
$livros_pendentes = [];
if (isset($_GET['id_emprestimo'])) {
    $id_emp = $_GET['id_emprestimo'];

    $sql_emp = "SELECT e.*, a.nome as aluno_nome
                FROM emprestimo e
                JOIN aluno a ON e.id_aluno = a.id
                WHERE e.id = ?";
    $stmt_emp = $conn->prepare($sql_emp);
    $stmt_emp->bind_param("i", $id_emp);
    $stmt_emp->execute();
    $result_emp = $stmt_emp->get_result();
    if ($result_emp->num_rows > 0) {
        $emprestimo_selecionado = $result_emp->fetch_assoc();

        // Buscar livros pendentes deste empréstimo
        $sql_livros = "SELECT el.id as id_emprestimo_livro, l.titulo, l.isbn
                       FROM emprestimo_livro el
                       JOIN livro l ON el.id_livro = l.id
                       WHERE el.id_emprestimo = ? AND el.data_devolucao IS NULL";
        $stmt_livros = $conn->prepare($sql_livros);
        $stmt_livros->bind_param("i", $id_emp);
        $stmt_livros->execute();
        $result_livros = $stmt_livros->get_result();

        while($livro = $result_livros->fetch_assoc()) {
            $livros_pendentes[] = $livro;
        }
    }
}
?>

<div class="w3-container">
  <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../componentes/mensagem_erro.php";
      }

      if (isset($_SESSION['mensagem_sucesso'])) {
        include "../componentes/mensagem_sucesso.php";
      }
  ?>

    <h2 class="w3-margin-top">Devolução de Livros</h2>

    <div class="w3-card-4 w3-margin-top">
        <div class="w3-container w3-blue">
            <h3>1. Selecione o Empréstimo</h3>
        </div>
        <div class="w3-container">
            <form method="GET" action="index.php">
                <div class="w3-row-padding w3-margin-top">
                    <div class="w3-col s10">
                        <label for="id_emprestimo">Empréstimo</label>
                        <select class="w3-select w3-border" id="id_emprestimo" name="id_emprestimo" required>
                            <option value="">Selecione um empréstimo...</option>
                            <?php
                            if ($emprestimos->num_rows > 0) {
                                while($emp = $emprestimos->fetch_assoc()) {
                                    $selected = (isset($_GET['id_emprestimo']) && $_GET['id_emprestimo'] == $emp['id']) ? 'selected' : '';
                                    echo "<option value='" . $emp['id'] . "' $selected>";
                                    echo "ID: " . $emp['id'] . " - " . $emp['aluno_nome'] . " - ";
                                    echo date('d/m/Y', strtotime($emp['data_emprestimo']));
                                    echo "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="w3-col s2">
                        <label>&nbsp;</label>
                        <button type="submit" class="w3-button w3-blue w3-block">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($emprestimo_selecionado && !empty($livros_pendentes)): ?>
    <div class="w3-card-4 w3-margin-top">
        <div class="w3-container w3-green">
            <h3>2. Selecione os Livros para Devolução</h3>
        </div>
        <div class="w3-container">
            <p><strong>Aluno:</strong> <?php echo $emprestimo_selecionado['aluno_nome']; ?></p>
            <p><strong>Data Empréstimo:</strong> <?php echo date('d/m/Y', strtotime($emprestimo_selecionado['data_emprestimo'])); ?></p>
            <p><strong>Data Devolução Prevista:</strong> <?php echo date('d/m/Y', strtotime($emprestimo_selecionado['data_devolucao_prevista'])); ?></p>

            <form method="POST" action="index.php?id_emprestimo=<?php echo $emprestimo_selecionado['id']; ?>">
                <div class="w3-margin-top">
                    <?php foreach($livros_pendentes as $livro): ?>
                    <label class="w3-block" style="padding: 10px; border: 1px solid #ccc; margin-bottom: 5px;">
                        <input type="checkbox" name="livros_devolver[]" value="<?php echo $livro['id_emprestimo_livro']; ?>">
                        <strong><?php echo $livro['titulo']; ?></strong> - ISBN: <?php echo $livro['isbn']; ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <div class="w3-margin-top w3-margin-bottom">
                    <button type="submit" name="devolver" class="w3-button w3-green">Registrar Devolução</button>
                </div>
            </form>
        </div>
    </div>
    <?php elseif ($emprestimo_selecionado): ?>
    <div class="w3-panel w3-pale-green">
        <p>Todos os livros deste empréstimo já foram devolvidos!</p>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
