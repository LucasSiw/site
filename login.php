<?php
session_start();
include_once(__DIR__ . '/php/conexao.php');
$errorMessage = '';

// Incluindo o PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Login
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
                    $_SESSION['bdCodUsuario'] = $bdCodUsuario;

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
            $token = bin2hex(random_bytes(50));

            $stmt = $wConexao->prepare("INSERT INTO tbUsuario (bdAluNome, bdAluTelefone, bdAluCPFCNPJ, bdAluEmail, bdAluSenha, token_verificacao) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('ssssss', $nome, $telefone, $cpf, $email, $senhaHash, $token);
                if ($stmt->execute()) {
                    // Enviar email de verificação
                    $mail = new PHPMailer(true);
                    try {
                        // Configurações do servidor
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'apetrechosenai@gmail.com'; // Seu e-mail
                        $mail->Password = 'Apetrecho@1Sen4i'; // Sua senha do e-mail
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Destinatários
                        $mail->setFrom('apetrechosenai@gmail.com', 'Apetrecho');
                        $mail->addAddress($email, $nome);

                        // Conteúdo do e-mail
                        $mail->isHTML(true);
                        $mail->Subject = "Confirme seu cadastro";
                        $url = "http://localhost/apetrecho/mensagemverificacao.php?token=$token";
                        $mail->Body = "Olá $nome,<br>Por favor, clique no link abaixo para verificar seu e-mail e completar seu cadastro:<br><a href='$url'>$url</a>";
                        $mail->AltBody = "Olá $nome,\nPor favor, clique no link abaixo para verificar seu e-mail e completar seu cadastro:\n$url";

                        $mail->send();
                        header("Location: mensagemverificacao.php");
                        exit();
                    } catch (Exception $e) {
                        $errorMessage = 'Erro ao enviar o e-mail de verificação: ' . $mail->ErrorInfo;
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
                        <input type="text" placeholder="Nome" name="edNome" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Telefone" name="edTelefone" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-id-card"></i>
                        <input type="text" placeholder="CPF" name="edCPF" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Email" name="edEmail" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Senha" name="edSenha" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Repita senha" name="edSenhaRep" required />
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
                    <button class="btn transparent" id="inicio">Ínicio</button>
                </div>
                <img src="/apetrecho/img/log.svg" class="image" alt="" />
            </div>
        </div>
    </div>

    <?php if ($errorMessage): ?>
        <div class="error"><?= $errorMessage ?></div>
    <?php endif; ?>


    <script src="/apetrecho/js/login.js"></script>
</body>

</html>