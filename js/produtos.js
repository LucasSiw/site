document.addEventListener("DOMContentLoaded", function() {
    // Obter o parâmetro 'id' da URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    // Verifica se o productId existe
    if (productId) {
        // Faz uma requisição para o backend para obter os detalhes do produto
        fetch(`/apetrecho/produto.php?id=${productId}`)
            .then(response => response.json())
            .then(product => {
                if (!product.error) {
                    // Atualiza os elementos da página com os detalhes do produto
                    document.getElementById("product-name").textContent = product.name;
                    document.getElementById("product-description").textContent = product.description;
                    document.getElementById("product-price").textContent = product.price;
                    document.getElementById("product-image").src = product.image;

                    const specsList = document.getElementById("product-specs");
                    specsList.innerHTML = ''; // Limpa a lista antes de adicionar novos itens
                    product.specs.split(',').forEach(function(spec) {
                        const li = document.createElement("li");
                        li.textContent = spec.trim(); // Trim para remover espaços
                        specsList.appendChild(li);
                    });
                } else {
                    document.getElementById("product-name").textContent = product.error;
                    document.getElementById("product-description").textContent = '';
                    document.getElementById("product-price").textContent = '';
                    document.getElementById("product-image").src = ''; // Limpa a imagem
                    document.getElementById("product-specs").innerHTML = ''; // Limpa a lista de especificações
                }
            })
            .catch(error => {
                console.error('Erro ao buscar produto:', error);
                document.getElementById("product-name").textContent = "Erro ao carregar produto.";
            });
    } else {
        document.getElementById("product-name").textContent = "ID do produto não fornecido.";
    }
});

function adicionarAoCarrinho() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    const produto = {
        id: productId,
        nome: document.getElementById("product-name").textContent,
        preco: document.getElementById("product-price").textContent,
        imagem: document.getElementById("product-image").src
    };

    // Obtém o carrinho do Local Storage
    let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    carrinho.push(produto);

    // Atualiza o Local Storage
    localStorage.setItem("carrinho", JSON.stringify(carrinho));

    alert("Produto adicionado ao carrinho!");
}
