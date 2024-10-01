<?php
session_start();
include_once('header.php');

// Obtém o ID do usuário logado
$userIdLogado = $_SESSION['bdCodUsuario'];

// Conexão com o banco de dados
include_once(__DIR__ . '/php/conexao.php');

// Consulta os produtos baseado na busca
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepara a consulta SQL para evitar SQL Injection
$stmt = $wConexao->prepare("SELECT bdCodProduto, bdProdDescricao, bdProdValor, bdProdImagem, bdCodUsuario FROM tbProduto WHERE bdProdDescricao LIKE ?");
$searchTerm = "%" . $query . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$produtos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
?>

<div class="container mt-4">
    <h2>Resultados da Pesquisa para: <?= htmlspecialchars($query); ?></h2>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="col">
                    <div class="product-card">
                        <a href="telaproduto.php?id=<?= urlencode($produto['bdCodProduto']); ?>">
                            <!-- Certifique-se de que o caminho da imagem está correto -->
                            <img src="<?= htmlspecialchars($produto['bdProdImagem']); ?>" alt="<?= htmlspecialchars($produto['bdProdDescricao']); ?>" class="img-fluid">
                            <div class="product-info">
                                <h5><?= htmlspecialchars($produto['bdProdDescricao']); ?></h5>
                                <div class="price">R$ <?= number_format($produto['bdProdValor'], 2, ',', '.'); ?></div>
                                <?php if ($produto['bdCodUsuario'] == $userIdLogado): ?>
                                    <!-- Se o usuário logado for o dono do produto, exibe o botão de modificar -->
                                    <a href="modificar_produto.php?id=<?= urlencode($produto['bdCodProduto']); ?>" class="btn btn-warning mt-2">Modificar</a>
                                <?php endif; ?>
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

<link rel="stylesheet" href="/apetrecho/css/pesquisa.css">
<?php include_once('footer.php'); ?>
