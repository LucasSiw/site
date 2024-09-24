<?php 
session_start(); 
include_once('header.php'); 
include_once(__DIR__ . '/php/conexao.php');


$bdCodUsuario = $_SESSION['bdCodUsuario']; // Pegar o ID do usuário logado

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $descricaoProduto = htmlspecialchars($_POST['produtoDescricao']);
    $valorProduto = htmlspecialchars($_POST['produtoValor']);
    
    // Processar upload da imagem
    $imagemProduto = $_FILES['produtoImagem'];
    
    // Definir o caminho onde a imagem será salva
    $diretorioImagens = 'caminho/para/imagens/';
    $nomeImagem = uniqid() . '_' . basename($imagemProduto['name']); // Nome único para evitar sobrescritas
    $imagemCaminho = $diretorioImagens . $nomeImagem;
    
    // Verificar o tipo de arquivo
    $tipoArquivo = strtolower(pathinfo($imagemCaminho, PATHINFO_EXTENSION));
    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($tipoArquivo, $tiposPermitidos) && $imagemProduto['size'] <= 5000000) { // Limite de 5MB
        // Mover o arquivo para o diretório desejado
        if (move_uploaded_file($imagemProduto['tmp_name'], $imagemCaminho)) {
            // Inserir no banco de dados
            $stmt = $wConexao->prepare("INSERT INTO tbProduto (bdProdDescricao, bdProdValor, bdCodUsuario, bdProdImagem) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('sdiss', $descricaoProduto, $valorProduto, $bdCodUsuario, $imagemCaminho);
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
            echo "<script>alert('Erro ao fazer upload da imagem.');</script>";
        }
    } else {
        echo "<script>alert('Formato de imagem não permitido ou imagem muito grande.');</script>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Cadastrar Produto</h2>
    <form action="cadastrar_produto.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="produtoDescricao" class="form-label">Descrição do Produto</label>
            <textarea class="form-control" id="produtoDescricao" name="produtoDescricao" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="produtoValor" class="form-label">Valor do Produto</label>
            <input type="number" class="form-control" id="produtoValor" name="produtoValor" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="produtoImagem" class="form-label">Imagem do Produto</label>
            <input type="file" class="form-control" id="produtoImagem" name="produtoImagem" accept="image/*" required>
        </div>
        <button type="submit" id="btnCadastrar">Cadastrar Produto</button>

    </form>
</div>

<link rel="stylesheet" href="/apetrecho/css/cadastrarprodutos.css">
<?php include_once('footer.php'); ?>
