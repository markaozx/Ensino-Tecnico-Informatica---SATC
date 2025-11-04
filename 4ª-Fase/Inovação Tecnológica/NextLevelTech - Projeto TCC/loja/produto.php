<?php
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
mysqli_set_charset($conn, "latin1");

// Pegar ID do produto
$produto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($produto_id <= 0) {
    header("Location: home.php");
    exit;
}

// Buscar produto
$sql = "SELECT p.*, m.nome AS marca_nome, c.nome AS categoria_nome 
        FROM produto p 
        INNER JOIN marca m ON p.codmarca = m.codigo 
        INNER JOIN categoria c ON p.codcategoria = c.codigo 
        WHERE p.codigo = ? AND p.ativo = 1";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $produto_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produto = mysqli_fetch_assoc($result);

if (!$produto) {
    header("Location: home.php");
    exit;
}

// Buscar produtos relacionados (mesma categoria)
$sql_relacionados = "SELECT p.*, m.nome AS marca_nome 
                     FROM produto p 
                     INNER JOIN marca m ON p.codmarca = m.codigo 
                     WHERE p.codcategoria = ? AND p.codigo != ? AND p.ativo = 1 
                     ORDER BY RAND() 
                     LIMIT 4";

$stmt_rel = mysqli_prepare($conn, $sql_relacionados);
mysqli_stmt_bind_param($stmt_rel, "ii", $produto['codcategoria'], $produto_id);
mysqli_stmt_execute($stmt_rel);
$result_rel = mysqli_stmt_get_result($stmt_rel);

$produtos_relacionados = array();
while ($row = mysqli_fetch_assoc($result_rel)) {
    $produtos_relacionados[] = $row;
}

// Contar itens no carrinho
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['qty']) ? (int)$item['qty'] : 0;
    }
}

// Informações do produto
$estoque = isset($produto['estoque']) ? (int)$produto['estoque'] : 0;
$estoque_minimo = isset($produto['estoque_minimo']) ? (int)$produto['estoque_minimo'] : 5;

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

// Fotos do produto
$fotos = array();
if (!empty($produto['foto1'])) $fotos[] = $produto['foto1'];
if (!empty($produto['foto2'])) $fotos[] = $produto['foto2'];
if (!empty($produto['foto3'])) $fotos[] = $produto['foto3'];

// Caminho das fotos
$dir_atual = dirname(__FILE__);
$dir_raiz = dirname($dir_atual);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - NextLevel Tech</title>
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

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: rgba(255, 255, 255, 0.97);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: bold;
            color: #111;
            text-decoration: none;
        }

        .logo span {
            color: #ff3333;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .icon-btn {
            font-size: 20px;
            color: #111;
            text-decoration: none;
            transition: color 0.3s;
        }

        .icon-btn:hover {
            color: var(--accent-color);
        }

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

        .main-content {
            max-width: 1400px;
            margin: 100px auto 60px;
            padding: 0 40px;
        }

        .breadcrumb {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .breadcrumb a {
            color: var(--text-secondary);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: var(--accent-color);
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 80px;
        }

        .image-gallery {
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .main-image {
            width: 100%;
            height: 600px;
            background: var(--bg-gray);
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
            cursor: zoom-in;
            transition: transform 0.3s ease;
        }

        .thumbnail-gallery {
            display: flex;
            gap: 15px;
        }

        .thumbnail {
            width: 100px;
            height: 100px;
            background: var(--bg-gray);
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: var(--accent-color);
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 5px;
        }

        .no-image-placeholder {
            font-size: 48px;
            opacity: 0.3;
        }

        .product-info {
            padding-top: 20px;
        }

        .product-badge {
            display: inline-block;
            background: var(--accent-color);
            color: white;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 4px;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }

        .product-badge.out-of-stock {
            background: #999;
        }

        .product-badge.low-stock {
            background: #ff9800;
        }

        .product-brand {
            font-size: 14px;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .product-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .product-price {
            font-size: 42px;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 30px;
        }

        .product-description {
            font-size: 16px;
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .product-specs {
            margin-bottom: 30px;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 15px;
        }

        .spec-label {
            font-weight: 600;
            color: var(--text-primary);
        }

        .spec-value {
            color: var(--text-secondary);
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .quantity-label {
            font-weight: 600;
            font-size: 15px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
        }

        .qty-btn {
            background: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .qty-btn:hover {
            background: var(--bg-gray);
        }

        .qty-input {
            border: none;
            width: 60px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            padding: 10px 0;
            background: white;
            color: var(--text-primary);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-add-cart {
            flex: 1;
            padding: 18px 40px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }

        .btn-add-cart:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 0, 0.3);
        }

        .btn-add-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .btn-buy-now {
            padding: 18px 40px;
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }

        .btn-buy-now:hover {
            background: var(--primary-color);
            color: white;
        }

        .related-section {
            margin-top: 80px;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid var(--border-color);
            text-decoration: none;
            color: inherit;
            display: block;
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
            object-fit: contain;
            padding: 10px;
        }

        .product-card-info {
            padding: 20px;
        }

        .product-card-brand {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .product-card-name {
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

        .product-card-price {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-color);
        }

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

        @media (max-width: 768px) {
            .main-content {
                padding: 0 20px;
                margin-top: 80px;
            }

            .product-detail {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .image-gallery {
                position: relative;
                top: 0;
            }

            .main-image {
                height: 400px;
            }

            .product-title {
                font-size: 24px;
            }

            .product-price {
                font-size: 32px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="home.php" class="logo">NEXT<span>LEVEL</span></a>
            
            <div class="header-actions">
                <?php if (isset($_SESSION['cliente_id'])): ?>
                <a class="icon-btn" href="meus_pedidos.php" title="Meus Pedidos">📦</a>
                <a class="icon-btn" href="logout_cliente.php?redirect=home.php" title="Sair">🚪</a>
                <?php else: ?>
                <a class="icon-btn" href="login_cliente.php?redirect=produto.php?id=<?php echo $produto_id; ?>" title="Entrar">👤</a>
                <?php endif; ?>
                <a class="icon-btn" href="carrinho.php" title="Carrinho">
                    🛒
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="breadcrumb">
            <a href="home.php">Home</a>
            <span>/</span>
            <a href="home.php?categoria=<?php echo $produto['codcategoria']; ?>"><?php echo htmlspecialchars($produto['categoria_nome']); ?></a>
            <span>/</span>
            <span><?php echo htmlspecialchars($produto['nome']); ?></span>
        </div>

        <div class="product-detail">
            <div class="image-gallery">
                <div class="main-image" id="mainImage">
                    <?php if (!empty($fotos)): 
                        $foto_principal = $fotos[0];
                        $caminho_url = '../produto/fotos/' . htmlspecialchars($foto_principal);
                        $caminho_completo = $dir_raiz . DIRECTORY_SEPARATOR . 'produto' . DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR . $foto_principal;
                        if (file_exists($caminho_completo)):
                    ?>
                        <img src="<?php echo $caminho_url; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" id="mainImageImg">
                    <?php else: ?>
                        <div class="no-image-placeholder">🎮</div>
                    <?php endif; else: ?>
                        <div class="no-image-placeholder">🎮</div>
                    <?php endif; ?>
                </div>
                
                <?php if (count($fotos) > 1): ?>
                <div class="thumbnail-gallery">
                    <?php foreach ($fotos as $index => $foto): 
                        $caminho_url = '../produto/fotos/' . htmlspecialchars($foto);
                        $caminho_completo = $dir_raiz . DIRECTORY_SEPARATOR . 'produto' . DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR . $foto;
                        if (file_exists($caminho_completo)):
                    ?>
                        <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage('<?php echo $caminho_url; ?>', this)">
                            <img src="<?php echo $caminho_url; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        </div>
                    <?php endif; endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="product-info">
                <span class="product-badge <?php echo $badge_class; ?>"><?php echo $badge; ?></span>
                
                <div class="product-brand"><?php echo htmlspecialchars($produto['marca_nome']); ?></div>
                
                <h1 class="product-title"><?php echo htmlspecialchars($produto['nome']); ?></h1>
                
                <div class="product-price">R$ <?php echo number_format((float)$produto['preco'], 2, ',', '.'); ?></div>
                
                <?php if (!empty($produto['descricao'])): ?>
                <div class="product-description">
                    <?php echo nl2br(htmlspecialchars($produto['descricao'])); ?>
                </div>
                <?php endif; ?>

                <div class="product-specs">
                    <div class="spec-item">
                        <span class="spec-label">Marca</span>
                        <span class="spec-value"><?php echo htmlspecialchars($produto['marca_nome']); ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Categoria</span>
                        <span class="spec-value"><?php echo htmlspecialchars($produto['categoria_nome']); ?></span>
                    </div>
                    <?php if (!empty($produto['modelo'])): ?>
                    <div class="spec-item">
                        <span class="spec-label">Modelo</span>
                        <span class="spec-value"><?php echo htmlspecialchars($produto['modelo']); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="spec-item">
                        <span class="spec-label">Estoque</span>
                        <span class="spec-value"><?php echo $estoque; ?> unidade(s)</span>
                    </div>
                </div>

                <?php if ($estoque > 0): ?>
                <div class="quantity-selector">
                    <span class="quantity-label">Quantidade:</span>
                    <div class="quantity-controls">
                        <button class="qty-btn" onclick="decreaseQty()">−</button>
                        <input type="number" id="quantity" class="qty-input" value="1" min="1" max="<?php echo $estoque; ?>" readonly>
                        <button class="qty-btn" onclick="increaseQty()">+</button>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-add-cart" onclick="addToCart()">Adicionar ao Carrinho</button>
                    <button class="btn-buy-now" onclick="buyNow()">Comprar Agora</button>
                </div>
                <?php else: ?>
                <button class="btn-add-cart" disabled>Produto Indisponível</button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($produtos_relacionados)): ?>
        <section class="related-section">
            <h2 class="section-title">Produtos Relacionados</h2>
            <div class="products-grid">
                <?php foreach ($produtos_relacionados as $rel): 
                    $foto_rel = !empty($rel['foto1']) ? $rel['foto1'] : (!empty($rel['foto2']) ? $rel['foto2'] : '');
                    $caminho_url_rel = '../produto/fotos/' . htmlspecialchars($foto_rel);
                    $caminho_completo_rel = $dir_raiz . DIRECTORY_SEPARATOR . 'produto' . DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR . $foto_rel;
                    $imagem_existe_rel = ($foto_rel !== '' && file_exists($caminho_completo_rel));
                ?>
                    <a href="produto.php?id=<?php echo $rel['codigo']; ?>" class="product-card">
                        <div class="product-image-wrapper">
                            <?php if ($imagem_existe_rel): ?>
                                <img src="<?php echo $caminho_url_rel; ?>" alt="<?php echo htmlspecialchars($rel['nome']); ?>" class="product-image">
                            <?php else: ?>
                                <div class="no-image-placeholder" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">🎮</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-info">
                            <div class="product-card-brand"><?php echo htmlspecialchars($rel['marca_nome']); ?></div>
                            <div class="product-card-name"><?php echo htmlspecialchars($rel['nome']); ?></div>
                            <div class="product-card-price">R$ <?php echo number_format((float)$rel['preco'], 2, ',', '.'); ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

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
        const maxQty = <?php echo $estoque; ?>;
        const productId = <?php echo $produto_id; ?>;

        function increaseQty() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue < maxQty) {
                input.value = currentValue + 1;
            }
        }

        function decreaseQty() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        function changeImage(imageSrc, thumbnail) {
            const mainImg = document.getElementById('mainImageImg');
            if (mainImg) {
                mainImg.src = imageSrc;
            }
            
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        }

        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            window.location.href = `carrinho.php?action=add&id=${productId}&qty=${quantity}`;
        }

        function buyNow() {
            const quantity = document.getElementById('quantity').value;
            window.location.href = `carrinho.php?action=add&id=${productId}&qty=${quantity}&buy=1`;
        }

        const mainImage = document.getElementById('mainImageImg');
        if (mainImage) {
            mainImage.addEventListener('click', function() {
                this.style.transform = this.style.transform === 'scale(1.5)' ? 'scale(1)' : 'scale(1.5)';
                this.style.cursor = this.style.transform === 'scale(1.5)' ? 'zoom-out' : 'zoom-in';
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