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
<style>
.custom-margin-top {
    margin-top: 10rem; 
}
</style>
<div class="container custom-margin-top">
    <div class="row">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?php echo htmlspecialchars($produto['bdProdImagem']); ?>" class="card-img-top" alt="Imagem do Produto" style="max-height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($produto['bdProdDescricao']); ?></h5>
                            <p class="card-text">Preço: R$ <?php echo number_format($produto['bdProdValor'], 2, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>
</div>
<?php include_once('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
