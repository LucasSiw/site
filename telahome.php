<?php
include_once('header.php'); 
session_start();

if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/apetrecho/css/telahome.css">
    <link rel="shortcut icon" href="/apetrecho/img/Apetrecho.ico" type="image/x-icon">
    <title>Apetrecho</title>
</head>

<body>
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/apetrecho/img/Fundo1.png" class="d-block w-100" alt="Fundo 1">
                </div>
                <div class="carousel-item">
                    <img src="/apetrecho/img/Fundo2.png" class="d-block w-100" alt="Fundo 2">
                </div>
                <div class="carousel-item">
                    <img src="/apetrecho/img/Fundo3.png" class="d-block w-100" alt="Fundo 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <section class="featured-products">
            <h2>Produtos Destacados</h2>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <div class="col">
                    <div class="product-card">
                        <a href="telaproduto.html?id=parafusadeira">
                            <img src="/apetrecho/img/Parafusadeira.png" alt="Parafusadeira">
                            <div class="product-info">
                                <h5>Parafusadeira</h5>
                                <p>Alta potência e durabilidade.</p>
                                <div class="price">R$ 299,99</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="product-card">
                        <a href="telaproduto.html?id=luvas">
                            <img src="/apetrecho/img/Luva.png" alt="Luvas de Segurança">
                            <div class="product-info">
                                <h5>Luvas de Segurança</h5>
                                <p>Proteja suas mãos em qualquer situação.</p>
                                <div class="price">R$ 19,99</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="product-card">
                        <a href="telaproduto.html?id=broca">
                            <img src="/apetrecho/img/Broca.png" alt="Broca para Concreto">
                            <div class="product-info">
                                <h5>Broca para Concreto</h5>
                                <p>Perfuração precisa e eficiente.</p>
                                <div class="price">R$ 15,99</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php include_once('footer.php'); ?>

    <script src="/apetrecho/js/telahome.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>