<?php
session_start();
$connect = mysqli_connect('localhost','root','','loja');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$status = "";
if (isset($_POST['codigo']) && $_POST['codigo'] != "") {
    $codigo = $_POST['codigo'];
    $resultado = mysqli_query($connect, "SELECT descricao, preco, foto1 FROM produto WHERE codigo = '$codigo'");
    $row = mysqli_fetch_assoc($resultado);
    $descricao = $row['descricao'];
    $preco = $row['preco'];
    $foto1 = $row['foto1'];

    // Cada produto é adicionado como um novo item, sem opção de quantidade
    $cartArray = array($codigo => array('descricao' => $descricao, 'preco' => $preco, 'foto' => $foto1));

    if (empty($_SESSION["shopping_cart"])) {
        $_SESSION["shopping_cart"] = $cartArray;
        $status = "<div class='box'>Produto foi adicionado ao carrinho!</div>";
    } else {
        // Sempre adiciona o produto ao carrinho, sem verificar se já existe
        $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $cartArray);
        $status = "<div class='box'>Produto foi adicionado ao carrinho!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MxK Sneakers</title>
    <link rel="stylesheet" href="styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        /* Custom styles to mimic lojaover.com.br */

        body {
            background-color: #f5f5f5;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-container img {
            height: 60px;
            width: auto;
        }

        .login-container img {
            height: 40px;
            width: auto;
        }

        .cart_div {
            position: fixed;
            top: 80px;
            right: 20px;
            background: #ff5e62;
            color: white;
            padding: 10px 15px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(255, 94, 98, 0.4);
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1001;
        }

        .cart_div a {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
        }

        .cart_div img {
            height: 24px;
            width: 24px;
        }

        .cart_div span {
            background: #fff;
            color: #ff5e62;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.9rem;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            display: flex;
            gap: 2rem;
            padding-left: 20px; /* Move filter sidebar more to the left */
        }

        .filter-sidebar {
            width: 250px;
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .filter-sidebar h2 {
            margin-bottom: 1rem;
            font-weight: 700;
            font-size: 1.2rem;
            border-bottom: 2px solid #ff5e62;
            padding-bottom: 0.5rem;
        }

        .filter-group {
            margin-bottom: 1rem;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }

        .filter-group select {
            width: 100%;
            padding: 0.5rem;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .filter-group button {
            width: 100%;
            background: #ff5e62;
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .filter-group button:hover {
            background: #ff3b3f;
        }

        .products-section {
            flex: 1;
        }

        .products-header {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .products-header h2 {
            margin: 0;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: box-shadow 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            box-shadow: 0 4px 16px rgb(0 0 0 / 0.2);
        }

        .product-image {
            width: 100%;
            height: 180px;
            margin-bottom: 1rem;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            position: absolute;
            top: 0;
            left: 0;
            transition: opacity 0.3s ease;
        }

        .product-image img.second-image {
            opacity: 0;
        }

        .product-card:hover .product-image img.first-image {
            opacity: 0;
        }

        .product-card:hover .product-image img.second-image {
            opacity: 1;
        }

        .product-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #222;
        }

        .product-price {
            font-weight: 700;
            color: #ff5e62;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .buy-button {
            background: #ff5e62;
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .buy-button:hover {
            background: #ff3b3f;
        }

        .box {
            margin-top: 1rem;
            padding: 1rem;
            background: #ff5e62;
            color: white;
            border-radius: 6px;
            font-weight: 700;
            text-align: center;
        }

        .box[style*="background:#ff3b3f"] {
            background: #ff3b3f;
        }
        
        .clear-cart-button {
            background: #555;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
        }
        
        .clear-cart-button:hover {
            background: #333;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                margin: 1rem;
                padding-left: 0;
            }

            .filter-sidebar {
                position: static;
                width: 100%;
                margin-bottom: 1rem;
                top: auto;
            }

            .cart_div {
                position: fixed;
                top: 10px;
                right: 10px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="pesquisar_new.php"><img src="logo.png" alt="MxK Sneakers Logo" /></a>
        </div>
        <div class="login-container">
            <a href="login/login.html"><img src="login.png" alt="Login" /></a>
        </div>
    </header>

    <?php
    if (!empty($_SESSION["shopping_cart"])) {
        $cart_count = count(array_keys($_SESSION["shopping_cart"]));
    ?>
        <div class="cart_div">
            <a href="cart.php"><img src="carrinho.png" alt="Carrinho" /> Carrinho <span><?php echo $cart_count; ?></span></a>
        </div>
    <?php
    }
    ?>

    <div class="container">
        <aside class="filter-sidebar">
            <h2>Filtrar Produtos</h2>
            <form method="post" action="pesquisar_new.php">
                <div class="filter-group">
                    <label for="categoria">Categorias</label>
                    <select name="categoria" id="categoria">
                        <option value="" selected>Selecione...</option>
                        <?php
                        $query = mysqli_query($connect, "SELECT codigo, nome FROM categoria");
                        while ($categorias = mysqli_fetch_array($query)) {
                            echo '<option value="' . $categorias['codigo'] . '">' . $categorias['nome'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo">
                        <option value="" selected>Selecione...</option>
                        <?php
                        $query = mysqli_query($connect, "SELECT codigo, nome FROM tipo");
                        while ($tipo = mysqli_fetch_array($query)) {
                            echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nome'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="marca">Marcas</label>
                    <select name="marca" id="marca">
                        <option value="" selected>Selecione...</option>
                        <?php
                        $query = mysqli_query($connect, "SELECT codigo, nome FROM marca");
                        while ($marcas = mysqli_fetch_array($query)) {
                            echo '<option value="' . $marcas['codigo'] . '">' . $marcas['nome'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" name="pesquisar">Pesquisar</button>
                </div>
            </form>
        </aside>

        <main class="products-section">
            <?php
            // Verificamos se foi realizada uma pesquisa com filtros
            if (isset($_POST['pesquisar'])) {
                $marca = empty($_POST['marca']) ? null : $_POST['marca'];
                $categoria = empty($_POST['categoria']) ? null : $_POST['categoria'];
                $tipo = empty($_POST['tipo']) ? null : $_POST['tipo'];

                $sql = "SELECT p.codigo, p.descricao, p.cor, p.tamanho, p.preco, p.foto1, p.foto2,
                               m.nome as marca_nome, c.nome as categoria_nome, t.nome as tipo_nome
                        FROM produto p
                        JOIN marca m ON p.codmarca = m.codigo
                        JOIN categoria c ON p.codcategoria = c.codigo
                        JOIN tipo t ON p.codtipo = t.codigo
                        WHERE 1=1";

                if ($marca) {
                    $sql .= " AND p.codmarca = $marca";
                }
                if ($categoria) {
                    $sql .= " AND p.codcategoria = $categoria";
                }
                if ($tipo) {
                    $sql .= " AND p.codtipo = $tipo";
                }

                $result = mysqli_query($connect, $sql);

                if (mysqli_num_rows($result) == 0) {
                    echo '<div class="box" style="background:#ff3b3f;">Desculpe, mas sua busca não retornou resultados...</div>';
                } else {
                    echo '<div class="products-header">';
                    echo '<h2>Produtos filtrados</h2>';
                    echo '</div>';
                    echo '<div class="products-grid">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="product-card">';
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="codigo" value="' . $row['codigo'] . '" />';
                        echo '<div class="product-image">';
                        echo '<img class="first-image" src="produto/fotos/' . $row['foto1'] . '" alt="' . htmlspecialchars($row['descricao']) . '" />';
                        echo '<img class="second-image" src="produto/fotos/' . $row['foto2'] . '" alt="' . htmlspecialchars($row['descricao']) . '" />';
                        echo '</div>';
                        echo '<div class="product-name">' . htmlspecialchars($row['descricao']) . '</div>';
                        echo '<div class="product-price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</div>';
                        echo '<button type="submit" class="buy-button">COMPRAR</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                // Se não houver pesquisa, carrega todos os produtos automaticamente
                $sql = "SELECT p.codigo, p.descricao, p.cor, p.tamanho, p.preco, p.foto1, p.foto2,
                               m.nome as marca_nome, c.nome as categoria_nome, t.nome as tipo_nome
                        FROM produto p
                        JOIN marca m ON p.codmarca = m.codigo
                        JOIN categoria c ON p.codcategoria = c.codigo
                        JOIN tipo t ON p.codtipo = t.codigo";
                
                $result = mysqli_query($connect, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="products-header">';
                    echo '<h2>Todos os produtos</h2>';
                    echo '</div>';
                    
                    echo '<div class="products-grid">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="product-card">';
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="codigo" value="' . $row['codigo'] . '" />';
                        echo '<div class="product-image">';
                        echo '<img class="first-image" src="produto/fotos/' . $row['foto1'] . '" alt="' . htmlspecialchars($row['descricao']) . '" />';
                        echo '<img class="second-image" src="produto/fotos/' . $row['foto2'] . '" alt="' . htmlspecialchars($row['descricao']) . '" />';
                        echo '</div>';
                        echo '<div class="product-name">' . htmlspecialchars($row['descricao']) . '</div>';
                        echo '<div class="product-price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</div>';
                        echo '<button type="submit" class="buy-button">COMPRAR</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="box" style="background:#ff3b3f;">Não há produtos cadastrados.</div>';
                }
            }
            ?>
        </main>
    </div>

    <?php
    if (!empty($status)) {
        echo '<div class="box">' . $status . '</div>';
    }
    ?>

    <footer>
        <p>© 2025 Street Sport - Todos os direitos reservados</p>
    </footer>
</body>

</html>