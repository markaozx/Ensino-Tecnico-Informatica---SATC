<?php
session_start();
$connect = mysqli_connect('localhost','root','','livraria');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$status = "";
if (isset($_POST['codigo']) && $_POST['codigo'] != "") {
    $codigo = $_POST['codigo'];
    $resultado = mysqli_query($connect, "SELECT titulo, preco, fotocapa1 FROM livro WHERE codigo = '$codigo'");
    $row = mysqli_fetch_assoc($resultado);
    $titulo = $row['titulo'];
    $preco = $row['preco'];
    $fotocapa1 = $row['fotocapa1'];

    // Inicializa o carrinho se ainda não existir na sessão
    if (empty($_SESSION["shopping_cart"])) {
        $_SESSION["shopping_cart"] = array();
    }

    // Verifica se o produto JÁ ESTÁ no carrinho
    if (array_key_exists($codigo, $_SESSION["shopping_cart"])) {
        // Se o produto já está no carrinho, INCREMENTA a quantidade
        $_SESSION["shopping_cart"][$codigo]['quantidade']++;
        $status = "<div class='alert-success'>📚 Quantidade do livro atualizada no carrinho!</div>";
    } else {
        // Se o produto NÃO está no carrinho, adiciona-o com quantidade 1
        $_SESSION["shopping_cart"][$codigo] = array(
            'titulo' => $titulo,
            'preco' => $preco,
            'foto' => $fotocapa1,
            'quantidade' => 1 // Define a quantidade inicial como 1
        );
        $status = "<div class='alert-success'>📚 Livro adicionado ao carrinho com sucesso!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BookHub - Sua Livraria Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #ec4899;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, #e0e7ff 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: var(--bg-primary);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-container h1 {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .login-container a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-md);
        }

        .login-container a:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Cart Button */
        .cart_div {
            position: fixed;
            top: 100px;
            right: 2rem;
            z-index: 1001;
            animation: slideInRight 0.5s ease-out;
        }

        .cart_div a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-xl);
            font-weight: 600;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .cart_div a:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-xl);
        }

        .cart_div span {
            background: white;
            color: var(--success-color);
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 0.875rem;
            font-weight: 700;
            min-width: 24px;
            text-align: center;
        }

        /* Banner Slider */
        .banner-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
        }

        .banner-slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 200px; /* Altura padrão para telas maiores */
            width: 100%;
        }

        .banner-slide {
            min-width: 100%;
            flex-shrink: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .banner-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .banner-indicators {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.75rem;
            z-index: 10;
        }

        .banner-indicators .indicator {
            width: 12px;
            height: 12px;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .banner-indicators .indicator.active {
            background-color: var(--primary-color);
            transform: scale(1.2);
            border-color: var(--primary-color);
        }

        /* Container and Layout */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 3rem;
            align-items: start;
        }

        /* Filter Sidebar */
        .filter-sidebar {
            background: var(--bg-primary);
            border-radius: var(--radius-xl);
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 120px;
            border: 1px solid var(--border-color);
        }

        .filter-sidebar h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .filter-sidebar h2::before {
            content: '🔍';
            font-size: 1.2rem;
        }

        .filter-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .filter-group label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .filter-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 1rem;
            color: var(--text-primary);
            background: var(--bg-primary);
            transition: all 0.3s ease;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
        }

        .filter-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: var(--shadow-md);
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .filter-btn:active {
            transform: translateY(0);
        }

        /* Products Section */
        .products-section {
            min-height: 500px;
        }

        .products-header {
            margin-bottom: 2rem;
        }

        .products-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .products-header h2::before {
            content: '📚';
            font-size: 1.5rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        /* Product Cards */
        .product-card {
            background: var(--bg-primary);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .product-image {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--bg-secondary), var(--bg-tertiary));
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .product-image img.second-image {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }

        .product-card:hover .product-image img.first-image {
            opacity: 0;
            transform: scale(1.1);
        }

        .product-card:hover .product-image img.second-image {
            opacity: 1;
            transform: scale(1.05);
        }

        .product-content {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }

        .product-author,
        .product-category,
        .product-publisher {
            font-size: 0.875rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .product-author::before { content: '👤'; }
        .product-category::before { content: '📂'; }
        .product-publisher::before { content: '🏢'; }

        .product-price {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--success-color), #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }

        .buy-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent-color), #d97706);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .buy-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        .buy-button::after {
            content: '🛒';
            font-size: 1rem;
        }

        /* Alerts */
        .alert-success {
            position: fixed;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 1rem 2rem;
            border-radius: var(--radius-lg);
            font-weight: 600;
            box-shadow: var(--shadow-xl);
            z-index: 1002;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-error {
            background: linear-gradient(135deg, var(--error-color), #dc2626);
            color: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            font-weight: 600;
            text-align: center;
            margin: 2rem 0;
            box-shadow: var(--shadow-lg);
        }

        /* Footer */
        footer {
            background: var(--text-primary);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }

        /* Animations */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .products-grid {
            animation: fadeIn 0.6s ease-out;
        }

        /* Responsive Design */
        /* Media Queries para o banner */
        @media (max-width: 1400px) {
            .banner-slider {
                height: 180px; /* Reduz a altura para telas médias */
            }
        }

        @media (max-width: 1200px) {
            .banner-slider {
                height: 160px; /* Mais redução de altura */
            }
        }

        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 1.5rem;
            }

            .filter-sidebar {
                position: static;
                order: -1;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }

            .banner-slider {
                height: 140px; /* Mais redução de altura para tablets grandes */
            }
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 1rem;
            }

            .logo-container h1 {
                font-size: 1.5rem;
            }

            .cart_div {
                top: 80px;
                right: 1rem;
            }

            .cart_div a {
                padding: 12px 16px;
                font-size: 0.875rem;
            }

            .container {
                padding: 1rem;
                gap: 1.5rem;
            }

            .filter-sidebar {
                padding: 1.5rem;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .product-content {
                padding: 1rem;
            }

            .banner-slider {
                height: 100px; /* Altura menor para tablets */
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .filter-sidebar {
                padding: 1rem;
            }

            .banner-slider {
                height: 80px; /* Altura mínima para banners em mobile */
            }
        }

        /* Loading State */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            font-size: 1.2rem;
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--text-light);
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h1>BookHub</h1>
            </div>
            <div class="login-container">
                <a href="../Login/login.php">
                    <i class="fas fa-user"></i>
                    Login
                </a>
            </div>
        </div>
    </header>
    <div class="banner-container">
        <div class="banner-slider">
            <div class="banner-slide active">
                <img src="../Assets/banner1.webp" alt="Banner Promocional 1">
            </div>
            <div class="banner-slide">
                <img src="../Assets/banner2.png" alt="Banner Promocional 2">
            </div>
            <div class="banner-slide">
                <img src="../Assets/banner3.webp" alt="Banner Promocional 3">
            </div>
            <div class="banner-slide">
                <img src="../Assets/banner4.webp" alt="Banner Promocional 4">
            </div>
            <div class="banner-slide">
                <img src="../Assets/banner5.webp" alt="Banner Promocional 5">
            </div>
            <div class="banner-slide">
                <img src="../Assets/banner6.webp" alt="Banner Promocional 6">
            </div>
        </div>
        <div class="banner-indicators">
            <span class="indicator active" data-slide="0"></span>
            <span class="indicator" data-slide="1"></span>
            <span class="indicator" data-slide="2"></span>
            <span class="indicator" data-slide="3"></span>
            <span class="indicator" data-slide="4"></span>
            <span class="indicator" data-slide="5"></span>
        </div>
    </div>

    <?php
    if (!empty($_SESSION["shopping_cart"])) {
        $cart_count = 0;
        foreach ($_SESSION["shopping_cart"] as $item) {
            // Garante que 'quantidade' existe ao contar os itens no carrinho
            if (isset($item['quantidade'])) {
                $cart_count += $item['quantidade'];
            } else {
                $cart_count += 1; // Fallback para itens antigos sem 'quantidade'
            }
        }
    ?>
        <div class="cart_div">
            <a href="../Carrinho/cart.php">
                <i class="fas fa-shopping-cart"></i>
                Carrinho
                <span><?php echo $cart_count; ?></span>
            </a>
        </div>
    <?php
    }
    ?>

    <div class="container">
        <aside class="filter-sidebar">
            <h2>Filtrar Livros</h2>
            <form method="post" action="" class="filter-form">
                <div class="filter-group">
                    <label for="categoria">Categoria</label>
                    <select name="categoria" id="categoria">
                        <option value="" selected>Todas as categorias</option>
                        <?php
                        $query = mysqli_query($connect, "SELECT codigo, nome FROM categoria");
                        while ($categorias = mysqli_fetch_array($query)) {
                            echo '<option value="' . htmlspecialchars($categorias['codigo']) . '">' . htmlspecialchars($categorias['nome']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="autor">Autor</label>
                    <select name="autor" id="autor">
                        <option value="" selected>Todos os autores</option>
                        <?php
                        $query = mysqli_query($connect, "SELECT codigo, nome FROM autor");
                        while ($autores = mysqli_fetch_array($query)) {
                            echo '<option value="' . htmlspecialchars($autores['codigo']) . '">' . htmlspecialchars($autores['nome']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="editora">Editora</label>
                    <select name="editora" id="editora">
                        <option value="" selected>Todas as editoras</option>
                        <?php
                        $query = mysqli_query($connect, "SELECT codigo, nome FROM editora");
                        while ($editoras = mysqli_fetch_array($query)) {
                            echo '<option value="' . htmlspecialchars($editoras['codigo']) . '">' . htmlspecialchars($editoras['nome']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" name="filtrar" class="filter-btn">
                        <i class="fas fa-search"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </aside>

        <main class="products-section">
            <?php
            // Verifica se foi enviado o filtro
            if (isset($_POST['filtrar'])) {
                $categoria = empty($_POST['categoria']) ? null : $_POST['categoria'];
                $autor = empty($_POST['autor']) ? null : $_POST['autor'];
                $editora = empty($_POST['editora']) ? null : $_POST['editora'];

                $sql = "SELECT l.codigo, l.titulo, l.preco, l.fotocapa1, l.fotocapa2, a.nome AS autor_nome, c.nome AS categoria_nome, e.nome AS editora_nome
                        FROM livro l
                        JOIN autor a ON l.codautor = a.codigo
                        JOIN categoria c ON l.codcategoria = c.codigo
                        JOIN editora e ON l.codeditora = e.codigo
                        WHERE 1=1";

                if ($categoria) {
                    $sql .= " AND l.codcategoria = " . intval($categoria);
                }
                if ($autor) {
                    $sql .= " AND l.codautor = " . intval($autor);
                }
                if ($editora) {
                    $sql .= " AND l.codeditora = " . intval($editora);
                }

                $result = mysqli_query($connect, $sql);

                if (mysqli_num_rows($result) == 0) {
                    echo '<div class="empty-state">';
                    echo '<i class="fas fa-search"></i>';
                    echo '<h3>Nenhum livro encontrado</h3>';
                    echo '<p>Tente ajustar os filtros para encontrar o que procura.</p>';
                    echo '</div>';
                } else {
                    echo '<div class="products-header">';
                    echo '<h2>Livros filtrados</h2>';
                    echo '</div>';
                    echo '<div class="products-grid">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="product-card">';
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="codigo" value="' . htmlspecialchars($row['codigo']) . '" />';
                        echo '<div class="product-image">';
                        echo '<img class="first-image" src="../Livro/fotos/' . htmlspecialchars($row['fotocapa1']) . '" alt="' . htmlspecialchars($row['titulo']) . '" />';
                        echo '<img class="second-image" src="../Livro/fotos/' . htmlspecialchars($row['fotocapa2']) . '" alt="' . htmlspecialchars($row['titulo']) . '" />';
                        echo '</div>';
                        echo '<div class="product-content">';
                        echo '<div class="product-name">' . htmlspecialchars($row['titulo']) . '</div>';
                        echo '<div class="product-details">';
                        echo '<div class="product-author">Autor: ' . htmlspecialchars($row['autor_nome']) . '</div>';
                        echo '<div class="product-category">Categoria: ' . htmlspecialchars($row['categoria_nome']) . '</div>';
                        echo '<div class="product-publisher">Editora: ' . htmlspecialchars($row['editora_nome']) . '</div>';
                        echo '</div>';
                        echo '<div class="product-price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</div>';
                        echo '<button type="submit" class="buy-button">Comprar</button>';
                        echo '</div>';
                        echo '</form>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                // Se não houver filtro, carrega todos os livros automaticamente
                $sql = "SELECT l.codigo, l.titulo, l.preco, l.fotocapa1, l.fotocapa2, a.nome AS autor_nome, c.nome AS categoria_nome, e.nome AS editora_nome
                        FROM livro l
                        JOIN autor a ON l.codautor = a.codigo
                        JOIN categoria c ON l.codcategoria = c.codigo
                        JOIN editora e ON l.codeditora = e.codigo";

                $result = mysqli_query($connect, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="products-header">';
                    echo '<h2>Todos os livros</h2>';
                    echo '</div>';

                    echo '<div class="products-grid">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="product-card">';
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="codigo" value="' . htmlspecialchars($row['codigo']) . '" />';
                        echo '<div class="product-image">';
                        echo '<img class="first-image" src="../Livro/fotos/' . htmlspecialchars($row['fotocapa1']) . '" alt="' . htmlspecialchars($row['titulo']) . '" />';
                        echo '<img class="second-image" src="../Livro/fotos/' . htmlspecialchars($row['fotocapa2']) . '" alt="' . htmlspecialchars($row['titulo']) . '" />';
                        echo '</div>';
                        echo '<div class="product-content">';
                        echo '<div class="product-name">' . htmlspecialchars($row['titulo']) . '</div>';
                        echo '<div class="product-details">';
                        echo '<div class="product-author">Autor: ' . htmlspecialchars($row['autor_nome']) . '</div>';
                        echo '<div class="product-category">Categoria: ' . htmlspecialchars($row['categoria_nome']) . '</div>';
                        echo '<div class="product-publisher">Editora: ' . htmlspecialchars($row['editora_nome']) . '</div>';
                        echo '</div>';
                        echo '<div class="product-price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</div>';
                        echo '<button type="submit" class="buy-button">Comprar</button>';
                        echo '</div>';
                        echo '</form>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="empty-state">';
                    echo '<i class="fas fa-book"></i>';
                    echo '<h3>Nenhum livro cadastrado</h3>';
                    echo '<p>Não há livros disponíveis no momento.</p>';
                    echo '</div>';
                }
            }
            ?>
        </main>
    </div>

    <?php
    if (!empty($status)) {
        echo $status;
    }
    ?>

    <footer>
        <p>© 2025 BookHub - Todos os direitos reservados | Sua livraria digital de confiança</p>
    </footer>

    <script>
        // Auto-hide success messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-success');
            alerts.forEach(alert => {
                alert.style.animation = 'slideInDown 0.5s ease-out reverse';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);

        // Smooth scroll for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.animation = 'fadeIn 0.6s ease-out both';
            });

            // Banner Slider functionality
            const bannerSlider = document.querySelector('.banner-slider');
            const indicatorsContainer = document.querySelector('.banner-indicators');
            const slides = document.querySelectorAll('.banner-slide');
            const indicators = document.querySelectorAll('.indicator');
            let currentSlide = 0;
            const slideInterval = 6000; // 6 seconds

            function goToSlide(index) {
                if (index < 0) {
                    index = slides.length - 1;
                } else if (index >= slides.length) {
                    index = 0;
                }
                // Ajusta a translação baseada na largura total do bannerSlider
                // Multiplica pela largura de um slide (que é 100% do bannerSlider)
                bannerSlider.style.transform = `translateX(-${index * 100}%)`;
                indicators.forEach((indicator, i) => {
                    if (i === index) {
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.remove('active');
                    }
                });
                currentSlide = index;
            }

            function nextSlide() {
                goToSlide(currentSlide + 1);
            }

            // Initial setup
            goToSlide(0);

            // Auto slide
            let autoSlide = setInterval(nextSlide, slideInterval);

            // Manual navigation with indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    clearInterval(autoSlide); // Stop auto-slide on manual navigation
                    goToSlide(index);
                    autoSlide = setInterval(nextSlide, slideInterval); // Restart auto-slide
                });
            });
        });
    </script>
</body>

</html>