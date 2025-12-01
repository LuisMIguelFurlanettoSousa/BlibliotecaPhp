<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Empréstimos</title>
</head>
<body>

<?php
  include "../includes/validar_sessao.php";
  include "../componentes/menu.php";
  include "../includes/database.php";

  $sql = "SELECT e.*, a.nome as aluno_nome, u.nome as usuario_nome
          FROM emprestimo e
          LEFT JOIN aluno a ON e.id_aluno = a.id
          LEFT JOIN usuario u ON e.id_usuario = u.id
          ORDER BY e.data_emprestimo DESC";
  $result = $conn->query($sql);
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

  <h2 class="w3-margin-top">Lista de Empréstimos</h2>

  <a href="/biblioteca/emprestimo/novo.php"><button class="w3-button w3-green w3-round">Novo Empréstimo</button></a>

  <table class="w3-table-all w3-margin-top ">
    <thead>
      <tr class="w3-light-grey">
        <th>ID</th>
        <th>Aluno</th>
        <th>Data Empréstimo</th>
        <th>Data Devolução Prevista</th>
        <th>Usuário</th>
        <th>Livros</th>
        <th>Status</th>
      </tr>
    </thead>


<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      // Buscar livros do emprestimo e status de devolução
      $id_emprestimo = $row['id'];
      $sql_livros = "SELECT l.titulo, el.data_devolucao
                     FROM emprestimo_livro el
                     JOIN livro l ON el.id_livro = l.id
                     WHERE el.id_emprestimo = ?";
      $stmt_livros = $conn->prepare($sql_livros);
      $stmt_livros->bind_param("i", $id_emprestimo);
      $stmt_livros->execute();
      $result_livros = $stmt_livros->get_result();

      $livros_list = [];
      $todos_devolvidos = true;
      while($livro = $result_livros->fetch_assoc()) {
          $status_livro = $livro['data_devolucao'] ? " (Devolvido)" : " (Pendente)";
          $livros_list[] = $livro['titulo'] . $status_livro;
          if (!$livro['data_devolucao']) {
              $todos_devolvidos = false;
          }
      }
      $livros_texto = implode("<br>", $livros_list);
      $status_emprestimo = $todos_devolvidos ? "<span class='w3-tag w3-green'>Concluído</span>" : "<span class='w3-tag w3-orange'>Em andamento</span>";

      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['aluno_nome'] . "</td>";
      echo "<td>" . date('d/m/Y', strtotime($row['data_emprestimo'])) . "</td>";
      echo "<td>" . date('d/m/Y', strtotime($row['data_devolucao_prevista'])) . "</td>";
      echo "<td>" . $row['usuario_nome'] . "</td>";
      echo "<td>" . $livros_texto . "</td>";
      echo "<td>" . $status_emprestimo . "</td>";
      echo "</tr>";

      $stmt_livros->close();
  }
} else {
  echo "<tr><td colspan='7'>Nenhum registro encontrado</td></tr>";
}

$conn->close();
?>
  </table>
</div>
</body>
</html>
