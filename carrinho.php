<?php
session_start();
include_once('header.php'); 
include_once(__DIR__ . '/php/conexao.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php");
    exit();
}

// Inicializa o carrinho
$carrinho = [];
$total = 0;

// Obtém os itens do carrinho do banco de dados
if (isset($_SESSION['bdCodUsuario'])) {
    $usuarioId = $_SESSION['bdCodUsuario'];
    $result = $wConexao->query("SELECT p.bdCodProduto, p.bdProdDescricao, p.bdProdValor, c.bdCarQtd FROM tbCarrinho c JOIN tbProduto p ON c.bdCodProduto = p.bdCodProduto WHERE c.bdCodUsuario = $usuarioId");

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $carrinho[] = $row;
            // Calcula o total
            $total += $row['bdProdValor'] * $row['bdCarQtd'];
        }
    }
}

// Função para remover produto do carrinho
if (isset($_GET['remove'])) {
  $removeId = (int)$_GET['remove']; // Captura o ID do produto a ser removido
  $usuarioId = $_SESSION['bdCodUsuario']; // Captura o ID do usuário atual

  // Prepara a consulta para remover apenas o produto específico do carrinho do usuário
  $stmt = $wConexao->prepare("DELETE FROM tbCarrinho WHERE bdCodUsuario = ? AND bdCodProduto = ?");
  $stmt->bind_param("ii", $usuarioId, $removeId); // Bind dos parâmetros
  $stmt->execute();
  
  // Verifica se a remoção foi bem-sucedida
  if ($stmt->affected_rows > 0) {
      echo "Produto removido com sucesso.";
  } else {
      echo "Erro ao remover o produto ou o produto não existe no carrinho.";
  }

  $stmt->close();

  // Redireciona para a mesma página para atualizar o carrinho
  header("Location: carrinho.php");
  exit();
}


?>
<style>
    
.content {
    display: flex;
    margin-top: 100px; 
}

table {
  width: 100%;
  border-collapse: collapse;
}

table thead tr {
  border-bottom: 3px solid #eee;
}

table thead tr th {
  text-align: left;
  padding-bottom: 10px;
  text-transform: uppercase;
  color: #666;
}

table tbody tr {
  border-bottom: 3px solid #eee;
}

table tbody tr:last-child {
  border: 0;
}

table tbody tr td {
  padding: 30px 0;
}

.produtos {
    flex: 2;
    padding-right: 20px;
}

.product {
  display: flex;
  align-items: center;
}

.product img {
  border-radius: 6px;
  width: 80px; 
  height: auto;
}

.product .info {
  margin-left: 20px;
}

.product .info .name {
  font-size: 20px;
  margin-bottom: 10px;
}

.product .info .category {
  color: #666;
}

.qty {
  background: #eee;
  display: inline-flex;
  justify-content: space-around;
  align-items: center;
  min-width: 60px;
  border-radius: 20px;
  overflow: hidden;
  height: 30px;
}

.qty span {
  margin: 0 10px;
}

.qty button {
  background: transparent;
  border: 0;
  padding: 0 10px;
  font-size: 20px;
  height: 100%;
}

.qty button:hover {
  background: #ddd;
}

.remove {
  background: #eee;
  border: 0;
  width: 30px;
  height: 30px;
  border-radius: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
}

.remove:hover {
  background: #ddd;
}

.resumo {
    flex: 1; 
    background: #fff;
    border-radius: 6px;
    padding: 15px;
}

.resumo .box {
  background: #f9f9f9;
  border-radius: 6px;
  padding: 15px;
}

.resumo header {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 20px;
}

.resumo .info {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.resumo footer {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
  font-weight: 600;
}

.resumo button {
  background: #ffb742;
  color: #fff;
  border: none;
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  cursor: pointer;
}

.resumo button:hover {
  background: #fb8c00;
}

.page-title {
    text-align: center;
    font-size: 28px;
    margin: 100px 0 50px;
    color: #fff;
}

.resumo .info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.resumo .info div {
    display: flex;
    justify-content: space-between;
    width: 100%;
    padding: 10px 5px; 
}

.resumo footer {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    font-weight: 600;
}

.texto {
  color: #fff;
}

.carrinho-itens{
  color: #fff;
}

</style>

        <div class="page-title">Seu Carrinho</div>
        <div class="content">
            <section class="produtos">
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>-</th>
                        </tr>
                    </thead>
                    <tbody id="carrinho-itens" class="carrinho-itens">
                        <?php if (!empty($carrinho)): ?>
                            <?php foreach ($carrinho as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['bdProdDescricao']); ?></td>
                                    <td>R$ <?= number_format($item['bdProdValor'], 2, ',', '.'); ?></td>
                                    <td><?= htmlspecialchars($item['bdCarQtd']); ?></td>
                                    <td>R$ <?= number_format($item['bdProdValor'] * $item['bdCarQtd'], 2, ',', '.'); ?></td>
                                    <td>
                                        <a href="?remove=<?= htmlspecialchars($item['bdCodProduto']); ?>" class="btn btn-danger">Remover</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="texto">Seu carrinho está vazio.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
            <aside class="resumo">
                <div class="box">
                    <header>Resumo da compra</header>
                    <div class="info">
                        <div><span>Sub-total</span><span id="sub-total">R$ <?= number_format($total, 2, ',', '.'); ?></span></div>
                        <div><span>Frete</span><span>Gratuito</span></div>
                        <div>
                            <button>
                                Adicionar cupom de desconto
                                <i class="bx bx-right-arrow-alt"></i>
                            </button>
                        </div>
                    </div>
                    <footer>
                        <span>Total</span>
                        <span id="total">R$ <?= number_format($total, 2, ',', '.'); ?></span>
                    </footer>
                </div>
                <button>Finalizar Compra</button>
            </aside>
        </div>
    </div>

<?php include_once('footer.php'); ?>
