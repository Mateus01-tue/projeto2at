<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adalto CELL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=1">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>

<?php 

if (!isset($_GET['busca']) && !isset($_GET['categoria']) && !isset($_GET['todos'])) { 
?>

    <section class="banner">
        <img src="imagens/banner2.png" alt="Banner do Adalto Cell" title="Adalto Cell banner">
    </section>

<?php 
} 
?>

<div class="container text-center mt-4">
    <div class="btn-group flex-wrap" role="group" aria-label="Filtro de Categorias">
        <a href="index.php?todos=1" class="btn btn-outline-dark">Todos os Produtos</a>
        <?php
        include_once 'conexao.php';
        $resCategorias = mysqli_query($conexao, "SELECT id, nome FROM categorias ORDER BY nome");
        while ($cat = mysqli_fetch_assoc($resCategorias)) {
            $idCat = (int)$cat['id'];
            $nomeCat = htmlspecialchars($cat['nome'], ENT_QUOTES, 'UTF-8');
            echo "<a href=\"index.php?categoria=$idCat\" class=\"btn btn-outline-dark\">$nomeCat</a>";
        }
        ?>
    </div>
</div>

<?php include 'includes/cards.php'; ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<footer class="bg-dark text-white text-center p-4">
    <p>© 2026 Adalto CELL - Todos os direitos reservados</p>
</footer>

</body>
</html>