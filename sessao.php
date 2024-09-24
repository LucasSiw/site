<?php
include_once(__DIR__ . '/php/conexao.php');

session_start();

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

    // Registro
    if (isset($_POST['edNome'])) {
        $nome = htmlspecialchars($_POST['edNome']);
        $telefone = htmlspecialchars($_POST['edTelefone']);
        $cpf = htmlspecialchars($_POST['edCPF']);
        $email = htmlspecialchars($_POST['edEmail']);
        $senha = $_POST['edSenha'];
        $senhaRep = $_POST['edSenhaRep'];

        if ($senha !== $senhaRep) {
            echo '<p style="color: red;">As senhas não coincidem</p>';
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $wConexao->prepare("INSERT INTO tbUsuario (bdAluNome, bdAluTelefone, bdAluCPF, bdAluEmail, bdAluSenha) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('sssss', $nome, $telefone, $cpf, $email, $senhaHash);
                if ($stmt->execute()) {
                    echo '<p style="color: green;">Usuário registrado com sucesso!</p>';
                } else {
                    echo '<p style="color: red;">Erro ao registrar o usuário</p>';
                }
                $stmt->close();
            } else {
                echo '<p style="color: red;">Erro na preparação da consulta de inserção</p>';
            }
        }
    }
}
?>
