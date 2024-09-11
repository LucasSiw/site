<?php
// Inclua o arquivo de conexão
include_once(__DIR__ . '/php/conexao.php'); // Use '/' em vez de '\' para caminhos em PHP

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $wNome     = htmlspecialchars($_POST['edNome']);
        $wLogin    = htmlspecialchars($_POST['edEmail']);
        $wSenha    = password_hash($_POST['edSenha'], PASSWORD_BCRYPT);
        $wTelefone = htmlspecialchars($_POST['edTelefone']);
        $wCPF      = htmlspecialchars($_POST['edCPF']);

        // Use consultas preparadas para evitar SQL Injection
        $stmt = $wConexao->prepare("INSERT INTO tbUsuario (bdAluNome, bdAluTelefone, bdAluCPFCNPJ, bdAluEmail, bdAluSenha) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sssss', $wNome, $wTelefone, $wCPF, $wLogin, $wSenha);
            if ($stmt->execute()) {
                echo '<p style="color: white;">Novo registro criado com sucesso</p>';
            } else {
                echo '<p style="color: red;">Erro: ' . $stmt->error . '</p>';
            }
            $stmt->close();
        } else {
            echo '<p style="color: red;">Erro na preparação da consulta</p>';
        }
    } elseif (isset($_POST['login'])) {
        $wLogin = htmlspecialchars($_POST['loginEmail']);
        $wSenha = $_POST['loginSenha'];

        // Use consultas preparadas para evitar SQL Injection
        $stmt = $wConexao->prepare("SELECT bdAluSenha FROM tbUsuario WHERE bdAluEmail = ?");
        if ($stmt) {
            $stmt->bind_param('s', $wLogin);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashedPassword);
                $stmt->fetch();
                if (password_verify($wSenha, $hashedPassword)) {
                    // Se o login for bem-sucedido, redirecione para a página inicial
                    header("Location: telahome.html"); // Altere para o caminho da sua página inicial
                    exit();
                } else {
                    echo '<p style="color: red;">Senha incorreta</p>';
                }
            } else {
                echo '<p style="color: red;">Email não encontrado</p>';
            }
            $stmt->close();
        } else {
            echo '<p style="color: red;">Erro na preparação da consulta</p>';
        }
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
                        <input type="text" placeholder="Nome" name="edNome" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Telefone" name="edTelefone" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-id-card"></i>
                        <input type="text" placeholder="CPF" name="edCPF" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Email" name="edEmail" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Senha" name="edSenha" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Repita senha" name="edSenhaRep" />
                    </div>
                    <input type="submit" class="btn" value="Registre-se" />
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