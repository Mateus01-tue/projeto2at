<?php

include 'conexao.php';



$mapaCategorias = [];
$resCat = mysqli_query($conexao, "SELECT id, nome FROM categorias");
while ($cat = mysqli_fetch_assoc($resCat)) {
    $mapaCategorias[(int)$cat['id']] = $cat['nome'];
}

function obterNomeCategoria($id) {
    global $mapaCategorias;

    if (array_key_exists((int)$id, $mapaCategorias)) {
        return $mapaCategorias[(int)$id];
    } else {
        return "Outros Produtos";
    }
}


$busca = isset($_GET['busca']) ? trim($_GET['busca']) : "";
$categoria_filtrada = isset($_GET['categoria']) ? $_GET['categoria'] : "";

if ($categoria_filtrada !== "") {

    $cat_id = (int)$categoria_filtrada;
    $sql = "SELECT * FROM produtos WHERE categoria_id = ? ORDER BY categoria_id ASC, id ASC";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $cat_id);

} elseif ($busca !== "") {

    
    
    $termoBusca = mb_strtolower($busca);
    $categoriaPorTexto = null;

    foreach ($mapaCategorias as $idCat => $nomeCat) {
        if (mb_strpos($termoBusca, mb_strtolower($nomeCat)) !== false) {
            $categoriaPorTexto = $idCat;
            break;
        }
    }

    if ($categoriaPorTexto !== null) {
        $sql = "SELECT * FROM produtos WHERE nome LIKE ? OR categoria_id = ? ORDER BY categoria_id ASC, id ASC";
        $stmt = mysqli_prepare($conexao, $sql);
        $like = '%' . $busca . '%';
        mysqli_stmt_bind_param($stmt, 'si', $like, $categoriaPorTexto);
    } else {
        $sql = "SELECT * FROM produtos WHERE nome LIKE ? ORDER BY categoria_id ASC, id ASC";
        $stmt = mysqli_prepare($conexao, $sql);
        $like = '%' . $busca . '%';
        mysqli_stmt_bind_param($stmt, 's', $like);
    }

} else {
    $sql = "SELECT * FROM produtos ORDER BY categoria_id ASC, id ASC";
    $stmt = mysqli_prepare($conexao, $sql);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($categoria_filtrada !== "") {
    echo "<h3 class='text-center mt-4'>Categoria: " . htmlspecialchars(obterNomeCategoria((int)$categoria_filtrada), ENT_QUOTES, 'UTF-8') . "</h3>";
} elseif ($busca !== "") {
    echo "<h3 class='text-center mt-4'>Resultado da busca: " . htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') . "</h3>";
}
?>

<section class="cardss">

    <?php 
    if(mysqli_num_rows($resultado) > 0) { 
        
        $categoria_atual = "";

        while($produto = mysqli_fetch_assoc($resultado)) { 
            

            $nome_categoria = obterNomeCategoria($produto['categoria_id']);

            if ($categoria_atual != $nome_categoria) {
                
                if ($categoria_atual != "") {
                    echo '</div></div>'; 
                }

                $categoria_atual = $nome_categoria;
                ?>
                <div class="container mt-5">
                    <h2 class="border-bottom pb-2 mb-4 fw-bold text-dark"><?php echo htmlspecialchars($categoria_atual, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <div class="row">
                <?php
            }
            ?>

            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="imagens/<?php echo htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8'); ?>"
                         class="produto-img"
                         alt="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="card-body text-center">
                        <h5>
                            <?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>
                        </h5>

                        <p>
                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </p>

                        <a href="produto.php?id=<?php echo (int)$produto['id']; ?>"
                           class="btn btn-dark">
                            Ver Produto
                        </a>
                    </div>
                </div>
            </div>

        <?php }  
        
        echo '</div></div>';

    } else { ?>

        <div class="container text-center my-5">
            <div class="p-5 bg-white rounded shadow-sm border mx-auto" style="max-width: 600px;">
                <h3 class="text-danger fw-bold">Nenhum produto encontrado 🔍</h3>
                <p class="text-muted mt-3">Não encontramos resultados para o termo correspondente.</p>
                <a href="index.php" class="btn btn-dark btn-sm mt-3">Ver Todos os Produtos</a>
            </div>
        </div>

    <?php } ?>

</section>