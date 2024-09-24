<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/apetrecho/css/header.css">
    <link rel="shortcut icon" href="/apetrecho/img/Apetrecho.ico" type="image/x-icon">
    <title>Apetrecho</title>
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
                    <li><a href="#" class="link">Cadastrar</a></li>
                </ul>
            </div>
            <div class="nav-button">
                <?php if (isset($_SESSION['usuario_nome'])): ?>
                    <span class="welcome-message">Bem-vindo, <?=htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <?php else: ?>
                    <button class="btn white-btn" id="loginBtn" onclick="login()">Entrar</button>
                <?php endif; ?>
            </div>
        </nav>
