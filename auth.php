<?php







if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION['usuario_id']);
$ehAdmin = $logado && ($_SESSION['usuario_tipo'] ?? '') === 'admin';

if (!$ehAdmin) {
    $destino = $caminho_login ?? 'login.php';
    header('Location: ' . $destino . '?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? ''));
    exit;
}
