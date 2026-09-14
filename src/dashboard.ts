




interface Produto {
    id: number;
    nome: string;
    categoria: string;
    categoriaId: number;
    preco: number;
    estoque: number;
    imagem: string;
}

interface ItemPedido {
    produtoId: number;
    nome: string;
    quantidade: number;
    precoUnitario: number;
}

interface Pedido {
    id: number;
    cliente: string;
    data: string;
    itens: ItemPedido[];
}

interface DashboardData {
    produtos: Produto[];
    pedidos: Pedido[];
}

interface ProdutoRanking {
    nome: string;
    quantidadeVendida: number;
}

const ESTOQUE_CRITICO = 5;

async function carregarDados(): Promise<DashboardData> {
    
    
    const resposta = await fetch('api/dashboard.php');

    if (!resposta.ok) {
        throw new Error(`Falha ao buscar dados: HTTP ${resposta.status}`);
    }

    const dados: DashboardData = await resposta.json();
    return dados;
}

function calcularFaturamentoTotal(pedidos: Pedido[]): number {
    return pedidos.reduce((totalGeral: number, pedido: Pedido): number => {
        const totalPedido = pedido.itens.reduce(
            (subtotal: number, item: ItemPedido): number =>
                subtotal + item.quantidade * item.precoUnitario,
            0
        );
        return totalGeral + totalPedido;
    }, 0);
}

function produtosPorCategoria(produtos: Produto[], categoria: string): Produto[] {
    return produtos.filter((p: Produto): boolean => p.categoria === categoria);
}

function produtosEstoqueCritico(produtos: Produto[]): Produto[] {
    return produtos.filter((p: Produto): boolean => p.estoque <= ESTOQUE_CRITICO);
}

function produtoMaisVendido(pedidos: Pedido[]): ProdutoRanking | null {
    const contagem: Record<string, number> = {};

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

interface ProdutoFormatado {
    nome: string;
    precoFormatado: string;
    categoria: string;
}

function formatarMoedaBRL(valor: number): string {
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

function formatarProdutos(produtos: Produto[]): ProdutoFormatado[] {
    return produtos.map((p: Produto): ProdutoFormatado => ({
        nome: p.nome,
        precoFormatado: formatarMoedaBRL(p.preco),
        categoria: p.categoria,
    }));
}

function definirTexto(elementId: string, texto: string): void {
    const elemento = document.getElementById(elementId);
    if (elemento === null) {
        console.warn(`Elemento #${elementId} não encontrado no DOM.`);
        return;
    }
    elemento.textContent = texto;
}

function renderizarListaProdutos(elementId: string, produtos: ProdutoFormatado[]): void {
    const container = document.getElementById(elementId);
    if (container === null) {
        return;
    }

    if (produtos.length === 0) {
        container.innerHTML = '<li class="list-group-item text-muted">Nenhum dado registrado.</li>';
        return;
    }

    container.innerHTML = produtos
        .map((p: ProdutoFormatado): string =>
            `<li class="list-group-item d-flex justify-content-between">
                <span>${p.nome}</span>
                <strong>${p.precoFormatado}</strong>
            </li>`
        )
        .join('');
}

function renderizarDashboard(dados: DashboardData): void {
    if (dados.pedidos.length === 0) {
        definirTexto('faturamento-total', 'Nenhum dado registrado');
    } else {
        const faturamento = calcularFaturamentoTotal(dados.pedidos);
        definirTexto('faturamento-total', formatarMoedaBRL(faturamento));
    }

    const topo = produtoMaisVendido(dados.pedidos);
    if (topo === null) {
        definirTexto('produto-mais-vendido', 'Nenhum dado registrado');
    } else {
        definirTexto('produto-mais-vendido', `${topo.nome} (${topo.quantidadeVendida} un.)`);
    }

    const criticos = produtosEstoqueCritico(dados.produtos);
    definirTexto('total-estoque-critico', String(criticos.length));
    renderizarListaProdutos('lista-estoque-critico', formatarProdutos(criticos));

    const celulares = produtosPorCategoria(dados.produtos, 'Celulares');
    renderizarListaProdutos('lista-celulares', formatarProdutos(celulares));
}

async function iniciarDashboard(): Promise<void> {
    try {
        const dados = await carregarDados();
        renderizarDashboard(dados);
    } catch (erro) {
        console.error('Erro ao carregar a dashboard:', erro);
        definirTexto('faturamento-total', 'Erro ao carregar dados');
        definirTexto('produto-mais-vendido', 'Erro ao carregar dados');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    void iniciarDashboard();
});