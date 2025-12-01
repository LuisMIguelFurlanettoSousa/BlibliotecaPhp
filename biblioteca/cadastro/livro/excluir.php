<?php
include "../../includes/validar_sessao.php";

if ($_SERVER["REQUEST_METHOD"] == "GET"){
  if (isset($_GET['id'])) {
    include "../../includes/database.php";

    $id = (int) $_GET['id'];

    // Primeiro excluir relacionamentos com autores
    $sql_rel = "DELETE FROM livro_autor WHERE id_livro = ?";
    $stmt_rel = $conn->prepare($sql_rel);
    $stmt_rel->bind_param("i", $id);
    $stmt_rel->execute();
    $stmt_rel->close();

    // Depois excluir o livro
    $sql = "DELETE FROM livro WHERE id = ?";
    $atualizar = $conn->prepare($sql);
    $atualizar->bind_param("i", $id);

    if ($atualizar->execute()) {
      $_SESSION['mensagem_sucesso'] = "Livro excluído com sucesso.";
    } else {
      $_SESSION['mensagem_erro'] = "Erro ao excluir registro: " . $conn->error;
    }

    $atualizar->close();
    $conn->close();

  } else {
    $_SESSION['mensagem_erro'] = "Livro não encontrado.";
  }
}

header("location: /biblioteca/cadastro/livro/listar.php");

?>
