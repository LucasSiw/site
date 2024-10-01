<?php 
session_start();
include_once('header.php'); 
include_once(__DIR__ . '/php/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['bdCodUsuario'])) {
    echo "<script>alert('Usuário não logado.'); window.location.href='login.php';</script>";
    exit();
}

$bdCodUsuario = $_SESSION['bdCodUsuario']; // Certifique-se de que esta variável existe

// Lógica para cadastro de produtos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricaoProduto = htmlspecialchars($_POST['produtoDescricao']);
    $valorProduto = htmlspecialchars($_POST['produtoValor']);
    
    $imagemProduto = $_FILES['produtoImagem'];
    
    $diretorioImagens = __DIR__ . '/img/';
    $nomeImagem = uniqid() . '_' . basename($imagemProduto['name']); 
    $imagemCaminho = $diretorioImagens . $nomeImagem;
    
    $tipoArquivo = strtolower(pathinfo($imagemCaminho, PATHINFO_EXTENSION));
    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($tipoArquivo, $tiposPermitidos) && $imagemProduto['size'] <= 5000000) { 
        if (move_uploaded_file($imagemProduto['tmp_name'], $imagemCaminho)) {
            // Caminho relativo da imagem
            $imagemCaminhoRelativo = '/img/' . $nomeImagem;
            
            // Monta a URL completa da imagem
            $urlBase = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            $urlCompletaImagem = $urlBase . $imagemCaminhoRelativo;

            // Salva no banco de dados a URL completa
            $stmt = $wConexao->prepare("INSERT INTO tbProduto (bdProdDescricao, bdProdValor, bdCodUsuario, bdProdImagem) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('sdis', $descricaoProduto, $valorProduto, $bdCodUsuario, $urlCompletaImagem);
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
            echo "<script>alert('Erro ao mover o arquivo da imagem.');</script>";
        }
    } else {
        echo "<script>alert('Formato de imagem não permitido ou imagem muito grande.');</script>";
    }
}
?>
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

body {
    font-family: 'Poppins', sans-serif;
    background-color: #212529; 
    color: white; 
}

#btnCadastrar {
    border: none;
    background: #ffb742;
    color: black;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px; 
    transition: background-color 0.3s, box-shadow 0.3s; 
    cursor: pointer;
}

#btnCadastrar:hover {
    background: #fb8c00;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

#produtoDescricao {
    color: white;
    background-color: #495057; 
    border: 1px solid #6c757d; 
    border-radius: 5px; 
    padding: 10px; 
}

textarea,
input[type="number"],
input[type="file"] {
    width: 100%; 
    border-radius: 5px; 
    padding: 10px; 
    border: 1px solid #6c757d; 
}

textarea:focus,
input[type="number"]:focus,
input[type="file"]:focus {
    outline: none;
    border-color: #ffb742; 
}

.custom-margin-top {
    margin-top: 6rem;
}

</style>

<div class="container custom-margin-top">
    <h2 class="text-center mb-1">Cadastrar Produto</h2>
    <form action="cadastrarproduto.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="produtoDescricao" class="form-label"></label>
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
        <button type="submit" id="btnCadastrar" class="btn btn-primary">Cadastrar Produto</button>
    </form>
</div>

<?php include_once('footer.php'); ?>
