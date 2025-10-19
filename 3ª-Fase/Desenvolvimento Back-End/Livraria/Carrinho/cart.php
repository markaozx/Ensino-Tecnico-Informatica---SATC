<?php
session_start();
$status = "";

// Lida com a remoção de um item
if (isset($_POST['action']) && $_POST['action'] == "remove") {
    if (!empty($_SESSION["shopping_cart"])) {
        $codigo_to_remove = $_POST["codigo"];

        // Verifica se o item existe e se sua quantidade é maior que 1
        // Adicionado isset($product['quantidade']) para evitar "Undefined index"
        if (isset($_SESSION["shopping_cart"][$codigo_to_remove]) && isset($_SESSION["shopping_cart"][$codigo_to_remove]['quantidade']) && $_SESSION["shopping_cart"][$codigo_to_remove]['quantidade'] > 1) {
            $_SESSION["shopping_cart"][$codigo_to_remove]['quantidade']--;
            $status = "<div class='box alert-info'>Uma unidade do produto foi removida!</div>";
        } else if (isset($_SESSION["shopping_cart"][$codigo_to_remove])) {
            // Se a quantidade for 1 ou menos, ou se 'quantidade' não estiver definida (para itens antigos), remove o item completo
            unset($_SESSION["shopping_cart"][$codigo_to_remove]);
            $status = "<div class='box alert-success'>Produto removido do carrinho!</div>";
        }

        // Se o carrinho ficar vazio após a remoção, limpa a variável de sessão
        if (empty($_SESSION["shopping_cart"])) {
            unset($_SESSION["shopping_cart"]);
        }
    }
}

// Lida com a ação de esvaziar o carrinho
if (isset($_POST['action']) && $_POST['action'] == "empty") {
    unset($_SESSION["shopping_cart"]); // Remove completamente a variável do carrinho
    $status = "<div class='box alert-warning'>🛒 Seu carrinho foi esvaziado!</div>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <TITLE>BookHub - Carrinho</TITLE>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #f5f5f5;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Novo estilo para o botão "Continuar Comprando" no cabeçalho */
        .header .continue-shopping-header {
            background-color: #4CAF50; /* Cor verde */
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .header .continue-shopping-header:hover {
            background-color: #45a049;
        }

        .main-content {
            padding: 20px;
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .cart-table th, .cart-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        .cart-table th {
            background-color: #f2f2f2;
            font-weight: 600;
            color: #555;
        }

        .cart-table img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 10px;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-info div {
            font-weight: 500;
        }

        .total-row td {
            font-weight: 700;
            background-color: #f9f9f9;
        }

        .total-price {
            color: #e60000;
            font-size: 1.1em;
        }

        .remove-button {
            background-color: #ff3b3f;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .remove-button:hover {
            background-color: #cc0000;
        }

        .checkout-button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
            flex-grow: 1;
        }

        .checkout-button:hover {
            background-color: #0056b3;
        }

        .empty-cart-button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
            flex-grow: 1;
        }

        .empty-cart-button:hover {
            background-color: #d32f2f;
        }


        .empty-cart {
            text-align: center;
            padding: 50px;
            border: 1px dashed #ccc;
            border-radius: 8px;
        }

        .empty-cart h3 {
            color: #555;
            margin-bottom: 20px;
        }

        /* Mantido para o estado de carrinho vazio */
        .empty-cart .continue-shopping {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .empty-cart .continue-shopping:hover {
            background-color: #45a049;
        }

        .message_box {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Meu Carrinho</h1>
        <a href="../Loja/home.php" class="continue-shopping-header">Continuar Comprando</a>
    </div>

    <div class="main-content">
        <h2>Seu Carrinho de Compras</h2>
        <?php
        if ($status) {
            echo "<div class='message_box " . (strpos($status, 'success') !== false ? 'alert-success' : (strpos($status, 'info') !== false ? 'alert-info' : (strpos($status, 'warning') !== false ? 'alert-warning' : 'alert-error'))) . "'>" . $status . "</div>";
        }

        if (!empty($_SESSION["shopping_cart"])) {
            $total_price = 0;
        ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($_SESSION["shopping_cart"] as $product) {
                        // Garante que 'quantidade' existe, caso contrário, assume 1 para evitar "Undefined index"
                        $quantity = isset($product["quantidade"]) ? $product["quantidade"] : 1;
                        $item_total = $product["preco"] * $quantity;
                    ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="../Livro/fotos/<?php echo htmlspecialchars($product["foto"]); ?>" alt="<?php echo htmlspecialchars($product["titulo"]); ?>" />
                                    <div><?php echo htmlspecialchars($product["titulo"]); ?></div>
                                </div>
                            </td>
                            <td>R$ <?php echo number_format($product["preco"], 2, ',', '.'); ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td>R$ <?php echo number_format($item_total, 2, ',', '.'); ?></td>
                            <td>
                                <form method='post' action=''>
                                    <input type='hidden' name='codigo' value="<?php echo htmlspecialchars($product['codigo']); ?>" />
                                    <input type='hidden' name='action' value="remove" />
                                    <button type='submit' class='remove-button'>Remover 1</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                        $total_price += $item_total;
                    }
                    ?>
                    <tr class="total-row">
                        <td colspan="3" align="right"><strong>TOTAL:</strong></td>
                        <td class="total-price">R$ <?php echo number_format($total_price, 2, ',', '.'); ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; gap: 15px; margin-top: 20px;">
                <form method='post' action='' style="flex-grow: 1;">
                    <input type='hidden' name='action' value="empty" />
                    <button type='submit' class='empty-cart-button'>Esvaziar Carrinho</button>
                </form>
                <button class="checkout-button">Finalizar Compra</button>
            </div>
        <?php
        } else {
        ?>
            <div class="empty-cart">
                <h3>Seu carrinho está vazio!</h3>
                <a href="../Loja/home.php" class="continue-shopping">Continuar Comprando</a>
            </div>
        <?php
        }
        ?>
    </div>
</body>
</html>