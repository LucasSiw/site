<?php
session_start();
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
    $removeId = (int)$_GET['remove'];
    $stmt = $wConexao->prepare("DELETE FROM tbCarrinho WHERE bdCodUsuario = ? AND bdCodProduto = ?");
    $stmt->bind_param("ii", $_SESSION['bdCodUsuario'], $removeId);
    $stmt->execute();
    $stmt->close();

    // Redireciona para a mesma página para atualizar o carrinho
    header("Location: carrinho.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="/apetrecho/css/carrinho.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/apetrecho/img/Apetrecho.ico" type="image/x-icon">
</head>

<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <p>Apetrecho</p>
            </div>
            <div class="nav-menu" id="navMenu">
                <ul>
                    <li><a href="telahome.php" class="link <?= $current_page == 'telahome.php' ? 'active' : '' ?>">Inicial</a></li>
                    <li><a href="carrinho.php" class="link <?= $current_page == 'carrinho.php' ? 'active' : '' ?>">Carrinho</a></li>
                    <li><a href="#" class="link <?= $current_page == 'servicos.php' ? 'active' : '' ?>">Serviços</a></li>
                    <li><a href="cadastrarproduto.php" class="link <?= $current_page == 'cadastrarproduto.php' ? 'active' : '' ?>">Cadastrar</a></li>
                </ul>
            </div>
            <div class="nav-button">
                <?php if (isset($_SESSION['usuario_nome'])): ?>
                    <span class="welcome-message">Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <?php else: ?>
                    <button class="btn white-btn" id="loginBtn" onclick="login()">Entrar</button>
                <?php endif; ?>
            </div>
        </nav>

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
                    <tbody id="carrinho-itens">
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
                                <td colspan="5">Seu carrinho está vazio.</td>
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

</body>

</html>
