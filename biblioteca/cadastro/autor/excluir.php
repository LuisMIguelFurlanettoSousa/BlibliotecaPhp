<?php
include "../../includes/validar_sessao.php";

if ($_SERVER["REQUEST_METHOD"] == "GET"){
  if (isset($_GET['id'])) {
    include "../../includes/database.php";

    $id = (int) $_GET['id'];

    $sql = "DELETE FROM autor WHERE id = ?";
    $atualizar = $conn->prepare($sql);
    $atualizar->bind_param("i", $id);

    if ($atualizar->execute()) {
      $_SESSION['mensagem_sucesso'] = "Autor excluído com sucesso.";
    } else {
      $_SESSION['mensagem_erro'] = "Erro ao excluir registro: " . $conn->error;
    }

    $atualizar->close();
    $conn->close();

  } else {
    $_SESSION['mensagem_erro'] = "Autor não encontrado.";
  }
}

header("location: /biblioteca/cadastro/autor/listar.php");

?>
