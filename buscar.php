<?php

include 'conexao.php';

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$sql = "SELECT * FROM produtos WHERE nome LIKE ?";
$stmt = mysqli_prepare($conexao, $sql);

$termo = '%' . $busca . '%';
mysqli_stmt_bind_param($stmt, 's', $termo);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Busca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=1">
</head>
<body>

<div class="container mt-5">

    <h2>Resultado da busca por: <?php echo htmlspecialchars($busca, ENT_QUOTES, 'UTF-8'); ?></h2>

    <hr>

    <?php

    if(mysqli_num_rows($resultado) > 0)
    {
        while($produto = mysqli_fetch_assoc($resultado))
        {
            ?>

            <div class="card mb-3">
                <div class="card-body">

                    <h4><?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?></h4>

                    <p><?php echo nl2br(htmlspecialchars($produto['descricao'], ENT_QUOTES, 'UTF-8')); ?></p>

                    <strong>
                        R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    </strong>

                </div>
            </div>

            <?php
        }
    }
    else
    {
        echo "<p>Nenhum produto encontrado.</p>";
    }

    ?>

</div>

</body>
</html>
