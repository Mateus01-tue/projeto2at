<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adalto CELL</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="css/contato.css?v=1">


</head>
<body>

    <?php include 'includes/header.php'; ?>

<section class="contato-container">

    <div class="container">

        <h1 class="titulo">Entre em Contato</h1>

        <p class="subtitulo">
            Estamos prontos para ajudar você a encontrar o produto ideal.
        </p>

        <div class="row">

            <div class="col-md-8 mx-auto">

                <div class="info-box">

                    <h3>Informações da Loja</h3>

                    <p>
                        📍 Rua Doutor Miguel Vieira Ferreira<br>
                        Centro - Luiziana/PR
                    </p>

                    <p>
                        📞 (44) 99749-3842
                    </p>

                    <p>
                        🕒 Segunda a Sexta: 08h às 18h<br>
                        🕒 Sábado: 08h às 12h
                    </p>

                    <a href="https://wa.me/5544997493842"
                       target="_blank"
                       class="btn btn-success">
                        Falar no WhatsApp
                    </a>

                </div>

            </div>

        </div>

        <div class="mapa">

            <h3>Localização</h3>

            <iframe
                src="https://maps.google.com/maps?q=Luiziana%20PR&t=&z=13&ie=UTF8&iwloc=&output=embed"
                width="100%"
                height="350"
                style="border:0;"
                loading="lazy">
            </iframe>

        </div>

    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<footer class="bg-dark text-white text-center p-4">

    <p>
        © 2026 Adalto CELL - Todos os direitos reservados
    </p>

</footer>

</body>
</html>