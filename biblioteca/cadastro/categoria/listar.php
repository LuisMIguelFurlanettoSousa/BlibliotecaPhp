<?php
include "../../includes/validar_sessao.php";
include "../../includes/database.php";
include "../../includes/validacoes.php";

$sql = "SELECT * FROM categoria";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Categorias</title>
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

  <h2 class="w3-margin-top">Lista de Categorias</h2>

  <a href="/cadastro/categoria/adicionar.php"><button class="w3-button w3-green w3-round">Nova Categoria</button></a>

  <table class="w3-table-all w3-margin-top ">
    <thead>
      <tr class="w3-light-grey">
        <th>Categoria</th>
        <th>Descrição</th>
        <th>Ações</th>
      </tr>
    </thead>


<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . escape($row['categoria']) . "</td>";
      echo "<td>" . escape($row['descricao']) . "</td>";
      echo "<td>";
      echo "<a href='/cadastro/categoria/alterar.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-blue'>Alterar</a> ";
      echo "<a href='/cadastro/categoria/excluir.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-red' onclick=\"return confirm('Tem certeza que deseja excluir?')\">Excluir</a>";
      echo "</td>";
      echo "</tr>";
  }
} else {
  echo "<tr><td colspan='3'>Nenhum registro encontrado</td></tr>";
}

$conn->close();
?>
  </table>
</div>
</body>
</html>
