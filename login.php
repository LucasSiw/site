<?php
include_once(__DIR__ . '/php/conexao.php');
session_start();

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $wLogin = htmlspecialchars($_POST['loginEmail']);
        $wSenha = $_POST['loginSenha'];

        $stmt = $wConexao->prepare("SELECT bdAluNome, bdAluSenha, bdCodUsuario FROM tbUsuario WHERE bdAluEmail = ?");
        if ($stmt) {
            $stmt->bind_param('s', $wLogin);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($wNome, $hashedPassword, $bdCodUsuario);
                $stmt->fetch();

                if (password_verify($wSenha, $hashedPassword)) {
                    // Armazena o nome e o código do usuário na sessão
                    $_SESSION['usuario_nome'] = $wNome;
                    $_SESSION['bdAluEmail'] = $wLogin;
                    $_SESSION['bdCodUsuario'] = $bdCodUsuario; // Adiciona o código do usuário

                    header("Location: telahome.php");
                    exit();
                } else {
                    $errorMessage = 'Senha incorreta';
                }
            } else {
                $errorMessage = 'Email não encontrado';
            }
            $stmt->close();
        } else {
            $errorMessage = 'Erro na preparação da consulta';
        }
    }

    // Registro de novo usuário
    if (isset($_POST['edNome'])) {
        $nome = htmlspecialchars($_POST['edNome']);
        $telefone = htmlspecialchars($_POST['edTelefone']);
        $cpf = htmlspecialchars($_POST['edCPF']);
        $email = htmlspecialchars($_POST['edEmail']);
        $senha = $_POST['edSenha'];
        $senhaRep = $_POST['edSenhaRep'];

        // Verifica se as senhas são iguais
        if ($senha !== $senhaRep) {
            $errorMessage = 'As senhas não coincidem';
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // Gerar um token de verificação único
            $token = bin2hex(random_bytes(50));

            $stmt = $wConexao->prepare("INSERT INTO tbUsuario (bdAluNome, bdAluTelefone, bdAluCPFCNPJ, bdAluEmail, bdAluSenha, token_verificacao) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('ssssss', $nome, $telefone, $cpf, $email, $senhaHash, $token);
                if ($stmt->execute()) {
                    // Enviar email de verificação
                    $url = "http://seusite.com/verificar_email.php?token=$token"; // Ajuste a URL para seu site
                    $assunto = "Confirme seu cadastro";
                    $mensagem = "Olá $nome,\nPor favor, clique no link abaixo para verificar seu e-mail e completar seu cadastro:\n$url";
                    $headers = 'From: seuemail@dominio.com';

                    // Função de envio de e-mail
                    if (mail($email, $assunto, $mensagem, $headers)) {
                        // Redireciona com uma mensagem para o usuário verificar o e-mail
                        header("Location: mensagem_verificacao.php");
                        exit();
                    } else {
                        $errorMessage = 'Erro ao enviar o e-mail de verificação';
                    }
                } else {
                    $errorMessage = 'Erro ao registrar o usuário';
                }
                $stmt->close();
            } else {
                $errorMessage = 'Erro na preparação da consulta de inserção';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/apetrecho/css/login.css" />
    <link rel="shortcut icon" href="/apetrecho/img/Apetrecho.ico" type="image/x-icon">
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
    <?php if (!empty($errorMessage)): ?>
        <script>
            alert("<?php echo htmlspecialchars($errorMessage); ?>");
        </script>
    <?php endif; ?>
</body>

</html>