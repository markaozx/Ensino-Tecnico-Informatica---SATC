<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

// 🔐 Proteção de login
if (!isset($_SESSION['adm_id'])) {
    header("Location: login_adm.php");
    exit;
}

$nomeAdm = isset($_SESSION['adm_nome']) ? $_SESSION['adm_nome'] : "Administrador";

if (isset($_GET['acao']) && $_GET['acao'] == 'sair') {
    session_destroy();
    header("Location: login_adm.php");
    exit;
}

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'ecommerce_perifericos';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Consultas para obter contadores
    $produtoCount = $pdo->query("SELECT COUNT(*) FROM produto")->fetchColumn();
    $marcaCount = $pdo->query("SELECT COUNT(*) FROM marca")->fetchColumn();
    $categoriaCount = $pdo->query("SELECT COUNT(*) FROM categoria")->fetchColumn();
    $adminCount = $pdo->query("SELECT COUNT(*) FROM administrador")->fetchColumn();
    
} catch(PDOException $e) {
    // Valores padrão em caso de erro de conexão
    $produtoCount = 0;
    $marcaCount = 0;
    $categoriaCount = 0;
    $adminCount = 0;
    $conexao_erro = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="theme.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextLevel Tech - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <style>
        /* Seu CSS existente permanece aqui */

        .topbar {
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .topbar a {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: bold;
        }
        .topbar a:hover {
            text-decoration: underline;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #fff;
            padding: 20px;
        }

        .cosmic-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            z-index: -1;
            animation: cosmicFloat 20s ease-in-out infinite;
        }

        @keyframes cosmicFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #4fc3f7, #29b6f6, #03a9f4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: logoGlow 3s ease-in-out infinite alternate;
        }

        @keyframes logoGlow {
            0% { filter: drop-shadow(0 0 10px rgba(79, 195, 247, 0.3)); }
            100% { filter: drop-shadow(0 0 30px rgba(79, 195, 247, 0.8)); }
        }

        .subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 300;
            letter-spacing: 2px;
        }

        .menu-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .menu-section:hover {
            transform: translateY(-5px);
        }

        .section-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #4fc3f7;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(45deg, #4fc3f7, #29b6f6);
            border-radius: 2px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .menu-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .menu-card:hover::before {
            left: 100%;
        }

        .menu-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(79, 195, 247, 0.5);
            box-shadow: 0 20px 40px rgba(79, 195, 247, 0.2);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .card-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #fff;
            font-weight: 600;
        }

        .card-description {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Orbitron', monospace;
            font-size: 0.9rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #ff5252, #26a69a);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #607d8b, #455a64);
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #546e7a, #37474f);
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-family: 'Orbitron', monospace;
            font-size: 2rem;
            font-weight: 700;
            color: #4fc3f7;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-info {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 3rem;
        }

        .quick-actions {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            z-index: 1000;
        }

        .quick-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #4fc3f7, #29b6f6);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .quick-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(79, 195, 247, 0.4);
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .logo {
                font-size: 2rem;
            }
            
            .menu-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions {
                bottom: 20px;
                right: 20px;
            }

            .menu-section {
                padding: 1.5rem;
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: translateX(400px);
            transition: transform 0.3s ease;
            z-index: 1001;
            color: white;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: rgba(76, 175, 80, 0.9);
        }

        .notification.error {
            background: rgba(244, 67, 54, 0.9);
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        .status-online {
            background: #4caf50;
        }

        .status-offline {
            background: #f44336;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="cosmic-bg"></div>

    <!-- 🔐 Barra com nome do administrador e botão sair -->
    <div class="topbar">
        <div>👤 Logado como: <strong><?php echo htmlspecialchars($nomeAdm); ?></strong></div>
        <div><a href="?acao=sair">Sair</a></div>
    </div>
    
    <div class="notification <?php echo isset($conexao_erro) ? 'error' : 'success'; ?>" id="notification">
        <?php if (isset($conexao_erro)): ?>
            <span class="status-indicator status-offline"></span>Erro de conexão com banco de dados! ⚠️
        <?php else: ?>
            <span class="status-indicator status-online"></span>Sistema online e funcionando! ✨
        <?php endif; ?>
    </div>

    <div class="container">
        <div class="header">
            <h1 class="logo">🎮 NextLevel Tech</h1>
            <p class="subtitle">Sistema de Gerenciamento de Periféricos</p>
        </div>
        

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number" id="produtoCount"><?php echo $produtoCount; ?></div>
                <div class="stat-label">Produtos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="marcaCount"><?php echo $marcaCount; ?></div>
                <div class="stat-label">Marcas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="categoriaCount"><?php echo $categoriaCount; ?></div>
                <div class="stat-label">Categorias</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="adminCount"><?php echo $adminCount; ?></div>
                <div class="stat-label">Admins</div>
            </div>
        </div>

        <div class="menu-section">
            <h2 class="section-title">📦 Gerenciamento de Produtos</h2>
            <div class="menu-grid">
                <div class="menu-card">
                    <span class="card-icon">📦</span>
                    <h3 class="card-title">Cadastrar Produto</h3>
                    <p class="card-description">Adicione novos produtos ao catálogo com fotos, especificações e preços.</p>
                    <a href="../produto/cadastro_produto.html" class="btn">Cadastrar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">📋</span>
                    <h3 class="card-title">Listar Produtos</h3>
                    <p class="card-description">Visualize todos os <?php echo $produtoCount; ?> produtos cadastrados no sistema.</p>
                    <a href="../produto/listar_produtos.php" class="btn btn-secondary">Listar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">✏️</span>
                    <h3 class="card-title">Alterar Produto</h3>
                    <p class="card-description">Modifique informações de produtos existentes.</p>
                    <a href="../produto/alterar_produto.html" class="btn btn-secondary">Alterar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">🗑️</span>
                    <h3 class="card-title">Excluir Produto</h3>
                    <p class="card-description">Remova produtos do catálogo de forma segura.</p>
                    <a href="../produto/excluir_produto.html" class="btn btn-secondary">Excluir</a>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2 class="section-title">🏷️ Gerenciamento de Marcas</h2>
            <div class="menu-grid">
                <div class="menu-card">
                    <span class="card-icon">🏷️</span>
                    <h3 class="card-title">Cadastrar Marca</h3>
                    <p class="card-description">Adicione novas marcas de periféricos ao sistema.</p>
                    <a href="../marca/cadastro_marca.html" class="btn">Cadastrar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">📋</span>
                    <h3 class="card-title">Listar Marcas</h3>
                    <p class="card-description">Veja todas as <?php echo $marcaCount; ?> marcas registradas no sistema.</p>
                    <a href="../marca/listar_marcas.php" class="btn btn-secondary">Listar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">✏️</span>
                    <h3 class="card-title">Alterar Marca</h3>
                    <p class="card-description">Edite informações das marcas cadastradas.</p>
                    <a href="../marca/alterar_marca.html" class="btn btn-secondary">Alterar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">🗑️</span>
                    <h3 class="card-title">Excluir Marca</h3>
                    <p class="card-description">Remova marcas que não são mais utilizadas.</p>
                    <a href="../marca/excluir_marca.html" class="btn btn-secondary">Excluir</a>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2 class="section-title">📂 Gerenciamento de Categorias</h2>
            <div class="menu-grid">
                <div class="menu-card">
                    <span class="card-icon">📂</span>
                    <h3 class="card-title">Cadastrar Categoria</h3>
                    <p class="card-description">Crie novas categorias para organizar os produtos.</p>
                    <a href="../categoria/cadastro_categoria.html" class="btn">Cadastrar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">📋</span>
                    <h3 class="card-title">Listar Categorias</h3>
                    <p class="card-description">Visualize todas as <?php echo $categoriaCount; ?> categorias do sistema.</p>
                    <a href="../categoria/listar_categorias.php" class="btn btn-secondary">Listar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">✏️</span>
                    <h3 class="card-title">Alterar Categoria</h3>
                    <p class="card-description">Modifique categorias existentes no sistema.</p>
                    <a href="../categoria/alterar_categoria.html" class="btn btn-secondary">Alterar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">🗑️</span>
                    <h3 class="card-title">Excluir Categoria</h3>
                    <p class="card-description">Remova categorias não utilizadas do sistema.</p>
                    <a href="../categoria/excluir_categoria.html" class="btn btn-secondary">Excluir</a>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['nivel']) && $_SESSION['nivel'] === 'super'): ?>
        <div class="menu-section">
            <h2 class="section-title">💰 Painel Financeiro</h2>
            <div class="menu-grid">
                <div class="menu-card">
                    <span class="card-icon">💰</span>
                    <h3 class="card-title">Relatório de Vendas</h3>
                    <p class="card-description">Visualize vendas, faturamento e estatísticas.</p>
                    <a href="financeiro.php" class="btn">Acessar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">📊</span>
                    <h3 class="card-title">Pedidos</h3>
                    <p class="card-description">Gerencie todos os pedidos do sistema.</p>
                    <a href="financeiro.php?tab=pedidos" class="btn btn-secondary">Ver Pedidos</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">📈</span>
                    <h3 class="card-title">Estatísticas</h3>
                    <p class="card-description">Análise de vendas por período.</p>
                    <a href="financeiro.php?tab=estatisticas" class="btn btn-secondary">Ver Estatísticas</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">🥑</span>
                    <h3 class="card-title">AbacatePay</h3>
                    <p class="card-description">Transações e histórico de pagamentos.</p>
                    <a href="financeiro.php?tab=transacoes" class="btn btn-secondary">Ver Transações</a>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2 class="section-title">👤 Gerenciamento de Administradores</h2>
            <div class="menu-grid">
                <div class="menu-card">
                    <span class="card-icon">👤</span>
                    <h3 class="card-title">Cadastrar Admin</h3>
                    <p class="card-description">Adicione novos administradores ao sistema.</p>
                    <a href="../admin/cadastro_admin.html" class="btn">Cadastrar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">📋</span>
                    <h3 class="card-title">Listar Admins</h3>
                    <p class="card-description">Visualize todos os <?php echo $adminCount; ?> administradores cadastrados.</p>
                    <a href="../admin/listar_admins.php" class="btn btn-secondary">Listar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">✏️</span>
                    <h3 class="card-title">Alterar Admin</h3>
                    <p class="card-description">Modifique dados dos administradores.</p>
                    <a href="../admin/alterar_admin.html" class="btn btn-secondary">Alterar</a>
                </div>
                <div class="menu-card">
                    <span class="card-icon">🗑️</span>
                    <h3 class="card-title">Excluir Admin</h3>
                    <p class="card-description">Remova administradores do sistema.</p>
                    <a href="../admin/excluir_admin.html" class="btn btn-secondary">Excluir</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="footer-info">
            <p>🚀 Sistema de E-commerce para Periféricos Gamer</p>
            <p>Versão Alpha | Desenvolvido com tecnologias modernas</p>
            <?php if (!isset($conexao_erro)): ?>
                <p style="color: #4caf50; margin-top: 10px;">
                    <span class="status-indicator status-online"></span>
                    Conectado ao banco: <?php echo $dbname; ?>
                </p>
            <?php else: ?>
                <p style="color: #f44336; margin-top: 10px;">
                    <span class="status-indicator status-offline"></span>
                    Falha na conexão com o banco de dados
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="quick-actions">
        <a href="#top" class="quick-btn" title="Voltar ao topo">⬆️</a>
        <a href="../produto/cadastro_produto.html" class="quick-btn" title="Cadastro rápido">➕</a>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="quick-btn" title="Atualizar dados">🔄</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar notificação de status do sistema
            setTimeout(() => {
                document.getElementById('notification').classList.add('show');
                setTimeout(() => {
                    document.getElementById('notification').classList.remove('show');
                }, 4000);
            }, 1000);

            // Animação dos números das estatísticas
            animateCounters();

            // Adicionar efeitos visuais aos cards
            addCardEffects();

            // Smooth scroll para âncoras
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    } else {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            });
        });

        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                if (target === 0) return; // Não animar se for 0
                
                let current = 0;
                const increment = Math.max(1, target / 30); // Mínimo de 1 por incremento
                
                const timer = setInterval(() => {
                    current += increment;
                    counter.textContent = Math.floor(current);
                    
                    if (current >= target) {
                        counter.textContent = target;
                        clearInterval(timer);
                    }
                }, 50);
            });
        }

        function addCardEffects() {
            const cards = document.querySelectorAll('.menu-card');
            
            cards.forEach((card, index) => {
                // Animação escalonada de entrada
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);

                // Efeito de hover personalizado
                card.addEventListener('mouseenter', function() {
                    this.style.background = 'rgba(79, 195, 247, 0.1)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.background = 'rgba(255, 255, 255, 0.08)';
                });
            });
        }

        // Adicionar efeito de partículas em movimento
        function createFloatingParticles() {
            for (let i = 0; i < 3; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: fixed;
                    width: 4px;
                    height: 4px;
                    background: rgba(79, 195, 247, 0.6);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: -1;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    animation: float ${10 + Math.random() * 10}s infinite linear;
                `;
                
                document.body.appendChild(particle);
                
                setTimeout(() => {
                    particle.remove();
                }, 20000);
            }
        }

        // Criar partículas periodicamente
        setInterval(createFloatingParticles, 8000);

        // CSS para animação das partículas
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0% { transform: translateY(100vh) translateX(0); opacity: 1; }
                100% { transform: translateY(-100vh) translateX(${Math.random() * 200 - 100}px); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // Função para recarregar os dados (pode ser chamada via AJAX)
        function atualizarDados() {
            location.reload();
        }
    </script>
</body>
</html>