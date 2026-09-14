<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Adalto CELL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=1">
</head>
<body>

<div class="container my-5">

    <h2>Dashboard de Indicadores</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="text-muted mb-1">Faturamento total</h5>
                <h2 id="faturamento-total" style="color:#ffb400;">Carregando...</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="text-muted mb-1">Produto mais vendido</h5>
                <h2 id="produto-mais-vendido">Carregando...</h2>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="text-muted mb-3">
                    Estoque crítico
                    (<span id="total-estoque-critico">0</span> produtos)
                </h5>
                <ul id="lista-estoque-critico" class="list-group"></ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="text-muted mb-3">Categoria: Celulares</h5>
                <ul id="lista-celulares" class="list-group"></ul>
            </div>
        </div>
    </div>

    <a href="index.php" class="btn btn-outline-secondary mt-4">← Voltar ao site</a>
    <a href="logout.php" class="btn btn-outline-danger mt-4">Sair</a>
</div>

<script src="dashboard/dashboard.js"></script>
</body>
</html>
