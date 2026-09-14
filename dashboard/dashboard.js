"use strict";




const ESTOQUE_CRITICO = 5;
async function carregarDados() {
    
    
    const resposta = await fetch('api/dashboard.php');
    if (!resposta.ok) {
        throw new Error(`Falha ao buscar dados: HTTP ${resposta.status}`);
    }
    const dados = await resposta.json();
    return dados;
}
function calcularFaturamentoTotal(pedidos) {
    return pedidos.reduce((totalGeral, pedido) => {
        const totalPedido = pedido.itens.reduce((subtotal, item) => subtotal + item.quantidade * item.precoUnitario, 0);
        return totalGeral + totalPedido;
    }, 0);
}
function produtosPorCategoria(produtos, categoria) {
    return produtos.filter((p) => p.categoria === categoria);
}
function produtosEstoqueCritico(produtos) {
    return produtos.filter((p) => p.estoque <= ESTOQUE_CRITICO);
}
function produtoMaisVendido(pedidos) {
    const contagem = {};
    for (const pedido of pedidos) {
        for (const item of pedido.itens) {
            contagem[item.nome] = (contagem[item.nome] ?? 0) + item.quantidade;
        }
    }
    const nomes = Object.keys(contagem);
    if (nomes.length === 0) {
        return null;
    }
    let nomeTopo = nomes[0];
    for (const nome of nomes) {
        if (contagem[nome] > contagem[nomeTopo]) {
            nomeTopo = nome;
        }
    }
    return { nome: nomeTopo, quantidadeVendida: contagem[nomeTopo] };
}
function formatarMoedaBRL(valor) {
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}
function formatarProdutos(produtos) {
    return produtos.map((p) => ({
        nome: p.nome,
        precoFormatado: formatarMoedaBRL(p.preco),
        categoria: p.categoria,
    }));
}
function definirTexto(elementId, texto) {
    const elemento = document.getElementById(elementId);
    if (elemento === null) {
        console.warn(`Elemento #${elementId} não encontrado no DOM.`);
        return;
    }
    elemento.textContent = texto;
}
function renderizarListaProdutos(elementId, produtos) {
    const container = document.getElementById(elementId);
    if (container === null) {
        return;
    }
    if (produtos.length === 0) {
        container.innerHTML = '<li class="list-group-item text-muted">Nenhum dado registrado.</li>';
        return;
    }
    container.innerHTML = produtos
        .map((p) => `<li class="list-group-item d-flex justify-content-between">
                <span>${p.nome}</span>
                <strong>${p.precoFormatado}</strong>
            </li>`)
        .join('');
}
function renderizarDashboard(dados) {
    if (dados.pedidos.length === 0) {
        definirTexto('faturamento-total', 'Nenhum dado registrado');
    }
    else {
        const faturamento = calcularFaturamentoTotal(dados.pedidos);
        definirTexto('faturamento-total', formatarMoedaBRL(faturamento));
    }
    const topo = produtoMaisVendido(dados.pedidos);
    if (topo === null) {
        definirTexto('produto-mais-vendido', 'Nenhum dado registrado');
    }
    else {
        definirTexto('produto-mais-vendido', `${topo.nome} (${topo.quantidadeVendida} un.)`);
    }
    const criticos = produtosEstoqueCritico(dados.produtos);
    definirTexto('total-estoque-critico', String(criticos.length));
    renderizarListaProdutos('lista-estoque-critico', formatarProdutos(criticos));
    const celulares = produtosPorCategoria(dados.produtos, 'Celulares');
    renderizarListaProdutos('lista-celulares', formatarProdutos(celulares));
}
async function iniciarDashboard() {
    try {
        const dados = await carregarDados();
        renderizarDashboard(dados);
    }
    catch (erro) {
        console.error('Erro ao carregar a dashboard:', erro);
        definirTexto('faturamento-total', 'Erro ao carregar dados');
        definirTexto('produto-mais-vendido', 'Erro ao carregar dados');
    }
}
document.addEventListener('DOMContentLoaded', () => {
    void iniciarDashboard();
});
