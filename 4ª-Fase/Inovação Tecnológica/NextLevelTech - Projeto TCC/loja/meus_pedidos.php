<?php
// Configurar timezone para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

// Verificar se o usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login_cliente.php?redirect=meus_pedidos.php');
    exit;
}

// Configuração do banco de dados
$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "ecommerce_perifericos";

$conn = mysqli_connect($servidor, $usuario, $senha, $banco);
if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "latin1");

$cliente_id = (int)$_SESSION['cliente_id'];

// Buscar pedidos do cliente
$sql_pedidos = "SELECT * FROM pedido WHERE cliente_id = $cliente_id ORDER BY data_pedido DESC";
$result_pedidos = mysqli_query($conn, $sql_pedidos);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - NextLevel Tech</title>
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
            background: var(--bg-gray);
            color: var(--text-primary);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 28px;
            color: var(--primary-color);
        }

        .btn {
            padding: 12px 24px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn:hover {
            background: var(--accent-color);
        }

        .pedidos-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .pedido-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: 1px solid var(--border-color);
        }

        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .pedido-numero {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .pedido-status {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pago {
            background: #d1fae5;
            color: #065f46;
        }

        .status-aguardando_pagamento {
            background: #fef3c7;
            color: #92400e;
        }

        .status-cancelado {
            background: #fee2e2;
            color: #991b1b;
        }

        .pedido-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 15px;
            color: var(--primary-color);
            font-weight: 500;
        }

        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .pedido-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .pedido-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>📦 Meus Pedidos</h1>
            <a href="home.php" class="btn">Continuar Comprando</a>
        </div>

        <div class="pedidos-list">
            <?php if (mysqli_num_rows($result_pedidos) > 0): ?>
                <?php while ($pedido = mysqli_fetch_assoc($result_pedidos)): ?>
                    <div class="pedido-card">
                        <div class="pedido-header">
                            <div class="pedido-numero">
                                Pedido #<?php echo str_pad($pedido['codigo'], 6, '0', STR_PAD_LEFT); ?>
                            </div>
                            <div class="pedido-status status-<?php echo $pedido['status']; ?>">
                                <?php 
                                $status_texto = array(
                                    'aguardando_pagamento' => 'Aguardando Pagamento',
                                    'pago' => 'Pago',
                                    'cancelado' => 'Cancelado',
                                    'em_separacao' => 'Em Separação',
                                    'enviado' => 'Enviado',
                                    'entregue' => 'Entregue'
                                );
                                echo isset($status_texto[$pedido['status']]) ? $status_texto[$pedido['status']] : $pedido['status'];
                                ?>
                            </div>
                        </div>
                        
                        <div class="pedido-info">
                            <div class="info-item">
                                <span class="info-label">Data do Pedido</span>
                                <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Valor Total</span>
                                <span class="info-value">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Forma de Pagamento</span>
                                <span class="info-value"><?php echo ucfirst($pedido['forma_pagamento']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📦</div>
                    <h2>Você ainda não tem pedidos</h2>
                    <p style="color: var(--text-secondary); margin: 15px 0;">Explore nossa loja e faça seu primeiro pedido!</p>
                    <a href="home.php" class="btn">Ver Produtos</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
