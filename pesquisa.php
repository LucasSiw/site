<?php
session_start();
include_once('header.php');

if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['usuario_nome'];

// Conexão com o banco de dados
include_once(__DIR__ . '/php/conexao.php');

$query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepara a consulta SQL para evitar SQL Injection
$stmt = $wConexao->prepare("SELECT bdProdDescricao, bdProdValor, bdProdImagem FROM tbProduto WHERE bdProdDescricao LIKE ?");
$searchTerm = "%" . $query . "%"; // Adiciona os caracteres de wildcard
$stmt->bind_param("s", $searchTerm); // "s" indica que estamos passando uma string
$stmt->execute();
$result = $stmt->get_result();

$produtos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
?>

<!-- Barra de pesquisa -->
<div class="container mt-4">
    <form action="seu_script_de_pesquisa.php" method="GET" class="d-flex mb-4">
        <input type="text" name="query" class="form-control me-2" placeholder="Pesquisar produtos" aria-label="Pesquisar">
        <button type="submit" class="btn btn-outline-success">Pesquisar</button>
    </form>
</div>

<!-- Exibir resultados da pesquisa -->
<div class="container mt-4">
    <h2 class="welcome-message">Resultados da Pesquisa para: <?= htmlspecialchars($query); ?></h2>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php if (!empty($produtos)): ?>
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
        <?php else: ?>
            <div class="col">
                <div class="alert alert-warning" role="alert">
                    Nenhum produto encontrado.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once('footer.php'); ?>
