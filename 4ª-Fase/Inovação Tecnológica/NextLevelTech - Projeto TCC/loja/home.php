<?php
// Configurar timezone para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

// Configuração do banco de dados
$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "ecommerce_perifericos";

// Conectar ao banco
$conn = @mysqli_connect($servidor, $usuario, $senha, $banco);
if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// Parâmetros de busca e filtros
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
$ordenacao = isset($_GET['ordem']) ? $_GET['ordem'] : 'relevante';
$marca_id = isset($_GET['marca']) ? (int)$_GET['marca'] : 0;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$produtos_por_pagina = 8;
$offset = ($pagina - 1) * $produtos_por_pagina;

// Buscar categorias
$categorias = array();
$rs_cat = mysqli_query($conn, "SELECT codigo, nome FROM categoria ORDER BY nome");
if ($rs_cat) {
    while ($row = mysqli_fetch_assoc($rs_cat)) {
        $categorias[] = $row;
    }
}

// Buscar marcas
$marcas = array();
$rs_marca = mysqli_query($conn, "SELECT codigo, nome FROM marca ORDER BY nome");
if ($rs_marca) {
    while ($row = mysqli_fetch_assoc($rs_marca)) {
        $marcas[] = $row;
    }
}

// Construir query de produtos com filtros
$where_clauses = array("p.ativo = 1");
$params = array();
$types = "";

if ($categoria_id > 0) {
    $where_clauses[] = "p.codcategoria = ?";
    $params[] = $categoria_id;
    $types .= "i";
}

if ($marca_id > 0) {
    $where_clauses[] = "p.codmarca = ?";
    $params[] = $marca_id;
    $types .= "i";
}

if ($pesquisa !== '') {
    $where_clauses[] = "(p.nome LIKE ? OR p.descricao LIKE ? OR p.modelo LIKE ?)";
    $search_term = "%{$pesquisa}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "sss";
}

$where_sql = implode(" AND ", $where_clauses);

// Definir ordenação
$order_sql = "p.data_cadastro DESC, p.codigo DESC";
switch($ordenacao) {
    case 'menor_preco':
        $order_sql = "p.preco ASC";
        break;
    case 'maior_preco':
        $order_sql = "p.preco DESC";
        break;
    case 'nome':
        $order_sql = "p.nome ASC";
        break;
    case 'lancamento':
        $order_sql = "p.data_cadastro DESC";
        break;
}

// Contar total de produtos
$sql_count = "SELECT COUNT(*) as total FROM produto p WHERE {$where_sql}";
$stmt_count = mysqli_prepare($conn, $sql_count);
if (!function_exists('mysqli_bind_params_refs')) {
    function mysqli_bind_params_refs($stmt, $types, $params) {
        $bindArgs = array_merge(array($stmt, $types), $params);
        foreach ($bindArgs as $key => $value) {
            $bindArgs[$key] = &$bindArgs[$key];
        }
        return call_user_func_array('mysqli_stmt_bind_param', $bindArgs);
    }
}
if (!empty($params)) {
    mysqli_bind_params_refs($stmt_count, $types, $params);
}
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);
$count_row = mysqli_fetch_assoc($result_count);
$total_produtos = $count_row['total'];
$total_paginas = ceil($total_produtos / $produtos_por_pagina);

// Buscar produtos
$sql_produtos = "SELECT p.*, m.nome AS marca_nome, c.nome AS categoria_nome 
                 FROM produto p 
                 INNER JOIN marca m ON p.codmarca = m.codigo 
                 INNER JOIN categoria c ON p.codcategoria = c.codigo 
                 WHERE {$where_sql} 
                 ORDER BY {$order_sql} 
                 LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($conn, $sql_produtos);
$all_params = array_merge($params, array($produtos_por_pagina, $offset));
$all_types = $types . "ii";
mysqli_bind_params_refs($stmt, $all_types, $all_params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$produtos = array();
while ($row = mysqli_fetch_assoc($result)) {
    $produtos[] = $row;
}

// Ícones de categorias
$category_icons = array(
    'mouse' => '🖱️',
    'teclado' => '⌨️',
    'headset' => '🎧',
    'monitor' => '🖥️',
    'mousepad' => '🎯',
    'controle' => '🎮',
    'cadeira' => '💺',
    'webcam' => '📷',
    'microfone' => '🎤',
    'default' => '🎮'
);

// Contar itens no carrinho
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['qty']) ? (int)$item['qty'] : 0;
    }
}

// Função para construir URL com filtros
function buildUrl($params) {
    $current = array(
        'categoria' => isset($_GET['categoria']) ? $_GET['categoria'] : '',
        'marca' => isset($_GET['marca']) ? $_GET['marca'] : '',
        'ordem' => isset($_GET['ordem']) ? $_GET['ordem'] : '',
        'pesquisa' => isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '',
        'pagina' => isset($_GET['pagina']) ? $_GET['pagina'] : ''
    );
    
    $merged = array_merge($current, $params);
    $filtered = array_filter($merged, function($v) { return $v !== '' && $v !== null; });
    
    return 'home.php' . (empty($filtered) ? '' : '?' . http_build_query($filtered));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="theme.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextLevel Tech - Periféricos Gamer</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #000;
            --secondary-color: #fff;
            --accent-color: #FF6B00;
            --text-primary: #000;
            --text-secondary: #666;
            --border-color: #e5e5e5;
            --bg-gray: #f5f5f5;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #fff;
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* ===== HEADER TRANSPARENTE COM TRANSIÇÃO ===== */
.header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 999;
  background: transparent;
  transition: all 0.3s ease;
}

.header.scrolled,
.header:hover {
  background: rgba(255, 255, 255, 0.97);
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* conteúdo interno */
.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 40px;
  max-width: 1300px;
  margin: 0 auto;
}

/* logo */
.logo {
  font-size: 1.6rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}
.logo span { color: #ff3333; }
.header.scrolled .logo,
.header:hover .logo {
  color: #111;
}

/* menu */
.nav-menu {
  display: flex;
  gap: 25px;
  list-style: none;
}
.nav-menu a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s;
}
.header.scrolled .nav-menu a,
.header:hover .nav-menu a {
  color: #111;
}

/* ações (ícones e busca) */
.header-actions {
  display: flex;
  align-items: center;
  gap: 15px;
}
.icon-btn {
  font-size: 20px;
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}
.header.scrolled .icon-btn,
.header:hover .icon-btn {
  color: #111;
}

.search-box input {
  border: none;
  border-radius: 20px;
  padding: 6px 10px;
  outline: none;
}
.search-box button {
  background: none;
  border: none;
  cursor: pointer;
}

/* carrinho */
.cart-count {
  background: #ff3333;
  color: #fff;
  border-radius: 50%;
  padding: 1px 6px;
  font-size: 12px;
  position: relative;
  top: -8px;
  right: 5px;
}

/* mobile */
.mobile-menu-btn {
  display: none;
  font-size: 24px;
  background: none;
  border: none;
  color: white;
  cursor: pointer;
}
.header.scrolled .mobile-menu-btn,
.header:hover .mobile-menu-btn {
  color: #111;
}

@media (max-width: 768px) {
  .nav-menu { display: none; }
  .mobile-menu-btn { display: block; }
}
        /* Hero Banner */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect fill="%23ffffff" opacity="0.03" width="100" height="100"/><path d="M0 50 L50 0 L100 50 L50 100 Z" fill="%23ffffff" opacity="0.05"/></svg>') repeat;
            animation: patternMove 20s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(100px, 100px); }
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            letter-spacing: -2px;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .cta-btn {
            display: inline-block;
            padding: 15px 40px;
            background: var(--accent-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .cta-btn:hover {
            background: #ff5500;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 0, 0.3);
        }

        /* Categories */
        .categories-section {
            padding: 60px 40px;
            background: var(--bg-gray);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 16px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .category-card {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid var(--border-color);
            text-decoration: none;
            color: inherit;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .category-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, #fff5f0 0%, #ffffff 100%);
        }

        .category-icon {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }

        .category-name {
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filters */
        .filters-section {
            padding: 40px 40px 20px;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }

        .filters-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
        }

        select {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            min-width: 150px;
            transition: all 0.3s;
            color: #000;
        }

        select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .results-info {
            margin-left: auto;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .clear-filters {
            padding: 8px 15px;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .clear-filters:hover {
            background: #ff5500;
        }

        /* Products Grid */
        .products-section {
            padding: 60px 40px;
            background: white;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .product-card:hover {
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        .product-image-wrapper {
            position: relative;
            padding-top: 100%;
            background: var(--bg-gray);
            overflow: hidden;
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent-color);
            color: white;
            padding: 5px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }

        .product-badge.out-of-stock {
            background: #999;
        }

        .product-badge.low-stock {
            background: #ff9800;
        }

        .product-info {
            padding: 20px;
        }

        .product-brand {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-primary);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn-add-cart {
            flex: 1;
            padding: 12px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-add-cart:hover {
            background: var(--accent-color);
        }

        .btn-add-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .btn-quick-view {
            padding: 12px 20px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #000;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-quick-view:hover {
            border-color: var(--primary-color);
            background: var(--bg-gray);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 60px;
        }

        .pagination-btn {
            padding: 10px 20px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s;
        }

        .pagination-btn:hover:not(.disabled) {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination-btn.disabled {
            opacity: 0.3;
            pointer-events: none;
        }

        .pagination-info {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0 10px;
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: white;
            padding: 60px 40px 30px;
            margin-top: 80px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 12px;
        }

        .footer-section a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--accent-color);
        }

        .footer-bottom {
            max-width: 1400px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            color: rgba(255,255,255,0.5);
            font-size: 13px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            grid-column: 1 / -1;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-secondary);
            font-size: 16px;
        }

        /* Mobile */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .search-box input {
                width: 200px;
            }
        }

        
        @media (max-width: 768px) {
            .header-content {
                padding: 15px 20px;
            }

            .nav-menu {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .search-box {
                display: none;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }

            .section-title {
                font-size: 28px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .filters-content {
                padding: 20px;
            }

            .results-info {
                width: 100%;
                margin-left: 0;
                text-align: center;
            }

            .products-section {
                padding: 30px 20px;
            }

            .categories-section {
                padding: 30px 20px;
            }
        }
        /* ===== CARROSSEL - BANNER PRINCIPAL ===== */
.carousel-container {
    position: relative;
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    overflow: hidden;
    background: #000;
    height: 934px;
}

.carousel-slide {
    display: flex;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
    height: 100%;
}

.carousel-slide img {
    width: 100%;
    min-width: 100%;
    height: 100%;
    flex-shrink: 0;
    display: block;
    object-fit: cover;
    object-position: center;
}

.carousel-prev,
.carousel-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 20px 24px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    border-radius: 4px;
    line-height: 1;
}

.carousel-prev {
    left: 30px;
}

.carousel-next {
    right: 30px;
}

.carousel-prev:hover,
.carousel-next:hover {
    background: rgba(255, 107, 0, 0.9);
    transform: translateY(-50%) scale(1.1);
}

.carousel-prev:active,
.carousel-next:active {
    transform: translateY(-50%) scale(0.95);
}

.carousel-dots {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    z-index: 10;
    padding: 10px 20px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 25px;
    backdrop-filter: blur(5px);
}

.dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: 2px solid rgba(255, 255, 255, 0.8);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: scale(1.2);
}

.dot.active {
    background: var(--accent-color);
    border-color: var(--accent-color);
    width: 30px;
    border-radius: 8px;
}

/* Responsivo */
@media (min-width: 1920px) {
    .carousel-container {
        height: 934px;
    }
}

@media (max-width: 1919px) and (min-width: 1440px) {
    .carousel-container {
        height: 700px;
    }
}

@media (max-width: 1439px) and (min-width: 1280px) {
    .carousel-container {
        height: 622px;
    }
    
    .carousel-prev,
    .carousel-next {
        padding: 18px 22px;
        font-size: 26px;
    }
}

@media (max-width: 1279px) and (min-width: 1024px) {
    .carousel-container {
        height: 465px;
    }
    
    .carousel-prev,
    .carousel-next {
        padding: 16px 20px;
        font-size: 24px;
        left: 20px;
    }
    
    .carousel-next {
        right: 20px;
    }
    
    .carousel-dots {
        bottom: 30px;
        gap: 10px;
    }
    
    .dot {
        width: 12px;
        height: 12px;
    }
    
    .dot.active {
        width: 26px;
    }
}

@media (max-width: 1023px) and (min-width: 768px) {
    .carousel-container {
        height: 410px;
    }
    
    .carousel-prev,
    .carousel-next {
        padding: 14px 18px;
        font-size: 22px;
    }
    
    .carousel-prev {
        left: 15px;
    }
    
    .carousel-next {
        right: 15px;
    }
    
    .carousel-dots {
        bottom: 25px;
        gap: 10px;
    }
    
    .dot {
        width: 10px;
        height: 10px;
    }
    
    .dot.active {
        width: 24px;
    }
}

@media (max-width: 767px) and (min-width: 480px) {
    .carousel-container {
        height: 360px;
    }
    
    .carousel-prev,
    .carousel-next {
        padding: 10px 14px;
        font-size: 18px;
        background: rgba(0, 0, 0, 0.6);
    }
    
    .carousel-prev {
        left: 10px;
    }
    
    .carousel-next {
        right: 10px;
    }
    
    .carousel-dots {
        bottom: 20px;
        gap: 8px;
        padding: 8px 16px;
    }
    
    .dot {
        width: 8px;
        height: 8px;
        border-width: 1px;
    }
    
    .dot.active {
        width: 20px;
    }
}

@media (max-width: 479px) {
    .carousel-container {
        height: 280px;
    }
    
    .carousel-prev,
    .carousel-next {
        padding: 8px 12px;
        font-size: 16px;
        background: rgba(0, 0, 0, 0.7);
    }
    
    .carousel-prev {
        left: 8px;
    }
    
    .carousel-next {
        right: 8px;
    }
    
    .carousel-dots {
        bottom: 15px;
        gap: 6px;
        padding: 6px 12px;
    }
    
    .dot {
        width: 6px;
        height: 6px;
        border-width: 1px;
    }
    
    .dot.active {
        width: 18px;
    }
}

.product-image-placeholder {
    background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 10px;
}

.product-image-placeholder .icon {
    font-size: 64px;
    opacity: 0.3;
    filter: grayscale(1);
}

.product-image-placeholder .text {
    font-size: 12px;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.product-image {
    object-fit: contain;
    padding: 10px;
}
    </style>
</head>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const header = document.querySelector(".header");

  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });
});
</script>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="home.php" class="logo">NEXT<span>LEVEL</span></a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="home.php" <?php echo ($categoria_id == 0 && $pesquisa == '') ? 'class="active"' : ''; ?>>Todos</a></li>
                    <?php 
                    $display_limit = 4;
                    $count = 0;
                    foreach($categorias as $cat) { 
                        if($count >= $display_limit) break;
                        $active = ($categoria_id == $cat['codigo']) ? 'class="active"' : '';
                    ?>
                        <li><a href="<?php echo buildUrl(array('categoria' => $cat['codigo'], 'pagina' => '')); ?>" <?php echo $active; ?>><?php echo htmlspecialchars($cat['nome']); ?></a></li>
                    <?php 
                        $count++;
                    } 
                    ?>
                </ul>
            </nav>

            <div class="header-actions">
                <div class="search-box">
                    <form method="get" action="home.php">
                        <input name="pesquisa" type="text" placeholder="Buscar produtos..." value="<?php echo htmlspecialchars($pesquisa); ?>">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <?php if (isset($_SESSION['cliente_id'])): ?>
                <a class="icon-btn" href="logout_cliente.php?redirect=home.php" title="Sair">🚪</a>
                <?php else: ?>
                <a class="icon-btn" href="login_cliente.php?redirect=home.php" title="Entrar">👤</a>
                <?php endif; ?>
                <?php if (isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1')): ?>
                <a class="icon-btn" href="login_adm.php" title="Admin">🔑</a>
                <?php endif; ?>
                <a class="icon-btn" href="carrinho.php" title="Carrinho">
                    🛒
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                <button class="mobile-menu-btn">☰</button>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="carousel-container">
    <div class="carousel-slide">
        <img src="Assets/Banner1.jpg" alt="Banner 1 - NextLevel Tech">
        <img src="Assets/Banner2.jpg" alt="Banner 2 - NextLevel Tech">
        <img src="Assets/Banner3.jpg" alt="Banner 3 - NextLevel Tech">
        <img src="Assets/Banner4.jpg" alt="Banner 4 - NextLevel Tech">
        <img src="Assets/Banner5.jpg" alt="Banner 5 - NextLevel Tech">
    </div>

    <button class="carousel-prev" onclick="plusSlides(-1)">&#10094;</button>
    <button class="carousel-next" onclick="plusSlides(1)">&#10095;</button>

    <div class="carousel-dots">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
        <span class="dot" onclick="currentSlide(4)"></span>
        <span class="dot" onclick="currentSlide(5)"></span>
    </div>
</div>

<script src="assets/js/carousel.js"></script>

    <!-- Categories -->
    <?php if (!empty($categorias)): ?>
    <section class="categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Categorias</h2>
                <p class="section-subtitle">Encontre o equipamento perfeito para você</p>
            </div>
            <div class="categories-grid">
                <?php foreach ($categorias as $cat): 
                    $n = strtolower($cat['nome']); 
                    $icon = isset($category_icons[$n]) ? $category_icons[$n] : $category_icons['default'];
                    $active_class = ($categoria_id == $cat['codigo']) ? 'active' : '';
                ?>
                    <a class="category-card <?php echo $active_class; ?>" href="<?php echo buildUrl(array('categoria' => $cat['codigo'], 'pagina' => '')); ?>">
                        <span class="category-icon"><?php echo $icon; ?></span>
                        <div class="category-name"><?php echo htmlspecialchars($cat['nome']); ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Filters -->
    <section class="filters-section">
        <div class="filters-content">
            <div class="filter-group">
                <span class="filter-label">Ordenar:</span>
                <select onchange="window.location.href=this.value">
                    <option value="<?php echo buildUrl(array('ordem' => 'relevante', 'pagina' => '')); ?>" <?php echo $ordenacao == 'relevante' ? 'selected' : ''; ?>>Mais Relevantes</option>
                    <option value="<?php echo buildUrl(array('ordem' => 'menor_preco', 'pagina' => '')); ?>" <?php echo $ordenacao == 'menor_preco' ? 'selected' : ''; ?>>Menor Preço</option>
                    <option value="<?php echo buildUrl(array('ordem' => 'maior_preco', 'pagina' => '')); ?>" <?php echo $ordenacao == 'maior_preco' ? 'selected' : ''; ?>>Maior Preço</option>
                    <option value="<?php echo buildUrl(array('ordem' => 'nome', 'pagina' => '')); ?>" <?php echo $ordenacao == 'nome' ? 'selected' : ''; ?>>Nome A-Z</option>
                    <option value="<?php echo buildUrl(array('ordem' => 'lancamento', 'pagina' => '')); ?>" <?php echo $ordenacao == 'lancamento' ? 'selected' : ''; ?>>Lançamentos</option>
                </select>
            </div>
            
            <?php if (!empty($marcas)): ?>
            <div class="filter-group">
                <span class="filter-label">Marca:</span>
                <select onchange="window.location.href=this.value">
                    <option value="<?php echo buildUrl(array('marca' => '', 'pagina' => '')); ?>">Todas</option>
                    <?php foreach($marcas as $marca): ?>
                        <option value="<?php echo buildUrl(array('marca' => $marca['codigo'], 'pagina' => '')); ?>" <?php echo $marca_id == $marca['codigo'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($marca['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="filter-group">
                <span class="filter-label">Categoria:</span>
                <select onchange="window.location.href=this.value">
                    <option value="<?php echo buildUrl(array('categoria' => '', 'pagina' => '')); ?>">Todas</option>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?php echo buildUrl(array('categoria' => $cat['codigo'], 'pagina' => '')); ?>" <?php echo $categoria_id == $cat['codigo'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($categoria_id > 0 || $marca_id > 0 || $pesquisa != ''): ?>
                <button class="clear-filters" onclick="window.location.href='home.php'">Limpar Filtros</button>
            <?php endif; ?>

            <div class="results-info">
                <?php 
                $inicio = $total_produtos > 0 ? $offset + 1 : 0;
                $fim = min($offset + $produtos_por_pagina, $total_produtos);
                ?>
                Mostrando <?php echo $inicio; ?>-<?php echo $fim; ?> de <?php echo $total_produtos; ?> produtos
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="produtos">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <?php 
                    if ($pesquisa != '') {
                        echo 'Resultados para "' . htmlspecialchars($pesquisa) . '"';
                    } elseif ($categoria_id > 0) {
                        foreach($categorias as $cat) {
                            if ($cat['codigo'] == $categoria_id) {
                                echo htmlspecialchars($cat['nome']);
                                break;
                            }
                        }
                    } else {
                        echo 'Produtos em Destaque';
                    }
                    ?>
                </h2>
                <p class="section-subtitle">
                    <?php 
                    if ($pesquisa != '' || $categoria_id > 0) {
                        echo 'Encontramos ' . $total_produtos . ' produto(s) para você';
                    } else {
                        echo 'Equipamentos premium selecionados para você';
                    }
                    ?>
                </p>
            </div>

            <div class="products-grid">
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $p): ?>
                        <?php 
                        $estoque = isset($p['estoque']) ? (int)$p['estoque'] : 0;
                        $estoque_minimo = isset($p['estoque_minimo']) ? (int)$p['estoque_minimo'] : 5;
                        
                        if ($estoque <= 0) {
                            $badge = 'Indisponível';
                            $badge_class = 'out-of-stock';
                        } elseif ($estoque <= $estoque_minimo) {
                            $badge = 'Últimas Unidades';
                            $badge_class = 'low-stock';
                        } else {
                            $badge = 'Em Estoque';
                            $badge_class = '';
                        }
                        
                        $foto1 = isset($p['foto1']) ? trim($p['foto1']) : '';
                        $foto2 = isset($p['foto2']) ? trim($p['foto2']) : '';
                        $img = $foto1 !== '' ? $foto1 : $foto2;
                        
                        $dir_atual = dirname(__FILE__);
                        $dir_raiz = dirname($dir_atual);
                        $caminho_completo = $dir_raiz . DIRECTORY_SEPARATOR . 'produto' . DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR . $img;
                        $caminho_url = '../produto/fotos/' . htmlspecialchars($img);
                        $imagem_existe = ($img !== '' && file_exists($caminho_completo));
                        
                        $categoria_nome = strtolower($p['categoria_nome']);
                        $icone_categoria = '🎮';
                        
                        $icones = array(
                            'mouse' => '🖱️',
                            'teclado' => '⌨️',
                            'headset' => '🎧',
                            'monitor' => '🖥️',
                            'mousepad' => '🎯',
                            'cadeira' => '💺',
                            'microfone' => '🎤'
                        );
                        
                        foreach ($icones as $cat => $icone) {
                            if (strpos($categoria_nome, $cat) !== false) {
                                $icone_categoria = $icone;
                                break;
                            }
                        }
                        ?>
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <?php if ($imagem_existe): ?>
                                    <img src="<?php echo $caminho_url; ?>" 
                                         alt="<?php echo htmlspecialchars($p['nome']); ?>" 
                                         class="product-image"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="product-image-placeholder" style="display:none;">
                                        <div class="icon"><?php echo $icone_categoria; ?></div>
                                        <div class="text">Imagem indisponível</div>
                                    </div>
                                <?php else: ?>
                                    <div class="product-image-placeholder">
                                        <div class="icon"><?php echo $icone_categoria; ?></div>
                                        <div class="text">Sem imagem</div>
                                    </div>
                                <?php endif; ?>
                                <span class="product-badge <?php echo $badge_class; ?>"><?php echo $badge; ?></span>
                            </div>
                            <div class="product-info">
                                <div class="product-brand"><?php echo htmlspecialchars($p['marca_nome']); ?></div>
                                <div class="product-name" title="<?php echo htmlspecialchars($p['nome']); ?>">
                                    <?php echo htmlspecialchars($p['nome']); ?>
                                </div>
                                <div class="product-price">R$ <?php echo number_format((float)$p['preco'], 2, ',', '.'); ?></div>
                                <div class="product-actions">
                                    <?php if ($estoque > 0): ?>
                                        <a class="btn-add-cart" href="carrinho.php?action=add&id=<?php echo (int)$p['codigo']; ?>">
                                            Adicionar ao Carrinho
                                        </a>
                                    <?php else: ?>
                                        <button class="btn-add-cart" disabled>Indisponível</button>
                                    <?php endif; ?>
                                    <a class="btn-quick-view" href="produto.php?id=<?php echo (int)$p['codigo']; ?>" title="Ver detalhes">👁️</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">🔍</div>
                        <h3>Nenhum produto encontrado</h3>
                        <p>
                            <?php if ($pesquisa != ''): ?>
                                Não encontramos produtos para "<?php echo htmlspecialchars($pesquisa); ?>". Tente outros termos de busca.
                            <?php else: ?>
                                Não há produtos cadastrados nesta categoria no momento.
                            <?php endif; ?>
                        </p>
                        <br>
                        <a href="home.php" class="cta-btn">Ver Todos os Produtos</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_paginas > 1): ?>
            <div class="pagination">
                <?php if ($pagina > 1): ?>
                    <a href="<?php echo buildUrl(array('pagina' => $pagina - 1)); ?>" class="pagination-btn">← Anterior</a>
                <?php else: ?>
                    <span class="pagination-btn disabled">← Anterior</span>
                <?php endif; ?>
                
                <span class="pagination-info">Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?></span>
                
                <?php if ($pagina < $total_paginas): ?>
                    <a href="<?php echo buildUrl(array('pagina' => $pagina + 1)); ?>" class="pagination-btn">Próxima →</a>
                <?php else: ?>
                    <span class="pagination-btn disabled">Próxima →</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sobre Nós</h3>
                <ul>
                    <li><a href="#">Quem Somos</a></li>
                    <li><a href="#">Nossa História</a></li>
                    <li><a href="#">Trabalhe Conosco</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Atendimento</h3>
                <ul>
                    <li><a href="#">Central de Ajuda</a></li>
                    <li><a href="#">Trocas e Devoluções</a></li>
                    <li><a href="#">Garantia</a></li>
                    <li><a href="#">Rastreamento</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Política</h3>
                <ul>
                    <li><a href="#">Privacidade</a></li>
                    <li><a href="#">Termos de Uso</a></li>
                    <li><a href="#">Cookies</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Redes Sociais</h3>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">YouTube</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            © 2025 NextLevel Tech. Todos os direitos reservados. | CNPJ: 00.000.000/0001-00
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        document.querySelectorAll('.btn-add-cart').forEach(btn => {
            if (!btn.disabled) {
                btn.addEventListener('click', function(e) {
                    if (this.tagName === 'A') {
                        return true;
                    }
                    
                    const originalText = this.textContent;
                    const originalBg = this.style.background;
                    
                    this.textContent = '✓ Adicionado';
                    this.style.background = '#4caf50';
                    
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.style.background = originalBg;
                    }, 2000);
                });
            }
        });

        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.borderColor = 'var(--accent-color)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.borderColor = 'var(--border-color)';
            });
        });

        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navMenu = document.querySelector('.nav-menu');

        if (mobileMenuBtn && navMenu) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                navMenu.classList.toggle('active');
                
                if (navMenu.classList.contains('active')) {
                    this.textContent = '✕';
                } else {
                    this.textContent = '☰';
                }
            });

            document.addEventListener('click', function(e) {
                if (!navMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    navMenu.classList.remove('active');
                    if (mobileMenuBtn) {
                        mobileMenuBtn.textContent = '☰';
                    }
                }
            });
            
            navMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    if (mobileMenuBtn) {
                        mobileMenuBtn.textContent = '☰';
                    }
                });
            });
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && navMenu) {
                navMenu.classList.remove('active');
                if (mobileMenuBtn) {
                    mobileMenuBtn.textContent = '☰';
                }
            }
        });

        const cartIcon = document.querySelector('.icon-btn[href="carrinho.php"]');
        if (cartIcon) {
            const addButtons = document.querySelectorAll('.btn-add-cart[href*="carrinho.php"]');
            addButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    cartIcon.style.transform = 'scale(1.3)';
                    cartIcon.style.transition = 'transform 0.3s';
                    
                    setTimeout(() => {
                        cartIcon.style.transform = 'scale(1)';
                    }, 300);
                });
            });
        }

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>
</body>
</html>
<?php
if ($conn) {
    mysqli_close($conn);
}
?>