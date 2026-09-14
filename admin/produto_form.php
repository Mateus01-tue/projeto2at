<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produto = [
    'id' => 0, 'nome' => '', 'descricao' => '', 'preco' => '',
    'estoque' => 10, 'imagem' => '', 'categoria_id' => ''
];

if ($id > 0) {
    $stmt = mysqli_prepare($conexao, "SELECT * FROM produtos WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $encontrado = mysqli_fetch_assoc($res);
    if ($encontrado) {
        $produto = $encontrado;
    }
}

$categorias = mysqli_query($conexao, "SELECT id, nome FROM categorias ORDER BY nome");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id > 0 ? 'Editar' : 'Novo'; ?> Produto - Adalto CELL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=1">
</head>
<body>
<div class="container my-5" style="max-width:600px;">

    <h2><?php echo $id > 0 ? 'Editar Produto' : 'Novo Produto'; ?></h2>
    <hr>

    <form action="produto_salvar.php" method="POST" class="bg-white p-4 rounded shadow-sm">

        <input type="hidden" name="id" value="<?php echo (int)$produto['id']; ?>">

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="3"><?php echo htmlspecialchars($produto['descricao'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label">Preço (R$)</label>
                <input type="number" step="0.01" min="0.01" name="preco" class="form-control" required
                       value="<?php echo htmlspecialchars((string)$produto['preco'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Estoque</label>
                <input type="number" min="0" name="estoque" class="form-control" required
                       value="<?php echo htmlspecialchars((string)$produto['estoque'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagem (nome do arquivo em /imagens)</label>
            <input type="text" name="imagem" class="form-control"
                   value="<?php echo htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Selecione...</option>
                <?php while ($c = mysqli_fetch_assoc($categorias)): ?>
                    <option value="<?php echo (int)$c['id']; ?>"
                        <?php echo ((int)$produto['categoria_id'] === (int)$c['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="produtos_listar.php" class="btn btn-outline-secondary">Cancelar</a>

    </form>
</div>
</body>
</html>
