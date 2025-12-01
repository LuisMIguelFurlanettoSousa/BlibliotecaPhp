<?php
header('Content-Type: application/json; charset=utf-8');

$dados = [
  '1' => [
    ['value' => '1', 'label' => 'Maçã'],
    ['value' => '2', 'label' => 'Banana'],
    ['value' => '3', 'label' => 'Laranja']
  ],
  '2' => [
    ['value' => '1', 'label' => 'Volkswagen Gol'],
    ['value' => '2', 'label' => 'Fiat Uno'],
    ['value' => '3', 'label' => 'Honda Civic']
]
];



//Se for via GET
//$id = $_GET['categoria_id'] ?? '';

//Se for via POST
$id = $_POST['categoria_id'] ?? '';
//Outra opcao usando o operador ternario, ao inves do operador de coalescência nula (??)
//$id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : '';

$resultado = $dados[$id] ?? [];

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);


// Se fosse usar um banco de dados MySQL, o código seria algo assim:
// // Configurações de conexão
// $host = 'localhost';     // ou o IP do seu servidor
// $user = 'root';          // usuário do banco
// $pass = '';              // senha
// $dbname = 'meubanco';    // nome do seu banco de dados

// // Conexão com o MySQL
// $conn = new mysqli($host, $user, $pass, $dbname);

// // Verifica se deu erro na conexão
// if ($conn->connect_error) {
//     http_response_code(500);
//     echo json_encode(['erro' => 'Falha na conexão: ' . $conn->connect_error], JSON_UNESCAPED_UNICODE);
//     exit;
// }

// // Lê o parâmetro da requisição
// $id = $_POST['categoria_id'] ?? '';

// if ($id === '') {
//     echo json_encode([], JSON_UNESCAPED_UNICODE);
//     exit;
// }

// // Consulta ao banco de dados
// // Exemplo: tabela "opcoes" com colunas id, categoria_id, nome
// $sql = "SELECT id AS value, nome AS label 
//         FROM opcoes 
//         WHERE categoria_id = ? 
//         ORDER BY nome ASC";

// $stmt = $conn->prepare($sql);
// $stmt->bind_param('i', $id);
// $stmt->execute();

// $result = $stmt->get_result();

// // Monta o array de retorno
// $dados = [];
// while ($row = $result->fetch_assoc()) {
//     $dados[] = $row;
// }

// $stmt->close();
// $conn->close();

// // Retorna em formato JSON
// echo json_encode($dados, JSON_UNESCAPED_UNICODE);