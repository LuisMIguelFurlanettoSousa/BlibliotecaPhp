<?php
include "../../includes/validar_sessao.php";
include "../../includes/database.php";
include "../../includes/validacoes.php";

$sql = "SELECT l.*, c.categoria, e.editora FROM livro l
        LEFT JOIN categoria c ON l.id_categoria = c.id
        LEFT JOIN editora e ON l.id_editora = e.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Livros</title>
</head>
<body>

<?php include "../../componentes/menu.php"; ?>

<div class="w3-container">
  <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }

      if (isset($_SESSION['mensagem_sucesso'])) {
        include "../../componentes/mensagem_sucesso.php";
      }
  ?>

  <h2 class="w3-margin-top">Lista de Livros</h2>

  <a href="/cadastro/livro/adicionar.php"><button class="w3-button w3-green w3-round">Novo Livro</button></a>

  <table class="w3-table-all w3-margin-top ">
    <thead>
      <tr class="w3-light-grey">
        <th>Título</th>
        <th>Categoria</th>
        <th>Editora</th>
        <th>Ano Publicação</th>
        <th>ISBN</th>
        <th>Autores</th>
        <th>Ações</th>
      </tr>
    </thead>


<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      // Buscar autores do livro
      $id_livro = $row['id'];
      $sql_autores = "SELECT a.autor FROM livro_autor la
                      JOIN autor a ON la.id_autor = a.id
                      WHERE la.id_livro = ?";
      $stmt_autores = $conn->prepare($sql_autores);
      $stmt_autores->bind_param("i", $id_livro);
      $stmt_autores->execute();
      $result_autores = $stmt_autores->get_result();

      $autores = [];
      while($autor = $result_autores->fetch_assoc()) {
          $autores[] = $autor['autor'];
      }
      $autores_texto = implode(", ", $autores);

      echo "<tr>";
      echo "<td>" . escape($row['titulo']) . "</td>";
      echo "<td>" . escape($row['categoria']) . "</td>";
      echo "<td>" . escape($row['editora']) . "</td>";
      echo "<td>" . escape($row['ano_publicacao']) . "</td>";
      echo "<td>" . escape($row['isbn']) . "</td>";
      echo "<td>" . escape($autores_texto) . "</td>";
      echo "<td>";
      echo "<a href='/cadastro/livro/alterar.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-blue'>Alterar</a> ";
      echo "<a href='/cadastro/livro/excluir.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-red' onclick=\"return confirm('Tem certeza que deseja excluir?')\">Excluir</a>";
      echo "</td>";
      echo "</tr>";

      $stmt_autores->close();
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
