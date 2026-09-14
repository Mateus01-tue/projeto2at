<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: categorias_listar.php");
    exit;
}

$id   = (int)($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');

if ($nome === '') {
    header("Location: categoria_form.php?id=$id&erro=1");
    exit;
}

if ($id > 0) {
    $stmt = mysqli_prepare($conexao, "UPDATE categorias SET nome = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $nome, $id);
    mysqli_stmt_execute($stmt);
    $msg = "Categoria atualizada com sucesso.";
} else {
    $stmt = mysqli_prepare($conexao, "INSERT INTO categorias (nome) VALUES (?)");
    mysqli_stmt_bind_param($stmt, 's', $nome);
    mysqli_stmt_execute($stmt);
    $msg = "Categoria cadastrada com sucesso.";
}

header("Location: categorias_listar.php?msg=" . urlencode($msg) . "&tipo=success");
exit;
