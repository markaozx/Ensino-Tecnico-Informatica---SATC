<?php
// Sessão (para cliente) - compatível com PHP 5.3
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

// ==== CONFIGURAÇÃO BANCO DE DADOS ====
$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "ecommerce_perifericos";

// ==== CONEXÃO ====
$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

// Verificar conexão
if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Definir charset (alinhado ao dump latin1)
mysqli_set_charset($conn, "latin1");

$msg = '';
$msg_tipo = '';

// Inicializar carrinho se não existir
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// ==== Funções de persistência do carrinho ====
function ensureUserCart($conn, $usuarioId) {
    $usuarioId = (int)$usuarioId;
    if ($usuarioId <= 0) return 0;
    $sql = "SELECT codigo FROM carrinho WHERE usuario_id = $usuarioId LIMIT 1";
    $rs = mysqli_query($conn, $sql);
    if ($rs && mysqli_num_rows($rs) > 0) {
        $row = mysqli_fetch_assoc($rs);
        return (int)$row['codigo'];
    }
    $now = date('Y-m-d H:i:s');
    $ins = "INSERT INTO carrinho (usuario_id, criado_em) VALUES ($usuarioId, '$now')";
    if (mysqli_query($conn, $ins)) {
        return (int)mysqli_insert_id($conn);
    }
    return 0;
}

function saveSessionCartToDb($conn) {
    if (!isset($_SESSION['cliente_id'])) return;
    $usuarioId = (int)$_SESSION['cliente_id'];
    if ($usuarioId <= 0) return;
    $carrinhoId = ensureUserCart($conn, $usuarioId);
    if ($carrinhoId <= 0) return;
    // Limpa itens existentes e regrava conforme sessão
    mysqli_query($conn, "DELETE FROM carrinho_item WHERE carrinho_id = $carrinhoId");
    foreach ($_SESSION['cart'] as $produtoId => $item) {
        $produtoId = (int)$produtoId;
        $qty = (int)$item['qty'];
        if ($produtoId <= 0 || $qty <= 0) continue;
        $preco = (float)$item['preco'];
        $ins = "INSERT INTO carrinho_item (carrinho_id, produto_id, quantidade, preco_unitario) VALUES ($carrinhoId, $produtoId, $qty, $preco)";
        mysqli_query($conn, $ins);
    }
}

function loadDbCartToSession($conn) {
    if (!isset($_SESSION['cliente_id'])) return;
    $usuarioId = (int)$_SESSION['cliente_id'];
    if ($usuarioId <= 0) return;
    $sql = "SELECT ca.codigo AS carrinho_id FROM carrinho ca WHERE ca.usuario_id = $usuarioId LIMIT 1";
    $rs = mysqli_query($conn, $sql);
    if (!$rs || mysqli_num_rows($rs) === 0) return;
    $row = mysqli_fetch_assoc($rs);
    $carrinhoId = (int)$row['carrinho_id'];
    if ($carrinhoId <= 0) return;
    $sqlItems = "SELECT ci.produto_id, ci.quantidade, ci.preco_unitario, p.nome, p.estoque, p.foto1
                 FROM carrinho_item ci
                 JOIN produto p ON p.codigo = ci.produto_id
                 WHERE ci.carrinho_id = $carrinhoId";
    $it = mysqli_query($conn, $sqlItems);
    if (!$it) return;
    $cart = array();
    while ($row = mysqli_fetch_assoc($it)) {
        $pid = (int)$row['produto_id'];
        $cart[$pid] = array(
            'nome' => $row['nome'],
            'preco' => (float)$row['preco_unitario'],
            'qty' => (int)$row['quantidade'],
            'estoque' => (int)$row['estoque'],
            'foto1' => $row['foto1']
        );
    }
    $_SESSION['cart'] = $cart;
}

// Processar ações do carrinho
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $produto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    // Bloquear alterações sem login
    $requiresAuth = in_array($action, array('add','remove','update','clear'));
    if ($requiresAuth && !isset($_SESSION['cliente_id'])) {
        header('Location: login_cliente.php?redirect=' . urlencode('carrinho.php?action=' . $action . ($produto_id ? ('&id=' . $produto_id) : '')));
        exit;
    }
    
    switch ($action) {
        case 'add':
            if ($produto_id > 0) {
                // Buscar produto no banco
                $sql_produto = "SELECT p.*, c.nome as categoria_nome, m.nome as marca_nome 
                               FROM produto p 
                               INNER JOIN categoria c ON p.codcategoria = c.codigo 
                               INNER JOIN marca m ON p.codmarca = m.codigo 
                               WHERE p.codigo = $produto_id AND p.ativo = 1";
                $result_produto = mysqli_query($conn, $sql_produto);
                
                if ($result_produto && mysqli_num_rows($result_produto) > 0) {
                    $produto = mysqli_fetch_assoc($result_produto);
                    
                    if ($produto['estoque'] > 0) {
                        if (isset($_SESSION['cart'][$produto_id])) {
                            $_SESSION['cart'][$produto_id]['qty']++;
                        } else {
                            $_SESSION['cart'][$produto_id] = array(
                                'nome' => $produto['nome'],
                                'preco' => $produto['preco'],
                                'qty' => 1,
                                'estoque' => $produto['estoque'],
                                'foto1' => $produto['foto1']
                            );
                        }
                        // Persistir ao banco se logado
                        saveSessionCartToDb($conn);
                        $msg = 'Produto adicionado ao carrinho!';
                        $msg_tipo = 'success';
                    } else {
                        $msg = 'Produto fora de estoque!';
                        $msg_tipo = 'error';
                    }
                } else {
                    $msg = 'Produto não encontrado!';
                    $msg_tipo = 'error';
                }
            }
            break;
            
        case 'remove':
            if ($produto_id > 0 && isset($_SESSION['cart'][$produto_id])) {
                unset($_SESSION['cart'][$produto_id]);
                saveSessionCartToDb($conn);
                $msg = 'Produto removido do carrinho!';
                $msg_tipo = 'success';
            }
            break;
            
        case 'update':
            if ($produto_id > 0 && isset($_SESSION['cart'][$produto_id])) {
                $nova_qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
                if ($nova_qty > 0 && $nova_qty <= $_SESSION['cart'][$produto_id]['estoque']) {
                    $_SESSION['cart'][$produto_id]['qty'] = $nova_qty;
                    saveSessionCartToDb($conn);
                    $msg = 'Quantidade atualizada!';
                    $msg_tipo = 'success';
                } else {
                    $msg = 'Quantidade inválida!';
                    $msg_tipo = 'error';
                }
            }
            break;
            
        case 'clear':
            $_SESSION['cart'] = array();
            saveSessionCartToDb($conn);
            $msg = 'Carrinho esvaziado!';
            $msg_tipo = 'success';
            break;
    }
}

// Carregar carrinho do banco caso logado e sessão vazia
if (isset($_SESSION['cliente_id']) && empty($_SESSION['cart'])) {
    loadDbCartToSession($conn);
}

// Calcular totais
$total_itens = 0;
$total_valor = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_itens += $item['qty'];
    $total_valor += $item['preco'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="theme.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextLevel Tech - Carrinho</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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
        }

        .container { max-width: 1200px; margin: 32px auto; padding: 0 20px; }

        .navigation { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 12px; flex-wrap: wrap; }
        .navigation a.btn-secondary { background: #fff; color: var(--text-primary); border: 1px solid var(--border-color); }

        .card { background: #fff; padding: 24px; border-radius: 16px; border: 1px solid var(--border-color); box-shadow: 0 6px 20px rgba(0,0,0,0.06); }

        h1 { font-size: 22px; margin-bottom: 16px; color: var(--primary-color); }

        .cart-item { display: grid; grid-template-columns: 100px 1fr auto; gap: 16px; align-items: center; padding: 16px 0; border-bottom: 1px solid var(--border-color); }
        .product-image img { width: 100px; height: 80px; object-fit: cover; border-radius: 10px; background: #fff; border: 1px solid var(--border-color); }

        .product-info h3 { font-size: 16px; margin: 0 0 6px; color: var(--primary-color); font-weight: 600; }
        .product-info p { color: var(--text-secondary); font-size: 13px; }

        .price { font-size: 16px; font-weight: 700; color: var(--primary-color); }

        .qty-controls { display: flex; align-items: center; gap: 8px; }
        .qty-controls input[type=number] { width: 64px; padding: 8px; border: 1px solid var(--border-color); border-radius: 8px; text-align: center; }

        .btn-small { padding: 6px 10px; font-size: 12px; border-radius: 8px; border: none; background: var(--accent-color); color: #fff; cursor: pointer; }
        .btn-small.secondary { background: #fff; color: var(--text-primary); border: 1px solid var(--border-color); }

        .cart-summary { background: #fff; border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; text-align: center; }
        .cart-summary h2 { font-size: 18px; margin-bottom: 8px; color: var(--primary-color); }
        .cart-summary .total { font-size: 28px; font-weight: 800; color: var(--primary-color); margin: 12px 0; }

        .empty-cart { text-align: center; padding: 40px 16px; color: var(--text-secondary); }
        .empty-cart .btn { margin-top: 12px; }

        @media (max-width: 768px) {
            .container { padding: 0 12px; }
            .cart-item { grid-template-columns: 1fr; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navigation">
            <a href="home.php" class="btn btn-secondary">← Voltar à Loja</a>
            <?php if (isset($_SESSION['cliente_id'])): ?>
                <span>Olá, <?php echo htmlspecialchars($_SESSION['cliente_nome']); ?>!</span>
                <a href="logout_cliente.php?redirect=carrinho.php" class="btn btn-danger">Sair</a>
            <?php else: ?>
                <a href="login_cliente.php?redirect=carrinho.php" class="btn">Entrar</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="message <?php echo $msg_tipo; ?>"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <div class="card">
            <h1>🛒 Carrinho de Compras</h1>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <span class="empty-cart-icon">🛒</span>
                    <h2>Seu carrinho está vazio</h2>
                    <p>Adicione alguns produtos para começar suas compras!</p>
                    <a href="home.php" class="btn">Continuar Comprando</a>
                </div>
            <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $produto_id => $item): ?>
                    <div class="cart-item">
                        <div class="product-image">
                            <?php if (!empty($item['foto1'])): ?>
                                <img src="../produto/fotos/<?php echo htmlspecialchars($item['foto1']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                            <?php else: ?>
                                <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">🎮</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <p>Estoque disponível: <?php echo (int)$item['estoque']; ?> unidades</p>
                        </div>
                        
                        <div class="price">
                            R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
                        </div>
                        
                        <div class="qty-controls">
                            <form method="post" action="?action=update&id=<?php echo $produto_id; ?>" style="display: inline;">
                                <input type="number" name="qty" value="<?php echo $item['qty']; ?>" min="1" max="<?php echo $item['estoque']; ?>" onchange="this.form.submit()">
                            </form>
                        </div>
                        
                        <div>
                            <a href="?action=remove&id=<?php echo $produto_id; ?>" class="btn btn-danger btn-small" onclick="return confirm('Remover este produto do carrinho?')">Remover</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="cart-summary">
                    <h2>Resumo do Pedido</h2>
                    <p>Total de itens: <strong><?php echo $total_itens; ?></strong></p>
                    <div class="total">Total: R$ <?php echo number_format($total_valor, 2, ',', '.'); ?></div>
                    
                    <div style="margin-top: 2rem;">
                        <a href="?action=clear" class="btn btn-danger" onclick="return confirm('Esvaziar carrinho?')">Esvaziar Carrinho</a>
                        <?php if (isset($_SESSION['cliente_id'])): ?>
                            <a href="#" class="btn btn-success" onclick="alert('Funcionalidade de checkout em desenvolvimento!')">Finalizar Compra</a>
                        <?php else: ?>
                            <a href="login_cliente.php?redirect=carrinho.php" class="btn btn-success">Fazer Login para Finalizar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(function(msg) {
                msg.style.opacity = '0';
                setTimeout(function() {
                    msg.remove();
                }, 500);
            });
        }, 5000);
    </script>

<?php mysqli_close($conn); ?>
</body>
</html>
