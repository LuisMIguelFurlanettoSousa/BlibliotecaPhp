<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alunos</title>
</head>
<body>

<?php
  include "../../includes/validar_sessao.php";
  include "../../componentes/menu.php";
  include "../../includes/database.php";

  $sql = "SELECT * FROM aluno";
  $result = $conn->query($sql);
?>

<div class="w3-container">
  <?php
      if (isset($_SESSION['mensagem_erro'])) {
        include "../../componentes/mensagem_erro.php";
      }

      if (isset($_SESSION['mensagem_sucesso'])) {
        include "../../componentes/mensagem_sucesso.php";
      }
  ?>

  <h2 class="w3-margin-top">Lista de Alunos</h2>

  <a href="/biblioteca/cadastro/aluno/adicionar.php"><button class="w3-button w3-green w3-round">Novo Aluno</button></a>

  <table class="w3-table-all w3-margin-top ">
    <thead>
      <tr class="w3-light-grey">
        <th>Nome</th>
        <th>CPF</th>
        <th>Telefone</th>
        <th>Email</th>
        <th>CEP</th>
        <th>Estado</th>
        <th>Cidade</th>
        <th>Endereço</th>
        <th>Bairro</th>
        <th>Ações</th>
      </tr>
    </thead>


<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row['nome'] . "</td>";
      echo "<td>" . $row['cpf'] . "</td>";
      echo "<td>" . $row['telefone'] . "</td>";
      echo "<td>" . $row['email'] . "</td>";
      echo "<td>" . $row['cep'] . "</td>";
      echo "<td>" . $row['estado'] . "</td>";
      echo "<td>" . $row['cidade'] . "</td>";
      echo "<td>" . $row['endereco'] . "</td>";
      echo "<td>" . $row['bairro'] . "</td>";
      echo "<td>";
      echo "<a href='/biblioteca/cadastro/aluno/alterar.php?id=". $row['id'] . "' class='w3-button w3-tiny w3-round w3-blue'>Alterar</a>";
      echo "<a href='/biblioteca/cadastro/aluno/excluir.php?id=". $row['id'] . "' class='w3-button w3-tiny w3-round w3-red'>Excluir</a>";
      echo "</td>";
      echo "</tr>";
  }
} else {
  echo "<tr><td colspan='10'>Nenhum registro encontrado</td></tr>";
}

$conn->close();
?>
  </table>
</div>
</body>
</html>
