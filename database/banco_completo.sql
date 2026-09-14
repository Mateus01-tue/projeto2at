-- Cria o banco caso ainda não exista
CREATE DATABASE IF NOT EXISTS `adaltocell` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `adaltocell`;

-- Remove tabelas antigas (se existirem) para permitir reimportação limpa
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `pedido_produto`;
DROP TABLE IF EXISTS `pedidos`;
DROP TABLE IF EXISTS `produtos`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `usuarios`;
SET FOREIGN_KEY_CHECKS = 1;


;
;
;
;

--
-- Banco de dados: `adaltocell`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'Celulares'),
(2, 'Acessorios'),
(3, 'TVs');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `data_pedido` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_produto`
--

CREATE TABLE `pedido_produto` (
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `categoria_id`) VALUES
(1, 'Samsung S25', 'Smartphone Samsung de alto desempenho', 4999.99, 's25.png', 1),
(2, 'Motorola Edge', 'Smartphone Motorola com excelente camera', 2799.99, 'edge.png', 1),
(3, 'Xiaomi Redmi', 'Smartphone Xiaomi com otimo custo beneficio', 2199.99, 'redmi.png', 1),
(4, 'Smart TV 50', 'Televisao Smart TV 50 polegadas 4K', 2199.99, 'tv50.png', 3),
(5, 'Fone Bluetooth', 'Fone sem fio com bluetooth', 149.99, 'fone.png', 2),
(6, 'Teclado Gamer', 'Teclado mecanico RGB', 199.99, 'teclado.png', 2),
(7, 'Caixa JBL', 'Caixa de som bluetooth portatil', 299.99, 'jbl.png', 2),
(9, 'Iphone 14', 'O iPhone 14 oferece excelente desempenho', 2999.99, 'iphone14.png', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `pedido_produto`
--
ALTER TABLE `pedido_produto`
  ADD PRIMARY KEY (`pedido_id`,`produto_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Restrições para tabelas `pedido_produto`
--
ALTER TABLE `pedido_produto`
  ADD CONSTRAINT `pedido_produto_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_produto_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

;
;
;


-- ============================================================
-- adaltocell_extra.sql
-- Complementos de Banco de Dados Avançado para o projeto Adalto CELL
-- Rode este script DEPOIS de importar o adaltocell.sql original
-- ============================================================

USE `adaltocell`;

-- ------------------------------------------------------------
-- 0. Ajuste de estrutura: coluna de estoque (necessária para o
--    filtro de "estoque crítico" pedido na parte de Lógica Avançada)
-- ------------------------------------------------------------
ALTER TABLE `produtos`
  ADD COLUMN `estoque` INT NOT NULL DEFAULT 10 AFTER `preco`;

UPDATE `produtos` SET `estoque` = 15 WHERE `id` = 1;
UPDATE `produtos` SET `estoque` = 3  WHERE `id` = 2; -- estoque crítico
UPDATE `produtos` SET `estoque` = 8  WHERE `id` = 3;
UPDATE `produtos` SET `estoque` = 20 WHERE `id` = 4;
UPDATE `produtos` SET `estoque` = 2  WHERE `id` = 5; -- estoque crítico
UPDATE `produtos` SET `estoque` = 12 WHERE `id` = 6;
UPDATE `produtos` SET `estoque` = 6  WHERE `id` = 7;
UPDATE `produtos` SET `estoque` = 4  WHERE `id` = 9; -- estoque crítico

-- ------------------------------------------------------------
-- 1. Dados de exemplo em clientes / pedidos / pedido_produto
--    (necessário para calcular faturamento real na dashboard)
-- ------------------------------------------------------------
INSERT INTO `clientes` (`nome`, `telefone`, `email`) VALUES
('Ana Souza',   '(44) 99111-2233', 'ana.souza@email.com'),
('Bruno Lima',  '(44) 99222-3344', 'bruno.lima@email.com'),
('Carla Melo',  '(44) 99333-4455', 'carla.melo@email.com');

INSERT INTO `pedidos` (`cliente_id`, `data_pedido`) VALUES
(1, '2026-07-10'),
(2, '2026-07-15'),
(3, '2026-07-20'),
(1, '2026-07-28');

INSERT INTO `pedido_produto` (`pedido_id`, `produto_id`, `quantidade`) VALUES
(1, 1, 1),
(1, 5, 2),
(2, 2, 1),
(2, 7, 1),
(3, 9, 1),
(3, 6, 1),
(4, 3, 2);

-- ------------------------------------------------------------
-- 2. VIEW simples: produtos já com o nome da categoria
--    (evita repetir o JOIN em toda query do sistema)
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW `vw_produtos_completo` AS
SELECT
    p.id,
    p.nome,
    p.descricao,
    p.preco,
    p.estoque,
    p.imagem,
    c.id   AS categoria_id,
    c.nome AS categoria_nome
FROM produtos p
LEFT JOIN categorias c ON c.id = p.categoria_id;

-- ------------------------------------------------------------
-- 3. VIEW analítica construída com CTE: faturamento por categoria
--    (junta produtos + pedido_produto + categorias em uma métrica
--     de negócio pronta para consumo direto pela API)
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW `vw_faturamento_por_categoria` AS
WITH vendas AS (
    SELECT
        pr.categoria_id,
        (pr.preco * pp.quantidade) AS subtotal
    FROM pedido_produto pp
    JOIN produtos pr ON pr.id = pp.produto_id
)
SELECT
    c.id   AS categoria_id,
    c.nome AS categoria,
    COALESCE(SUM(v.subtotal), 0) AS faturamento_total,
    COALESCE(COUNT(v.subtotal), 0) AS itens_vendidos
FROM categorias c
LEFT JOIN vendas v ON v.categoria_id = c.id
GROUP BY c.id, c.nome;

-- ------------------------------------------------------------
-- 4. VIEW analítica: pedidos detalhados (une clientes, pedidos,
--    pedido_produto e produtos numa única fonte de consulta)
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW `vw_pedidos_detalhe` AS
SELECT
    pe.id            AS pedido_id,
    pe.data_pedido,
    cl.id            AS cliente_id,
    cl.nome          AS cliente_nome,
    pr.id            AS produto_id,
    pr.nome          AS produto_nome,
    pr.preco         AS preco_unitario,
    pp.quantidade,
    (pr.preco * pp.quantidade) AS subtotal
FROM pedidos pe
JOIN clientes cl        ON cl.id = pe.cliente_id
JOIN pedido_produto pp  ON pp.pedido_id = pe.id
JOIN produtos pr        ON pr.id = pp.produto_id;

-- ------------------------------------------------------------
-- 5. FUNCTION reutilizável: calcula preço com desconto
--    Usada tanto em scripts avulsos quanto dentro de outras
--    queries/views sem precisar repetir a fórmula.
-- ------------------------------------------------------------
DROP FUNCTION IF EXISTS `fn_preco_com_desconto`;
DELIMITER $$
CREATE FUNCTION `fn_preco_com_desconto`(
    p_preco DECIMAL(10,2),
    p_percentual DECIMAL(5,2)
)
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    IF p_percentual IS NULL OR p_percentual < 0 THEN
        RETURN p_preco;
    END IF;
    RETURN ROUND(p_preco - (p_preco * p_percentual / 100), 2);
END$$
DELIMITER ;

-- Exemplo de uso:
-- SELECT nome, preco, fn_preco_com_desconto(preco, 10) AS preco_com_10pct FROM produtos;

-- ------------------------------------------------------------
-- 6. TRIGGER: padroniza valores positivos ao atualizar um produto
--    Evita que preco ou estoque fiquem negativos por erro de
--    digitação ou falha de validação no PHP.
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS `trg_produtos_valores_positivos`;
DELIMITER $$
CREATE TRIGGER `trg_produtos_valores_positivos`
BEFORE UPDATE ON `produtos`
FOR EACH ROW
BEGIN
    IF NEW.preco IS NULL OR NEW.preco <= 0 THEN
        SET NEW.preco = OLD.preco;
    END IF;

    IF NEW.estoque IS NULL OR NEW.estoque < 0 THEN
        SET NEW.estoque = 0;
    END IF;
END$$
DELIMITER ;

-- ------------------------------------------------------------
-- 7. STORED PROCEDURE: centraliza busca, filtro por categoria
--    e paginação — a API em PHP passa a só fazer CALL(),
--    sem montar SQL dinâmico na mão.
-- ------------------------------------------------------------
DROP PROCEDURE IF EXISTS `sp_buscar_produtos`;
DELIMITER $$
CREATE PROCEDURE `sp_buscar_produtos`(
    IN p_termo VARCHAR(150),
    IN p_categoria_id INT,
    IN p_limite INT,
    IN p_offset INT
)
BEGIN
    IF p_limite IS NULL OR p_limite <= 0 THEN
        SET p_limite = 20;
    END IF;
    IF p_offset IS NULL OR p_offset < 0 THEN
        SET p_offset = 0;
    END IF;

    SELECT *
    FROM vw_produtos_completo
    WHERE (p_termo IS NULL OR p_termo = '' OR nome LIKE CONCAT('%', p_termo, '%'))
      AND (p_categoria_id IS NULL OR p_categoria_id = 0 OR categoria_id = p_categoria_id)
    ORDER BY nome
    LIMIT p_limite OFFSET p_offset;
END$$
DELIMITER ;

-- Exemplo de uso:
-- CALL sp_buscar_produtos('smart', 0, 10, 0);


-- ============================================================
-- sql_usuarios.sql
-- Tabela de usuários com sistema de cadastro/login por
-- NOME + SENHA (sem email), com dois níveis de acesso:
-- 'cliente' (comum) e 'admin' (você, dono da loja).
-- ============================================================

USE `adaltocell`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- sp_listar_pedidos.sql
-- Stored procedure que centraliza a busca/paginação dos pedidos
-- (com seus itens) usados pelos indicadores da dashboard.
-- ============================================================

USE `adaltocell`;

DROP PROCEDURE IF EXISTS `sp_listar_pedidos`;
DELIMITER $$
CREATE PROCEDURE `sp_listar_pedidos`(
    IN p_limite INT,
    IN p_offset INT
)
BEGIN
    IF p_limite IS NULL OR p_limite <= 0 THEN
        SET p_limite = 1000;
    END IF;
    IF p_offset IS NULL OR p_offset < 0 THEN
        SET p_offset = 0;
    END IF;

    SELECT *
    FROM vw_pedidos_detalhe
    ORDER BY pedido_id
    LIMIT p_limite OFFSET p_offset;
END$$
DELIMITER ;
