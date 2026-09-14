<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: produtos_listar.php");
    exit;
}

$id          = (int)($_POST['id'] ?? 0);
$nome        = trim($_POST['nome'] ?? '');
$descricao   = trim($_POST['descricao'] ?? '');
$preco       = (float)($_POST['preco'] ?? 0);
$estoque     = (int)($_POST['estoque'] ?? 0);
$imagem      = trim($_POST['imagem'] ?? '');
$categoriaId = (int)($_POST['categoria_id'] ?? 0);


if ($nome === '' || $preco <= 0 || $categoriaId <= 0) {
    header("Location: produto_form.php?id=$id&erro=1");
    exit;
}

if ($id > 0) {
    
    
    $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=?, imagem=?, categoria_id=? WHERE id=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdisii', $nome, $descricao, $preco, $estoque, $imagem, $categoriaId, $id);
    mysqli_stmt_execute($stmt);
    $msg = "Produto atualizado com sucesso.";
} else {
    $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem, categoria_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdisi', $nome, $descricao, $preco, $estoque, $imagem, $categoriaId);
    mysqli_stmt_execute($stmt);
    $msg = "Produto cadastrado com sucesso.";
}

header("Location: produtos_listar.php?msg=" . urlencode($msg) . "&tipo=success");
exit;
