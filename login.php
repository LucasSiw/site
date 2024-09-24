<?php
include_once(__DIR__ . '/php/conexao.php');
session_start();

$errorMessage = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $wLogin = htmlspecialchars($_POST['loginEmail']);
        $wSenha = $_POST['loginSenha'];

        $stmt = $wConexao->prepare("SELECT bdAluNome, bdAluSenha FROM tbUsuario WHERE bdAluEmail = ?");
        if ($stmt) {
            $stmt->bind_param('s', $wLogin);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($wNome, $hashedPassword);
                $stmt->fetch();

                if (password_verify($wSenha, $hashedPassword)) {
                    $_SESSION['usuario_nome'] = $wNome;
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

    // Registro
    if (isset($_POST['edNome'])) {
        $nome = htmlspecialchars($_POST['edNome']);
        $telefone = htmlspecialchars($_POST['edTelefone']);
        $cpf = htmlspecialchars($_POST['edCPF']);
        $email = htmlspecialchars($_POST['edEmail']);
        $senha = $_POST['edSenha'];
        $senhaRep = $_POST['edSenhaRep'];

        if ($senha !== $senhaRep) {
            $errorMessage = 'As senhas não coincidem';
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $wConexao->prepare("INSERT INTO tbUsuario (bdAluNome, bdAluTelefone, bdAluCPFCNPJ, bdAluEmail, bdAluSenha) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('sssss', $nome, $telefone, $cpf, $email, $senhaHash);
                if ($stmt->execute()) {
                    header("Location: telahome.php");
                    exit;
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