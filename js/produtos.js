document.addEventListener("DOMContentLoaded", function() {
    // Simulação de dados de produto. Substitua isso pela lógica de backend
    const produtos = {
        parafusadeira: {
            name: "Parafusadeira",
            description: "Parafusadeira elétrica de alta potência.",
            specs: ["Bateria de 18V", "Velocidade ajustável", "Leve e portátil"],
            price: "R$ 299,90",
            image: "images/parafusadeira.jpg"
        },
        // Adicione outros produtos aqui
    };

    // Obter o ID do produto da URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    // Verificar se o produto existe
    if (produtos[productId]) {
        const product = produtos[productId];

        // Atualizar o conteúdo da página
        document.getElementById("product-name").textContent = product.name;
        document.getElementById("product-description").textContent = product.description;
        document.getElementById("product-price").textContent = product.price;
        document.getElementById("product-image").src = product.image;

        // Atualizar especificações
        const specsList = document.getElementById("product-specs");
        product.specs.forEach(function(spec) {
            const li = document.createElement("li");
            li.textContent = spec;
            specsList.appendChild(li);
        });
    } else {
        // Produto não encontrado
        document.getElementById("product-name").textContent = "Produto não encontrado.";
    }
});
