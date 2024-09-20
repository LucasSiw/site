document.addEventListener("DOMContentLoaded", function() {
    const produtos = {
        parafusadeira: {
            name: "Parafusadeira",
            description: "Parafusadeira elétrica de alta potência.",
            specs: ["Bateria de 18V", "Velocidade ajustável", "Leve e portátil"],
            price: "R$ 299,90",
            image: "/apetrecho/img/parafusadeira.png"
        },

        luvas: {
            name: "Luva",
            description: "Luvas de Segurança",
            specs: ["Proteção", "Segurança"],
            price: "R$ 19,99",
            image: "/apetrecho/img/luva.png"
        },

        broca: {
            name: "Broca",
            description: "Broca para furar metal.",
            specs: [],
            price: "R$ 15,90",
            image: "/apetrecho/img/broca.png"
        },
    };

    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    if (produtos[productId]) {
        const product = produtos[productId];

        document.getElementById("product-name").textContent = product.name;
        document.getElementById("product-description").textContent = product.description;
        document.getElementById("product-price").textContent = product.price;
        document.getElementById("product-image").src = product.image;

        const specsList = document.getElementById("product-specs");
        product.specs.forEach(function(spec) {
            const li = document.createElement("li");
            li.textContent = spec;
            specsList.appendChild(li);
        });
    } else {
        document.getElementById("product-name").textContent = "Produto não encontrado.";
    }
});
