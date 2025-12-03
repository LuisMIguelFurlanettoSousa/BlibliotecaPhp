<?php
include "../../includes/validar_sessao.php";
include "../../includes/database.php";
include "../../includes/validacoes.php";

$sql = "SELECT * FROM aluno";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Alunos</title>
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

  <h2 class="w3-margin-top">Lista de Alunos</h2>

  <a href="/cadastro/aluno/adicionar.php"><button class="w3-button w3-green w3-round">Novo Aluno</button></a>

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
      echo "<td>" . escape($row['nome']) . "</td>";
      echo "<td>" . escape(formatar_cpf($row['cpf'])) . "</td>";
      echo "<td>" . escape(formatar_telefone($row['telefone'])) . "</td>";
      echo "<td>" . escape($row['email']) . "</td>";
      echo "<td>" . escape(formatar_cep($row['cep'])) . "</td>";
      echo "<td>" . escape($row['estado']) . "</td>";
      echo "<td>" . escape($row['cidade']) . "</td>";
      echo "<td>" . escape($row['endereco']) . "</td>";
      echo "<td>" . escape($row['bairro']) . "</td>";
      echo "<td>";
      echo "<a href='/cadastro/aluno/alterar.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-blue'>Alterar</a> ";
      echo "<a href='/cadastro/aluno/excluir.php?id=". escape($row['id']) . "' class='w3-button w3-tiny w3-round w3-red' onclick=\"return confirm('Tem certeza que deseja excluir?')\">Excluir</a>";
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
