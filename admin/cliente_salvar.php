<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: clientes_listar.php");
    exit;
}

$id       = (int)($_POST['id'] ?? 0);
$nome     = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email    = trim($_POST['email'] ?? '');

if ($nome === '') {
    header("Location: cliente_form.php?id=$id&erro=1");
    exit;
}

if ($id > 0) {
    $stmt = mysqli_prepare($conexao, "UPDATE clientes SET nome = ?, telefone = ?, email = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'sssi', $nome, $telefone, $email, $id);
    mysqli_stmt_execute($stmt);
    $msg = "Cliente atualizado com sucesso.";
} else {
    $stmt = mysqli_prepare($conexao, "INSERT INTO clientes (nome, telefone, email) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $nome, $telefone, $email);
    mysqli_stmt_execute($stmt);
    $msg = "Cliente cadastrado com sucesso.";
}

header("Location: clientes_listar.php?msg=" . urlencode($msg) . "&tipo=success");
exit;
