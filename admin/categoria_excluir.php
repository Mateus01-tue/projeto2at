<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: categorias_listar.php?msg=" . urlencode("Categoria inválida.") . "&tipo=danger");
    exit;
}

$stmt = mysqli_prepare($conexao, "DELETE FROM categorias WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
$sucesso = mysqli_stmt_execute($stmt);

if ($sucesso) {
    $msg = "Categoria excluída com sucesso.";
    $tipo = "success";
} else {
    if (mysqli_errno($conexao) === 1451) {
        $msg = "Não foi possível excluir: existem produtos cadastrados nessa categoria.";
    } else {
        $msg = "Não foi possível excluir a categoria. Tente novamente.";
    }
    $tipo = "danger";
}

header("Location: categorias_listar.php?msg=" . urlencode($msg) . "&tipo=$tipo");
exit;
