<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Sistema Biblioteca</title>
</head>
<body>

<?php
include 'includes/validar_sessao.php';
include 'componentes/menu.php';
?>

<div class="w3-container">
    <h2 class="w3-margin-top">Bem-vindo ao Sistema de Biblioteca</h2>
    <div class="w3-panel w3-pale-blue w3-border">
        <h3>Olá, <?php echo $_SESSION['nome']; ?>!</h3>
        <p>Use o menu acima para navegar pelo sistema.</p>
    </div>

    <div class="w3-row-padding w3-margin-top">
        <div class="w3-third">
            <div class="w3-card-4">
                <div class="w3-container w3-blue">
                    <h3>Cadastros</h3>
                </div>
                <div class="w3-container">
                    <p>Gerencie alunos, autores, categorias, editoras e livros.</p>
                    <a href="/biblioteca/cadastro/aluno/listar.php" class="w3-button w3-blue w3-margin-bottom">Acessar</a>
                </div>
            </div>
        </div>

        <div class="w3-third">
            <div class="w3-card-4">
                <div class="w3-container w3-green">
                    <h3>Empréstimos</h3>
                </div>
                <div class="w3-container">
                    <p>Realize novos empréstimos e consulte os existentes.</p>
                    <a href="/biblioteca/emprestimo/novo.php" class="w3-button w3-green w3-margin-bottom">Acessar</a>
                </div>
            </div>
        </div>

        <div class="w3-third">
            <div class="w3-card-4">
                <div class="w3-container w3-orange">
                    <h3>Devoluções</h3>
                </div>
                <div class="w3-container">
                    <p>Registre a devolução de livros emprestados.</p>
                    <a href="/biblioteca/devolucao/index.php" class="w3-button w3-orange w3-margin-bottom">Acessar</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
