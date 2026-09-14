<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="css/style.css?v=2">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuarioLogado = isset($_SESSION['usuario_id']);
$usuarioNome   = $_SESSION['usuario_nome'] ?? '';
$ehAdmin       = $usuarioLogado && ($_SESSION['usuario_tipo'] ?? '') === 'admin';
?>

<nav class="navbar navbar-expand-lg bg-white border-bottom">

    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            <img src="imagens/logo.png" alt="Logo AdaltoCell" class="logo-header">
            ADALTO CELL
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Início</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="sobre.php">Sobre</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contato.php">Contato</a>
                </li>

                <?php if ($ehAdmin): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Painel Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="admin/produtos_listar.php">Gerenciar Produtos</a></li>
                            <li><a class="dropdown-item" href="admin/categorias_listar.php">Gerenciar Categorias</a></li>
                            <li><a class="dropdown-item" href="admin/clientes_listar.php">Gerenciar Clientes</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if ($usuarioLogado): ?>
                    <li class="nav-item">
                        <span class="nav-link disabled">Olá, <?php echo htmlspecialchars($usuarioNome, ENT_QUOTES, 'UTF-8'); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro.php">Criar Conta</a>
                    </li>
                <?php endif; ?>
            </ul>

        <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') { ?>
                
                <form action="index.php" method="GET" class="d-flex ms-lg-3">
                    <input class="form-control me-2"
                           type="search"
                           name="busca"
                           placeholder="Pesquisar">
                    <button type="submit" class="btn btn-dark">
                        Buscar
                    </button>
                </form>

            <?php } ?>
        </div>

    </div>

</nav>