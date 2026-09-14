<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: produtos_listar.php?msg=" . urlencode("Produto inválido.") . "&tipo=danger");
    exit;
}

$stmt = mysqli_prepare($conexao, "DELETE FROM produtos WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
$sucesso = mysqli_stmt_execute($stmt);

if ($sucesso) {
    $msg = "Produto excluído com sucesso.";
    $tipo = "success";
} else {
    
    if (mysqli_errno($conexao) === 1451) {
        $msg = "Não foi possível excluir: este produto está vinculado a um ou mais pedidos existentes.";
    } else {
        $msg = "Não foi possível excluir o produto. Tente novamente.";
    }
    $tipo = "danger";
}

header("Location: produtos_listar.php?msg=" . urlencode($msg) . "&tipo=$tipo");
exit;
