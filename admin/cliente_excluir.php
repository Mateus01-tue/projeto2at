<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: clientes_listar.php?msg=" . urlencode("Cliente inválido.") . "&tipo=danger");
    exit;
}

$stmt = mysqli_prepare($conexao, "DELETE FROM clientes WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
$sucesso = mysqli_stmt_execute($stmt);

if ($sucesso) {
    $msg = "Cliente excluído com sucesso.";
    $tipo = "success";
} else {
    if (mysqli_errno($conexao) === 1451) {
        $msg = "Não foi possível excluir: este cliente já tem pedidos registrados.";
    } else {
        $msg = "Não foi possível excluir o cliente. Tente novamente.";
    }
    $tipo = "danger";
}

header("Location: clientes_listar.php?msg=" . urlencode($msg) . "&tipo=$tipo");
exit;
