<?php
    // Inclua o arquivo de conexão
    include_once(__DIR__ . '/conexao.php'); // Ajuste o caminho se necessário

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['register'])) {
            $wNome     = htmlspecialchars($_POST['edNome']);
            $wLogin    = htmlspecialchars($_POST['edEmail']);
            $wSenha    = password_hash($_POST['edSenha'], PASSWORD_BCRYPT);
            $wTelefone = htmlspecialchars($_POST['edTelefone']);
            $wCPF      = htmlspecialchars($_POST['edCPF']);

            // Use consultas preparadas para evitar SQL Injection
            $stmt = $wConexao->prepare("INSERT INTO tbUsuario (bdAluNome, bdAluTelefone, bdAluCPFCNPJ, bdAluEmail, bdAluSenha) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $wNome, $wTelefone, $wCPF, $wLogin, $wSenha);
            $stmt->close();

        } elseif (isset($_POST['login'])) {
            $wLogin = htmlspecialchars($_POST['loginEmail']);
            $wSenha = $_POST['loginSenha'];

            // Use consultas preparadas para evitar SQL Injection
            $stmt = $wConexao->prepare("SELECT bdAluSenha FROM tbUsuario WHERE bdAluEmail = ?");
            $stmt->bind_param('s', $wLogin);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashedPassword);
            $stmt->close();
        }
    }

    $wConexao->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/apetrecho/css/login.css" />
    <title>Apetrecho</title>
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="login.php" method="POST" class="sign-in-form">
                    <h2 class="title">Login</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="loginEmail" placeholder="Login" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="loginSenha" placeholder="Senha" required />
                    </div>
                    <input type="submit" name="login" value="Login" class="btn solid" />
                </form>
                <form action="login.php" method="POST" class="sign-up-form">
                    <h2 class="title">Registre-se</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="edNome" placeholder="Nome" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="edTelefone" placeholder="Telefone" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="edCPF" placeholder="CPF" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="edEmail" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="edSenha" placeholder="Senha" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="edSenhaRepita" placeholder="Repita senha" required />
                    </div>
                    <input type="submit" name="register" class="btn" value="Registre-se" />
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Novo aqui?</h3>
                    <p>Insira seus dados pessoais para utilizar todos os recursos do site</p>
                    <button class="btn transparent" id="sign-up-btn">Registre-se</button>
                    <button class="btn transparent" id="inicio">Ínicio</button>
                </div>
                <img src="/apetrecho/img/log.svg" class="image" alt="" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Bem Vindo</h3>
                    <p>Registre-se com seus dados pessoais para utilizar todos os recursos do site</p>
                    <button class="btn transparent" id="sign-in-btn">Login</button>
                    <button class="btn transparent" id="inicio">Ínício</button>
                </div>
                <img src="/apetrecho/img/register.svg" class="image" alt="" />
            </div>
        </div>
    </div>

    <script src="/apetrecho/js/login.js"></script>
</body>
</html>
