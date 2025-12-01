<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Livros Emprestados</title>
</head>
<body>

<?php
  include "../includes/validar_sessao.php";
  include "../componentes/menu.php";
  include "../includes/database.php";

  // Buscar livros atualmente emprestados (não devolvidos)
  $sql = "SELECT l.titulo, l.isbn, c.categoria, e.editora, a.nome as aluno_nome,
                 emp.data_emprestimo, emp.data_devolucao_prevista,
                 DATEDIFF(CURDATE(), emp.data_devolucao_prevista) as dias_atraso
          FROM emprestimo_livro el
          JOIN livro l ON el.id_livro = l.id
          JOIN emprestimo emp ON el.id_emprestimo = emp.id
          JOIN aluno a ON emp.id_aluno = a.id
          LEFT JOIN categoria c ON l.id_categoria = c.id
          LEFT JOIN editora e ON l.id_editora = e.id
          WHERE el.data_devolucao IS NULL
          ORDER BY emp.data_devolucao_prevista ASC";

  $result = $conn->query($sql);
?>

<div class="w3-container">
  <h2 class="w3-margin-top">Relatório: Livros Atualmente Emprestados</h2>

  <div class="w3-panel w3-pale-blue">
      <p><strong>Data do Relatório:</strong> <?php echo date('d/m/Y H:i'); ?></p>
      <p><strong>Total de Livros Emprestados:</strong> <?php echo $result->num_rows; ?></p>
  </div>

  <table class="w3-table-all w3-margin-top ">
    <thead>
      <tr class="w3-light-grey">
        <th>Título</th>
        <th>ISBN</th>
        <th>Categoria</th>
        <th>Editora</th>
        <th>Aluno</th>
        <th>Data Empréstimo</th>
        <th>Devolução Prevista</th>
        <th>Status</th>
      </tr>
    </thead>


<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      $dias_atraso = $row['dias_atraso'];
      $status_class = '';
      $status_text = 'No prazo';

      if ($dias_atraso > 0) {
          $status_class = 'w3-red';
          $status_text = 'Atrasado ' . $dias_atraso . ' dia(s)';
      } elseif ($dias_atraso == 0) {
          $status_class = 'w3-yellow';
          $status_text = 'Vence hoje';
      } else {
          $status_class = 'w3-green';
          $status_text = 'No prazo';
      }

      echo "<tr class='$status_class'>";
      echo "<td>" . $row['titulo'] . "</td>";
      echo "<td>" . $row['isbn'] . "</td>";
      echo "<td>" . $row['categoria'] . "</td>";
      echo "<td>" . $row['editora'] . "</td>";
      echo "<td>" . $row['aluno_nome'] . "</td>";
      echo "<td>" . date('d/m/Y', strtotime($row['data_emprestimo'])) . "</td>";
      echo "<td>" . date('d/m/Y', strtotime($row['data_devolucao_prevista'])) . "</td>";
      echo "<td>" . $status_text . "</td>";
      echo "</tr>";
  }
} else {
  echo "<tr><td colspan='8'>Nenhum livro emprestado no momento</td></tr>";
}

$conn->close();
?>
  </table>

  <div class="w3-panel w3-margin-top">
      <button onclick="window.print()" class="w3-button w3-blue">Imprimir Relatório</button>
  </div>
</div>

<style>
@media print {
    .w3-bar, button {
        display: none;
    }
}
</style>

</body>
</html>
