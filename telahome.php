<?php
session_start();

include_once('header.php');

if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['usuario_nome'];

include_once(__DIR__ . '/php/conexao.php');
$produtos = [];
$result = $wConexao->query("SELECT bdProdDescricao, bdProdValor, bdProdImagem FROM tbProduto");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
?>

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
        <?php foreach ($produtos as $produto): ?>
            <div class="col">
                <div class="product-card">
                    <a href="telaproduto.html?id=<?= urlencode($produto['bdProdDescricao']); ?>">
                        <img src="<?= htmlspecialchars($produto['bdProdImagem']); ?>" alt="<?= htmlspecialchars($produto['bdProdDescricao']); ?>">
                        <div class="product-info">
                            <h5><?= htmlspecialchars($produto['bdProdDescricao']); ?></h5>
                            <div class="price">R$ <?= number_format($produto['bdProdValor'], 2, ',', '.'); ?></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
</div>

<link rel="stylesheet" href="/apetrecho/css/telahome.css">
<?php include_once('footer.php'); ?>

<script src="/apetrecho/js/telahome.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>