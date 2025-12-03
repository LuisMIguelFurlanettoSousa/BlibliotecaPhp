<?php
include "../../includes/validar_sessao.php";
include "../../includes/database.php";
include "../../includes/validacoes.php";

$sql = "SELECT * FROM autor";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Autores</title>
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

  <h2 class="w3-margin-top">Lista de Autores</h2>

  <a href="/cadastro/autor/adicionar.php"><button class="w3-button w3-green w3-round">Novo Autor</button></a>

  <table class="w3-table-all w3-margin-top ">
    <thead>
      <tr class="w3-light-grey">
        <th>Autor</th>
        <th>Pseudônimo</th>
        <th>Nacionalidade</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Endereço Web</th>
        <th>Ações</th>
      </tr>
    </thead>


<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . escape($row['autor']) . "</td>";
      echo "<td>" . escape($row['pseudonimo']) . "</td>";
      echo "<td>" . escape($row['nacionalidade']) . "</td>";
      echo "<td>" . escape($row['email']) . "</td>";
      echo "<td>" . escape(formatar_telefone($row['telefone'])) . "</td>";
      echo "<td>" . escape($row['endereco_web']) . "</td>";
      echo "<td>";
      echo "<a href='/cadastro/autor/alterar.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-blue'>Alterar</a> ";
      echo "<a href='/cadastro/autor/excluir.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-red' onclick=\"return confirm('Tem certeza que deseja excluir?')\">Excluir</a>";
      echo "</td>";
      echo "</tr>";
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
