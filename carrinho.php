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
                    <li><a href="telahome.php" class="link active">Ínicial</a></li>
                    <li><a href="carrinho.php" class="link">Carrinho</a></li>
                    <li><a href="#" class="link">Serviços</a></li>
                    <li><a href="cadastrarproduto.php" class="link">Cadastrar</a></li>
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
                    </tbody>
                </table>
            </section>
            <aside class="resumo">
                <div class="box">
                    <header>Resumo da compra</header>
                    <div class="info">
                        <div><span>Sub-total</span><span id="sub-total">R$ 0</span></div>
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
                        <span id="total">R$ 0</span>
                    </footer>
                </div>
                <button onclick="finalizarCompra()">Finalizar Compra</button>
            </aside>
        </div>
    </div>

    <script src="apetrecho/js/carrinho.js"></script>
</body>
</html>
