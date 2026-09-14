<?php
$caminho_login = '../login.php';
include '../auth.php';

include '../conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$categoria = ['id' => 0, 'nome' => ''];

if ($id > 0) {
    $stmt = mysqli_prepare($conexao, "SELECT * FROM categorias WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $encontrada = mysqli_fetch_assoc($res);
    if ($encontrada) {
        $categoria = $encontrada;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id > 0 ? 'Editar' : 'Nova'; ?> Categoria - Adalto CELL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=2">
</head>
<body>
<div class="container my-5" style="max-width:500px;">

    <h2><?php echo $id > 0 ? 'Editar Categoria' : 'Nova Categoria'; ?></h2>
    <hr>

    <form action="categoria_salvar.php" method="POST" class="bg-white p-4 rounded shadow-sm">

        <input type="hidden" name="id" value="<?php echo (int)$categoria['id']; ?>">

        <div class="mb-3">
            <label class="form-label">Nome da categoria</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?php echo htmlspecialchars($categoria['nome'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="categorias_listar.php" class="btn btn-outline-secondary">Cancelar</a>

    </form>
</div>
</body>
</html>
