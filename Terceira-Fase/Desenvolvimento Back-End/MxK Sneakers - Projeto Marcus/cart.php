<?php
session_start();
$status="";

if (isset($_POST['action']) && $_POST['action']=="remove") {
  if(!empty($_SESSION["shopping_cart"])) {
    foreach($_SESSION["shopping_cart"] as $key => $value) {
      if(isset($_POST["codigo"]) && $_POST["codigo"] == $key){
        unset($_SESSION["shopping_cart"][$key]);
        $status = "<div class='box' style='color:white; background:#ff3b3f;'>
           Produto foi removido do carrinho!</div>";
      }
      if(empty($_SESSION["shopping_cart"]))
         unset($_SESSION["shopping_cart"]);
    }
  }
}

// Removemos a função de alteração de quantidade
?>
<!DOCTYPE html>
<html lang="pt-br">
<HEAD>
 <TITLE>MxK Sneakers - Carrinho</TITLE>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
 <style>
    /* Estilo compatível com a home */
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

    .cart-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .cart-title {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #333;
        border-bottom: 2px solid #ff5e62;
        padding-bottom: 0.5rem;
    }

    .cart {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background-color: #f8f8f8;
        font-weight: 600;
        color: #555;
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-right: 1rem;
        background: #f8f8f8;
        padding: 0.5rem;
        border-radius: 4px;
    }

    .product-details {
        display: flex;
        align-items: center;
    }

    .product-name {
        font-weight: 600;
        font-size: 1rem;
    }

    .remove-button {
        background: #ff5e62;
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

    .remove-button:hover {
        background: #ff3b3f;
    }

    .price {
        font-weight: 600;
        color: #555;
    }

    .total-row {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .total-price {
        color: #ff5e62;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .empty-cart {
        text-align: center;
        padding: 2rem;
        font-weight: 600;
        color: #777;
    }

    .continue-shopping {
        display: inline-block;
        background: #ff5e62;
        color: white;
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        font-weight: 700;
        border-radius: 6px;
        margin-top: 1rem;
        transition: background 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .continue-shopping:hover {
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

    .checkout-button {
        background: #2ecc71;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 700;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        float: right;
        margin-top: 1rem;
    }

    .checkout-button:hover {
        background: #27ae60;
    }

    @media (max-width: 768px) {
        th, td {
            padding: 0.75rem 0.5rem;
        }

        .product-image {
            width: 60px;
            height: 60px;
        }

        .product-name {
            font-size: 0.9rem;
        }

        .remove-button {
            padding: 0.4rem 0.8rem;
            font-size: 0.7rem;
        }
    }
 </style>
</HEAD>
<BODY>
    <header>
        <div class="logo-container">
            <a href="pesquisar_new.php"><img src="logo.png" alt="MxK Sneakers Logo" /></a>
        </div>
    </header>

    <div class="cart-container">
        <h1 class="cart-title">Seu Carrinho</h1>
        
        <div class="cart">
        <?php
        if(isset($_SESSION["shopping_cart"]) && !empty($_SESSION["shopping_cart"])){
            $total_price = 0;
        ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($_SESSION["shopping_cart"] as $key => $product){
                ?>
                    <tr>
                        <td>
                            <div class="product-details">
                                <?php if(isset($product["foto"])): ?>
                                    <img class="product-image" src="produto/fotos/<?php echo $product["foto"]; ?>" alt="<?php echo $product["descricao"]; ?>" />
                                <?php endif; ?>
                                <span class="product-name"><?php echo $product["descricao"]; ?></span>
                            </div>
                        </td>
                        <td class="price"><?php echo "R$ ".number_format($product["preco"], 2, ',', '.'); ?></td>
                        <td>
                            <form method='post' action=''>
                                <input type='hidden' name='codigo' value="<?php echo $key; ?>" />
                                <input type='hidden' name='action' value="remove" />
                                <button type='submit' class='remove-button'>Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php
                $total_price += $product["preco"];
                }
                ?>
                    <tr class="total-row">
                        <td colspan="1" align="right"><strong>TOTAL:</strong></td>
                        <td class="total-price"><?php echo "R$ ".number_format($total_price, 2, ',', '.'); ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            
            <button class="checkout-button">Finalizar Compra</button>
        <?php
        } else {
        ?>
            <div class="empty-cart">
                <h3>Seu carrinho está vazio!</h3>
                <a href="pesquisar_new.php" class="continue-shopping">Continuar Comprando</a>
            </div>
        <?php
        }
        ?>
        </div>

        <div class="message_box">
            <?php echo $status; ?>
        </div>
    </div>
</BODY>
</HTML>