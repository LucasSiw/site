<?php
session_start();
include_once('header.php');
include_once(__DIR__ . '/php/conexao.php');

// Obtém o ID do produto a ser modificado
$idProduto = isset($_GET['id']) ? $_GET['id'] : 0;

// Verifica se o produto existe
$stmt = $wConexao->prepare("SELECT bdCodProduto, bdProdDescricao, bdProdValor, bdProdImagem FROM tbProduto WHERE bdCodProduto = ?");
$stmt->bind_param("i", $idProduto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning'>Produto não encontrado.</div>";
    exit;
}

$produto = $result->fetch_assoc();
?>

<div class="container">
    <h2>Modificar Produto</h2>
    <form action="atualizar_produto.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($produto['bdCodProduto']); ?>">
        
        <div class="form-group">
            <label for="descricao">Descrição do Produto:</label>
            <input type="text" class="form-control" id="descricao" name="descricao" value="<?= htmlspecialchars($produto['bdProdDescricao']); ?>" required>
        </div>

        <div class="form-group">
            <label for="valor">Valor do Produto:</label>
            <input type="number" step="0.01" class="form-control" id="valor" name="valor" value="<?= htmlspecialchars($produto['bdProdValor']); ?>" required>
        </div>

        <div class="form-group">
            <label for="imagem">Imagem do Produto:</label>
            <input type="file" class="form-control" id="imagem" name="imagem">
            <small>Imagem atual: <img src="<?= htmlspecialchars($produto['bdProdImagem']); ?>" alt="Imagem do Produto" style="width: 100px;"></small>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
    </form>
</div>

<link rel="stylesheet" href="/apetrecho/css/modificar.css">
<?php include_once('footer.php'); ?>
