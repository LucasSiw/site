document.addEventListener("DOMContentLoaded", function() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const carrinhoItens = document.getElementById("carrinho-itens");
    let subTotal = 0;

    if (carrinho.length === 0) {
        carrinhoItens.innerHTML = '<tr><td colspan="5">Carrinho está vazio.</td></tr>';
    } else {
        carrinho.forEach(function(produto) {
            const tr = document.createElement("tr");
            const quantidade = 1; // Exemplo fixo, você pode implementar ajuste de quantidade
            const total = parseFloat(produto.preco.replace("R$ ", "").replace(",", ".")) * quantidade;

            tr.innerHTML = `
                <td>
                    <div class="product">
                        <img src="${produto.imagem}" alt="${produto.nome}" />
                        <div class="info">
                            <div class="name">${produto.nome}</div>
                            <div class="category">Categoria</div>
                        </div>
                    </div>
                </td>
                <td>${produto.preco}</td>
                <td>
                    <div class="qty">
                        <button><i class="bx bx-minus"></i></button>
                        <span>${quantidade}</span>
                        <button><i class="bx bx-plus"></i></button>
                    </div>
                </td>
                <td>R$ ${total.toFixed(2).replace(".", ",")}</td>
                <td>
                    <button class="remove" onclick="removerDoCarrinho('${produto.id}')"><i class="bx bx-x"></i></button>
                </td>
            `;
            carrinhoItens.appendChild(tr);
            subTotal += total;
        });
    }

    document.getElementById("sub-total").innerText = `R$ ${subTotal.toFixed(2).replace(".", ",")}`;
    document.getElementById("total").innerText = `R$ ${subTotal.toFixed(2).replace(".", ",")}`;
});

function removerDoCarrinho(id) {
    let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    carrinho = carrinho.filter(produto => produto.id !== id);
    localStorage.setItem("carrinho", JSON.stringify(carrinho));
    window.location.reload(); // Atualiza a página
}

function finalizarCompra() {
    alert("Compra finalizada!");
    // Aqui você pode redirecionar para uma página de confirmação ou processar a compra
}