<?php 
  if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if (isset($_GET['id'])) {
      include "../../includes/database.php";

      $id = (int) $_GET['id'];

      // Prepara e executa a exclusão do registro
      $sql = "DELETE FROM aluno WHERE id = ?";
      $atualizar = $conn->prepare($sql);
      $atualizar->bind_param("i", $id);
      
      if ($atualizar->execute()) {
        $_SESSION['mensagem_sucesso'] = "Aluno excluído com sucesso.";
      } else {
        $_SESSION['mensagem_erro'] = "Erro ao excluir registro: " . $conn->error;
      }

      $atualizar->close();
      $conn->close();

  // caso nao seja fornecido um id na requisicao
  } else {
    // Inicia a sessão para acessar variáveis de sessão
    $_SESSION['mensagem_erro'] = "Aluno não encontrado.";
  }
}

header("location: /app/cadastro/aluno/listar.php");

?>
