<?php
include_once(__DIR__ . '/php/conexao.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar se o token é válido
    $stmt = $wConexao->prepare("SELECT bdCodUsuario FROM tbUsuario WHERE token_verificacao = ?");
    if ($stmt) {
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Token válido, ativar o usuário
            $stmt->bind_result($bdCodUsuario);
            $stmt->fetch();

            $updateStmt = $wConexao->prepare("UPDATE tbUsuario SET email_verificado = 1, token_verificacao = NULL WHERE bdCodUsuario = ?");
            if ($updateStmt) {
                $updateStmt->bind_param('i', $bdCodUsuario);
                $updateStmt->execute();
                $updateStmt->close();

                echo "Seu e-mail foi verificado com sucesso!";
                // Redirecionar para a página de login
                header("Location: login.php");
                exit();
            }
        } else {
            echo "Token inválido ou já utilizado.";
        }
        $stmt->close();
    } else {
        echo "Erro ao verificar o token.";
    }
} else {
    echo "Token não fornecido.";
}
?>
