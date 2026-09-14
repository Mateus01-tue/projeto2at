<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado  = isset($_SESSION['usuario_id']);
$ehAdmin = $logado && ($_SESSION['usuario_tipo'] ?? '') === 'admin';

if (!$ehAdmin) {
    http_response_code(403);
    echo json_encode(['erro' => 'Acesso não autorizado. Faça login para ver esses dados.']);
    exit;
}

include '../conexao.php';


$produtos = [];
$stmt = mysqli_prepare($conexao, "CALL sp_buscar_produtos(NULL, 0, 1000, 0)");
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($res)) {
    $produtos[] = [
        'id'          => (int)$row['id'],
        'nome'        => $row['nome'],
        'categoria'   => $row['categoria_nome'],
        'categoriaId' => (int)$row['categoria_id'],
        'preco'       => (float)$row['preco'],
        'estoque'     => (int)$row['estoque'],
        'imagem'      => $row['imagem'],
    ];
}
mysqli_stmt_close($stmt);

while (mysqli_more_results($conexao) && mysqli_next_result($conexao)) {;}


$pedidosMap = [];
$stmt = mysqli_prepare($conexao, "CALL sp_listar_pedidos(1000, 0)");
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($res)) {
    $pid = (int)$row['pedido_id'];

    if (!isset($pedidosMap[$pid])) {
        $pedidosMap[$pid] = [
            'id'      => $pid,
            'cliente' => $row['cliente_nome'],
            'data'    => $row['data_pedido'],
            'itens'   => [],
        ];
    }

    $pedidosMap[$pid]['itens'][] = [
        'produtoId'      => (int)$row['produto_id'],
        'nome'           => $row['produto_nome'],
        'quantidade'     => (int)$row['quantidade'],
        'precoUnitario'  => (float)$row['preco_unitario'],
    ];
}
mysqli_stmt_close($stmt);
while (mysqli_more_results($conexao) && mysqli_next_result($conexao)) {;}

echo json_encode([
    'produtos' => $produtos,
    'pedidos'  => array_values($pedidosMap),
], JSON_UNESCAPED_UNICODE);
