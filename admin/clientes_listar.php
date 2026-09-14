<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

$resultado = mysqli_query($conexao, "SELECT * FROM clientes ORDER BY nome");

$mensagem = $_GET['msg'] ?? '';
$tipoMsg  = $_GET['tipo'] ?? 'success';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Clientes - Adalto CELL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=2">
</head>
<body>
<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Clientes</h2>
        <a href="cliente_form.php" class="btn btn-primary">+ Novo Cliente</a>
    </div>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo htmlspecialchars($tipoMsg, ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered align-middle bg-white">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <?php while ($c = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($c['telefone'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($c['email'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-end">
                        <a href="cliente_form.php?id=<?php echo (int)$c['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                        <a href="cliente_excluir.php?id=<?php echo (int)$c['id']; ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center text-muted">Nenhum cliente cadastrado.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="produtos_listar.php" class="btn btn-outline-secondary">← Gerenciar Produtos</a>
    <a href="../index.php" class="btn btn-outline-secondary">← Voltar ao site</a>
</div>
</body>
</html>
