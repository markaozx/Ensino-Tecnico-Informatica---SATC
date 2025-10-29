<?php
// Configurar timezone para Brasília
date_default_timezone_set('America/Sao_Paulo');

session_start();

// Verificar se é admin
if (!isset($_SESSION['adm_id'])) {
    header("Location: login_adm.php");
    exit;
}

// Verificar se é admin nível 2 (super)
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'super') {
    header("Location: menu.php");
    exit;
}

$nomeAdm = isset($_SESSION['adm_nome']) ? $_SESSION['adm_nome'] : "Administrador";

// Conexão com o banco
$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "latin1");

// Aba ativa
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';

// Período de filtro
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : '30dias';

// Calcular datas
$data_final = date('Y-m-d 23:59:59');
switch ($periodo) {
    case 'hoje':
        $data_inicial = date('Y-m-d 00:00:00');
        break;
    case '7dias':
        $data_inicial = date('Y-m-d 00:00:00', strtotime('-7 days'));
        break;
    case '30dias':
        $data_inicial = date('Y-m-d 00:00:00', strtotime('-30 days'));
        break;
    case 'mes':
        $data_inicial = date('Y-m-01 00:00:00');
        break;
    case 'ano':
        $data_inicial = date('Y-01-01 00:00:00');
        break;
    default:
        $data_inicial = date('Y-m-d 00:00:00', strtotime('-30 days'));
}

// Estatísticas gerais
$sql_total_vendas = "SELECT COUNT(*) as total FROM pedido WHERE status = 'pago' AND data_pedido BETWEEN '$data_inicial' AND '$data_final'";
$result_vendas = mysqli_query($conn, $sql_total_vendas);
$total_vendas = mysqli_fetch_assoc($result_vendas)['total'];

$sql_faturamento = "SELECT SUM(total) as faturamento FROM pedido WHERE status = 'pago' AND data_pedido BETWEEN '$data_inicial' AND '$data_final'";
$result_faturamento = mysqli_query($conn, $sql_faturamento);
$faturamento = mysqli_fetch_assoc($result_faturamento)['faturamento'];
$faturamento = $faturamento ? (float)$faturamento : 0;

$sql_pendentes = "SELECT COUNT(*) as total FROM pedido WHERE status = 'aguardando_pagamento'";
$result_pendentes = mysqli_query($conn, $sql_pendentes);
$pedidos_pendentes = mysqli_fetch_assoc($result_pendentes)['total'];

$sql_ticket_medio = "SELECT AVG(total) as ticket FROM pedido WHERE status = 'pago' AND data_pedido BETWEEN '$data_inicial' AND '$data_final'";
$result_ticket = mysqli_query($conn, $sql_ticket_medio);
$ticket_medio = mysqli_fetch_assoc($result_ticket)['ticket'];
$ticket_medio = $ticket_medio ? (float)$ticket_medio : 0;

// Buscar todos os pedidos
$sql_pedidos = "SELECT p.*, u.nome as cliente_nome, u.email as cliente_email 
                FROM pedido p 
                INNER JOIN usuario u ON p.cliente_id = u.codigo 
                ORDER BY p.data_pedido DESC";
$result_pedidos = mysqli_query($conn, $sql_pedidos);

// Produtos mais vendidos
$sql_mais_vendidos = "SELECT p.nome, p.foto1, SUM(pi.quantidade) as total_vendido, SUM(pi.subtotal) as faturamento_produto
                      FROM pedido_item pi
                      INNER JOIN produto p ON pi.produto_id = p.codigo
                      INNER JOIN pedido ped ON pi.pedido_id = ped.codigo
                      WHERE ped.status = 'pago' AND ped.data_pedido BETWEEN '$data_inicial' AND '$data_final'
                      GROUP BY pi.produto_id
                      ORDER BY total_vendido DESC
                      LIMIT 5";
$result_mais_vendidos = mysqli_query($conn, $sql_mais_vendidos);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="theme.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Financeiro - NextLevel Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary-color: #000;
            --accent-color: #FF6B00;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-primary: #000;
            --text-secondary: #666;
            --border-color: #e5e5e5;
            --bg-gray: #f5f5f5;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .topbar {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 15px 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            transition: all 0.3s;
        }

        .topbar a:hover {
            background: var(--danger-color);
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 32px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .tab {
            padding: 12px 24px;
            background: var(--bg-gray);
            border: 2px solid transparent;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            transition: all 0.3s;
        }

        .tab:hover {
            background: #e0e0e0;
        }

        .tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary-color);
            margin: 10px 0;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            font-size: 40px;
            opacity: 0.8;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: var(--bg-gray);
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        table th {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 13px;
            text-transform: uppercase;
        }

        table tr:hover {
            background: var(--bg-gray);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
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

        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-bar select {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .product-mini {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-mini img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            table th, table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div>👤 Logado como: <strong><?php echo htmlspecialchars($nomeAdm); ?></strong> (Super Admin)</div>
            <div>
                <a href="menu.php">🏠 Menu Principal</a>
                <a href="?acao=sair">🚪 Sair</a>
            </div>
        </div>

        <div class="header">
            <h1>💰 Painel Financeiro</h1>
            <p style="color: var(--text-secondary);">Relatórios, vendas e estatísticas do sistema</p>

            <div class="tabs">
                <a href="?tab=dashboard" class="tab <?php echo $tab === 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a>
                <a href="?tab=pedidos" class="tab <?php echo $tab === 'pedidos' ? 'active' : ''; ?>">📦 Pedidos</a>
                <a href="?tab=estatisticas" class="tab <?php echo $tab === 'estatisticas' ? 'active' : ''; ?>">📈 Estatísticas</a>
                <a href="?tab=transacoes" class="tab <?php echo $tab === 'transacoes' ? 'active' : ''; ?>">🥑 Transações</a>
            </div>
        </div>

        <?php if ($tab === 'dashboard'): ?>
            <!-- Dashboard Principal -->
            <div class="filter-bar">
                <strong>Período:</strong>
                <select onchange="window.location.href='?tab=dashboard&periodo=' + this.value">
                    <option value="hoje" <?php echo $periodo === 'hoje' ? 'selected' : ''; ?>>Hoje</option>
                    <option value="7dias" <?php echo $periodo === '7dias' ? 'selected' : ''; ?>>Últimos 7 dias</option>
                    <option value="30dias" <?php echo $periodo === '30dias' ? 'selected' : ''; ?>>Últimos 30 dias</option>
                    <option value="mes" <?php echo $periodo === 'mes' ? 'selected' : ''; ?>>Este mês</option>
                    <option value="ano" <?php echo $periodo === 'ano' ? 'selected' : ''; ?>>Este ano</option>
                </select>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-label">Faturamento</div>
                    <div class="stat-value">R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-label">Vendas Realizadas</div>
                    <div class="stat-value"><?php echo $total_vendas; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💳</div>
                    <div class="stat-label">Ticket Médio</div>
                    <div class="stat-value">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-label">Pedidos Pendentes</div>
                    <div class="stat-value"><?php echo $pedidos_pendentes; ?></div>
                </div>
            </div>

            <!-- Produtos Mais Vendidos -->
            <div class="card">
                <h2>🏆 Produtos Mais Vendidos</h2>
                <?php if (mysqli_num_rows($result_mais_vendidos) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade Vendida</th>
                                <th>Faturamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($produto = mysqli_fetch_assoc($result_mais_vendidos)): ?>
                                <tr>
                                    <td>
                                        <div class="product-mini">
                                            <?php if ($produto['foto1']): ?>
                                                <img src="../produto/fotos/<?php echo htmlspecialchars($produto['foto1']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                            <?php else: ?>
                                                <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">🎮</div>
                                            <?php endif; ?>
                                            <strong><?php echo htmlspecialchars($produto['nome']); ?></strong>
                                        </div>
                                    </td>
                                    <td><strong><?php echo $produto['total_vendido']; ?></strong> unidades</td>
                                    <td><strong>R$ <?php echo number_format($produto['faturamento_produto'], 2, ',', '.'); ?></strong></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <p>Nenhum produto vendido neste período</p>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($tab === 'pedidos'): ?>
            <!-- Lista de Pedidos -->
            <div class="card">
                <h2>📦 Todos os Pedidos</h2>
                <?php if (mysqli_num_rows($result_pedidos) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Pedido #</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($pedido = mysqli_fetch_assoc($result_pedidos)): ?>
                                <tr>
                                    <td><strong>#<?php echo str_pad($pedido['codigo'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                                    <td><strong>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></strong></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $pedido['status']; ?>">
                                            <?php 
                                            $status_texto = array(
                                                'aguardando_pagamento' => 'Aguardando',
                                                'pago' => 'Pago',
                                                'cancelado' => 'Cancelado'
                                            );
                                            echo isset($status_texto[$pedido['status']]) ? $status_texto[$pedido['status']] : $pedido['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo ucfirst($pedido['forma_pagamento']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <p>Nenhum pedido encontrado</p>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($tab === 'estatisticas'): ?>
            <!-- Estatísticas Detalhadas -->
            <div class="filter-bar">
                <strong>Período:</strong>
                <select onchange="window.location.href='?tab=estatisticas&periodo=' + this.value">
                    <option value="hoje" <?php echo $periodo === 'hoje' ? 'selected' : ''; ?>>Hoje</option>
                    <option value="7dias" <?php echo $periodo === '7dias' ? 'selected' : ''; ?>>Últimos 7 dias</option>
                    <option value="30dias" <?php echo $periodo === '30dias' ? 'selected' : ''; ?>>Últimos 30 dias</option>
                    <option value="mes" <?php echo $periodo === 'mes' ? 'selected' : ''; ?>>Este mês</option>
                    <option value="ano" <?php echo $periodo === 'ano' ? 'selected' : ''; ?>>Este ano</option>
                </select>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-label">Faturamento Total</div>
                    <div class="stat-value">R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-label">Pedidos Pagos</div>
                    <div class="stat-value"><?php echo $total_vendas; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💳</div>
                    <div class="stat-label">Ticket Médio</div>
                    <div class="stat-value">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-label">Aguardando Pagamento</div>
                    <div class="stat-value"><?php echo $pedidos_pendentes; ?></div>
                </div>
            </div>

            <!-- Vendas por Categoria -->
            <?php
            $sql_cat = "SELECT c.nome, COUNT(DISTINCT ped.codigo) as total_pedidos, SUM(pi.quantidade) as total_itens, SUM(pi.subtotal) as faturamento_cat
                        FROM categoria c
                        LEFT JOIN produto p ON p.codcategoria = c.codigo
                        LEFT JOIN pedido_item pi ON pi.produto_id = p.codigo
                        LEFT JOIN pedido ped ON ped.codigo = pi.pedido_id AND ped.status = 'pago' AND ped.data_pedido BETWEEN '$data_inicial' AND '$data_final'
                        GROUP BY c.codigo
                        ORDER BY faturamento_cat DESC";
            $result_cat = mysqli_query($conn, $sql_cat);
            ?>
            <div class="card">
                <h2>📊 Vendas por Categoria</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Pedidos</th>
                            <th>Itens Vendidos</th>
                            <th>Faturamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cat = mysqli_fetch_assoc($result_cat)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($cat['nome']); ?></strong></td>
                                <td><?php echo $cat['total_pedidos'] ? $cat['total_pedidos'] : 0; ?></td>
                                <td><?php echo $cat['total_itens'] ? $cat['total_itens'] : 0; ?></td>
                                <td><strong>R$ <?php echo number_format($cat['faturamento_cat'] ? $cat['faturamento_cat'] : 0, 2, ',', '.'); ?></strong></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($tab === 'transacoes'): ?>
            <!-- Transações AbacatePay -->
            <div class="card">
                <h2>🥑 Histórico de Transações AbacatePay</h2>
                <?php
                $sql_transacoes = "SELECT p.*, u.nome as cliente_nome, u.email 
                                   FROM pedido p 
                                   INNER JOIN usuario u ON p.cliente_id = u.codigo 
                                   WHERE p.forma_pagamento = 'abacatepay' 
                                   ORDER BY p.data_pedido DESC";
                $result_trans = mysqli_query($conn, $sql_transacoes);
                ?>
                <?php if (mysqli_num_rows($result_trans) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Pedido #</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Billing ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($trans = mysqli_fetch_assoc($result_trans)): ?>
                                <tr>
                                    <td><strong>#<?php echo str_pad($trans['codigo'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td>
                                        <?php echo htmlspecialchars($trans['cliente_nome']); ?><br>
                                        <small style="color: #999;"><?php echo htmlspecialchars($trans['email']); ?></small>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($trans['data_pedido'])); ?></td>
                                    <td><strong>R$ <?php echo number_format($trans['total'], 2, ',', '.'); ?></strong></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $trans['status']; ?>">
                                            <?php echo $trans['status'] === 'pago' ? '✓ Pago' : ($trans['status'] === 'aguardando_pagamento' ? '⏳ Aguardando' : $trans['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <code style="font-size: 11px;"><?php echo $trans['stripe_session_id'] ? substr($trans['stripe_session_id'], 0, 20) . '...' : '-'; ?></code>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">🥑</div>
                        <p>Nenhuma transação AbacatePay encontrada</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>

