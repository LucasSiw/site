<?php 
session_start(); 
include_once('header.php'); 
include_once(__DIR__ . '/php/conexao.php');

// Verificar se o usuário está logado


$bdCodUsuario = $_SESSION['bdCodUsuario']; // Pegar o ID do usuário logado

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $descricaoProduto = htmlspecialchars($_POST['produtoDescricao']);
    $valorProduto = htmlspecialchars($_POST['produtoValor']);
    
    // Validação simples
    if (!empty($descricaoProduto) && !empty($valorProduto)) {
        // Inserir no banco de dados
        $stmt = $wConexao->prepare("INSERT INTO tbProduto (bdProdDescricao, bdProdValor, bdCodUsuario) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sdi', $descricaoProduto, $valorProduto, $bdCodUsuario);
            if ($stmt->execute()) {
                echo "<script>alert('Produto cadastrado com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao cadastrar o produto.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Erro na preparação da consulta');</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos.');</script>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Cadastrar Produto</h2>
    <form action="cadastrar_produto.php" method="POST">
        <div class="mb-3">
            <label for="produtoDescricao" class="form-label">Descrição do Produto</label>
            <textarea class="form-control" id="produtoDescricao" name="produtoDescricao" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="produtoValor" class="form-label">Valor do Produto</label>
            <input type="number" class="form-control" id="produtoValor" name="produtoValor" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
    </form>
</div>

<?php include_once('footer.php'); ?>