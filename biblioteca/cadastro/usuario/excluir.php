<?php
session_start();
include '../../includes/validar_sessao.php';
include '../../includes/database.php';

if(isset($_GET["id"])) {
    $id = $_GET["id"];

    // Não permitir excluir o próprio usuário logado
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
        $_SESSION['mensagem_erro'] = "Você não pode excluir seu próprio usuário!";
        header("location: /biblioteca/cadastro/usuario/listar.php");
        exit;
    }

    // Verificar se usuário tem empréstimos registrados
    $check_sql = "SELECT id FROM emprestimo WHERE id_usuario = ?";
    $check = $conn->prepare($check_sql);
    $check->bind_param("i", $id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['mensagem_erro'] = "Não é possível excluir este usuário pois ele possui empréstimos registrados.";
        $check->close();
        $conn->close();
        header("location: /biblioteca/cadastro/usuario/listar.php");
        exit;
    }
    $check->close();

    // Preparar e executar a exclusão
    $sql = "DELETE FROM usuario WHERE id = ?";
    $delete = $conn->prepare($sql);
    $delete->bind_param("i", $id);

    if ($delete->execute()) {
        $_SESSION['mensagem_sucesso'] = "Usuário excluído com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao excluir usuário: " . $conn->error;
    }

    $delete->close();
} else {
    $_SESSION['mensagem_erro'] = "ID do usuário não informado.";
}

$conn->close();
header("location: /biblioteca/cadastro/usuario/listar.php");
exit;
?>
