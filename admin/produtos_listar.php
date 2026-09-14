<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';


$stmt = mysqli_prepare($conexao, "CALL sp_buscar_produtos(NULL, 0, 100, 0)");
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$mensagem = $_GET['msg'] ?? '';
$tipoMsg  = $_GET['tipo'] ?? 'success';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - Adalto CELL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=1">
</head>
<body>
<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Produtos</h2>
        <a href="produto_form.php" class="btn btn-primary">+ Novo Produto</a>
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
                <th>Categoria</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <?php while ($p = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($p['categoria_nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                    <td>
                        <?php if ($p['estoque'] <= 5): ?>
                            <span class="badge bg-danger"><?php echo (int)$p['estoque']; ?> (crítico)</span>
                        <?php else: ?>
                            <?php echo (int)$p['estoque']; ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="produto_form.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                        <a href="produto_excluir.php?id=<?php echo (int)$p['id']; ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">Nenhum produto cadastrado.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

        <a href="../index.php" class="btn btn-outline-secondary">← Voltar ao site</a>
        <a href="../logout.php" class="btn btn-outline-danger">Sair</a>
</div>
</body>
</html>
