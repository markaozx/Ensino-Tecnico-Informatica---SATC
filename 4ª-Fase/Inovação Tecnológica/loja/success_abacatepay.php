<?php
// Configurar timezone para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

require_once 'abacatepay_config.php';

$pedido_id = isset($_GET['pedido_id']) ? (int)$_GET['pedido_id'] : 0;

if ($pedido_id <= 0) {
    header('Location: carrinho.php');
    exit;
}

// Configuração do banco de dados
$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "latin1");

// Buscar pedido
$sql_pedido = "SELECT * FROM pedido WHERE codigo = $pedido_id";
$result_pedido = mysqli_query($conn, $sql_pedido);
$pedido = mysqli_fetch_assoc($result_pedido);

if (!$pedido) {
    mysqli_close($conn);
    header('Location: home.php');
    exit;
}

// Verificar status na AbacatePay
$billing_id = $pedido['stripe_session_id'];
if ($billing_id) {
    $response = abacatepay_request('/billing/list');
    
    if (isset($response['data']) && is_array($response['data'])) {
        foreach ($response['data'] as $bill) {
            if ($bill['id'] === $billing_id && $bill['status'] === 'PAID') {
                // Atualizar status do pedido
                $sql_update = "UPDATE pedido SET status = 'pago', atualizado_em = NOW() WHERE codigo = $pedido_id";
                mysqli_query($conn, $sql_update);
                
                // Atualizar estoque
                $sql_items = "SELECT produto_id, quantidade FROM pedido_item WHERE pedido_id = $pedido_id";
                $result_items = mysqli_query($conn, $sql_items);
                
                while ($item = mysqli_fetch_assoc($result_items)) {
                    $produto_id = (int)$item['produto_id'];
                    $quantidade = (int)$item['quantidade'];
                    $sql_estoque = "UPDATE produto SET estoque = estoque - $quantidade WHERE codigo = $produto_id";
                    mysqli_query($conn, $sql_estoque);
                }
                
                // Limpar carrinho
                $_SESSION['cart'] = array();
                
                if (isset($_SESSION['cliente_id'])) {
                    $cliente_id = (int)$_SESSION['cliente_id'];
                    $sql_carrinho = "SELECT codigo FROM carrinho WHERE usuario_id = $cliente_id";
                    $result_carrinho = mysqli_query($conn, $sql_carrinho);
                    if ($row = mysqli_fetch_assoc($result_carrinho)) {
                        $carrinho_id = (int)$row['codigo'];
                        mysqli_query($conn, "DELETE FROM carrinho_item WHERE carrinho_id = $carrinho_id");
                    }
                }
                
                break;
            }
        }
    }
}

// Recarregar pedido atualizado
$result_pedido = mysqli_query($conn, "SELECT * FROM pedido WHERE codigo = $pedido_id");
$pedido = mysqli_fetch_assoc($result_pedido);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - NextLevel Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            padding: 50px 40px;
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            animation: scaleIn 0.5s ease-out 0.2s both;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .success { background: #10b981; }
        .pending { background: #f59e0b; }
        h1 { font-size: 32px; margin-bottom: 15px; }
        .subtitle { color: #666; font-size: 16px; margin-bottom: 30px; line-height: 1.6; }
        .order-details {
            background: #f5f5f5;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e5e5;
        }
        .detail-row:last-child { border-bottom: none; }
        .btn {
            padding: 16px 30px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            margin: 10px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary {
            background: white;
            color: #000;
            border: 2px solid #e5e5e5;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($pedido['status'] === 'pago'): ?>
            <div class="icon success">✓</div>
            <h1>🎉 Pagamento Confirmado!</h1>
            <p class="subtitle">Obrigado pela sua compra! Seu pagamento foi processado com sucesso.</p>
        <?php else: ?>
            <div class="icon pending">⏳</div>
            <h1>Aguardando Pagamento</h1>
            <p class="subtitle">Seu pedido foi criado. Complete o pagamento para confirmar.</p>
        <?php endif; ?>

        <div class="order-details">
            <div class="detail-row">
                <span style="color: #666;">Número do Pedido:</span>
                <strong>#<?php echo str_pad($pedido['codigo'], 6, '0', STR_PAD_LEFT); ?></strong>
            </div>
            <div class="detail-row">
                <span style="color: #666;">Data:</span>
                <strong><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></strong>
            </div>
            <div class="detail-row">
                <span style="color: #666;">Total:</span>
                <strong>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></strong>
            </div>
            <div class="detail-row">
                <span style="color: #666;">Status:</span>
                <strong><?php echo $pedido['status'] === 'pago' ? '✓ Pago' : '⏳ Aguardando'; ?></strong>
            </div>
        </div>

        <a href="home.php" class="btn btn-primary">Continuar Comprando</a>
        <a href="meus_pedidos.php" class="btn btn-secondary">Ver Meus Pedidos</a>
    </div>
</body>
</html>

