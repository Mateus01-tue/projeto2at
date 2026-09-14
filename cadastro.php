<?php
session_start();
include 'conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || strlen($senha) < 6) {
        $erro = 'Preencha o nome e uma senha com pelo menos 6 caracteres.';
    } else {
        
        $stmt = mysqli_prepare($conexao, "SELECT id FROM usuarios WHERE nome = ?");
        mysqli_stmt_bind_param($stmt, 's', $nome);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($res) > 0) {
            $erro = 'Já existe uma conta com esse nome. Escolha outro.';
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            
            
            $stmt = mysqli_prepare($conexao, "INSERT INTO usuarios (nome, senha, tipo) VALUES (?, ?, 'cliente')");
            mysqli_stmt_bind_param($stmt, 'ss', $nome, $senhaHash);

            if (mysqli_stmt_execute($stmt)) {
                $sucesso = 'Conta criada com sucesso! Você já pode fazer login.';
            } else {
                $erro = 'Não foi possível criar a conta. Tente novamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Adalto CELL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=2">
</head>
<body>

<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="card p-4" style="max-width:420px; width:100%;">

        <h3 class="mb-4 text-center">Criar Conta</h3>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?></div>
            <a href="login.php" class="btn btn-primary w-100">Ir para o login</a>
        <?php else: ?>

            <form method="POST" action="cadastro.php">

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required
                           value="<?php echo htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Senha (mín. 6 caracteres)</label>
                    <input type="password" name="senha" class="form-control" minlength="6" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Criar conta</button>

            </form>

            <p class="text-center mt-3 mb-0">
                Já tem conta? <a href="login.php">Entrar</a>
            </p>

        <?php endif; ?>

        <a href="index.php" class="d-block text-center mt-3 text-muted">← Voltar ao site</a>

    </div>
</div>

</body>
</html>
