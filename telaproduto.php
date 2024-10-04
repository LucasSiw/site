<?php
session_start();

include_once('header.php');

if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['usuario_nome'];
$userIdLogado = $_SESSION['bdCodUsuario']; // Obtém o ID do usuário logado

include_once(__DIR__ . '/php/conexao.php');
$produtos = [];
$result = $wConexao->query("SELECT bdCodProduto, bdProdDescricao, bdProdValor, bdProdImagem, bdCodUsuario FROM tbProduto"); // Incluindo o bdCodUsuario na consulta

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    $productQuery = $wConexao->query("SELECT bdProdValor FROM tbProduto WHERE bdCodProduto = $productId");
    $productData = $productQuery->fetch_assoc();
    $productPrice = $productData['bdProdValor'];

    $stmt = $wConexao->prepare("INSERT INTO tbCarrinho (bdCodUsuario, bdCodProduto, bdCarQtd, bdCarPreco) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $_SESSION['bdCodUsuario'], $productId, $quantity, $productPrice);
    $stmt->execute();
    $stmt->close();
}

// Função para excluir o produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productIdToDelete = (int)$_POST['product_id'];
    
    $deleteStmt = $wConexao->prepare("DELETE FROM tbProduto WHERE bdCodProduto = ? AND bdCodUsuario = ?");
    $deleteStmt->bind_param("ii", $productIdToDelete, $userIdLogado);
    $deleteStmt->execute();
    $deleteStmt->close();
    header("Location: " . $_SERVER['PHP_SELF']); // Redireciona para a mesma página para atualizar a lista de produtos
    exit();
}
?>

<style>
.custom-margin-top {
    margin-top: 10rem; 
}

.btn {
  background: #ffb742;
  color: #fff;
  border: none;  
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
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($produto['bdCodProduto']); ?>">
                                <label for="quantity">Quantidade:</label>
                                <input type="number" name="quantity" value="1" min="1" max="10" class="form-control mb-2" required>
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Adicionar ao Carrinho</button>
                            </form>
                            
                            <?php if ($produto['bdCodUsuario'] == $userIdLogado): ?>
                                <a href="modificarproduto.php?id=<?php echo urlencode($produto['bdCodProduto']); ?>" class="btn btn-warning mt-2">Modificar</a>

                                <form method="POST" class="mt-2">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($produto['bdCodProduto']); ?>">
                                    <button type="submit" name="delete_product" class="btn btn-danger">Excluir</button>
                                </form>
                            <?php endif; ?>
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
