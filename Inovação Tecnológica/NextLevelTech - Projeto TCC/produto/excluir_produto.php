<?php
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
mysqli_set_charset($conectar, 'utf8');

if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $codigo = intval($_POST['codigo']);
    
    if ($codigo > 0) {
        $sql = "DELETE FROM produto WHERE codigo = $codigo";
        $resultado = mysqli_query($conectar, $sql);
        
        if ($resultado) {
            echo "Produto excluído com sucesso!";
        } else {
            echo "Erro ao excluir produto: " . mysqli_error($conectar);
        }
    } else {
        echo "Código de produto inválido!";
    }
    exit;
}

// Buscar produto para exibir informações antes da exclusão
$produto = null;
if (isset($_GET['codigo'])) {
    $codigo = intval($_GET['codigo']);
    $sql = "SELECT p.*, m.nome AS marca, c.nome AS categoria 
            FROM produto p 
            JOIN marca m ON p.codmarca = m.codigo 
            JOIN categoria c ON p.codcategoria = c.codigo 
            WHERE p.codigo = $codigo";
    $resultado = mysqli_query($conectar, $sql);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $produto = mysqli_fetch_assoc($resultado);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto - NextLevel Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <style>
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
            overflow-x: hidden;
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
            33% { transform: translateY(-20px) rotate(2deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .nav-menu {
            margin-bottom: 2rem;
        }

        .nav-menu ul {
            list-style: none;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .nav-menu a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1, h2 {
            font-family: 'Orbitron', monospace;
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 2.5rem;
            background: linear-gradient(45deg, #f44336, #d32f2f);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        h2 {
            font-size: 1.5rem;
            color: #e0e0e0;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            padding: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            min-width: 200px;
        }

        .search-form input:focus {
            outline: none;
            border-color: #f44336;
            box-shadow: 0 0 20px rgba(244, 67, 54, 0.3);
            transform: translateY(-2px);
        }

        .search-form input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn {
            padding: 1rem 2rem;
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', monospace;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #d32f2f, #b71c1c);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #607d8b, #455a64);
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #546e7a, #37474f);
        }

        .btn-danger {
            background: linear-gradient(45deg, #f44336, #d32f2f);
        }

        .btn-danger:hover {
            background: linear-gradient(45deg, #d32f2f, #b71c1c);
        }

        .product-info {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-weight: 600;
            color: #4fc3f7;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .info-value {
            color: #fff;
            font-size: 1.1rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .warning-box {
            background: linear-gradient(45deg, rgba(255, 152, 0, 0.2), rgba(245, 124, 0, 0.2));
            border: 2px solid rgba(255, 152, 0, 0.5);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: center;
        }

        .warning-box h3 {
            color: #ff9800;
            font-family: 'Orbitron', monospace;
            margin-bottom: 1rem;
        }

        .warning-box p {
            color: #e0e0e0;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .no-product {
            text-align: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .no-product h3 {
            font-family: 'Orbitron', monospace;
            color: #4fc3f7;
            margin-bottom: 1rem;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(45deg, #4caf50, #388e3c);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            animation: slideInRight 0.5s ease-out;
        }

        .error-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-form input {
                width: 100%;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="cosmic-bg"></div>
    <div class="container">
        <nav class="nav-menu">
            <ul>
                <li><a href="cadastro_produto.html">Cadastrar Produto</a></li>
                <li><a href="alterar_produto.html">Alterar Produto</a></li>
                <li><a href="listar_produtos.php">Listar Produtos</a></li>
                <li><a href="../marca/cadastro_marca.html">Marcas</a></li>
                <li><a href="../categoria/cadastro_categoria.html">Categorias</a></li>
                <li><a href="../loja/menu.php">Menu</a></li>
            </ul>
        </nav>

        <div class="card">
            <h1>🗑️ Excluir Produto</h1>

            <form method="GET" class="search-form">
                <input type="number" name="codigo" placeholder="Digite o código do produto..." 
                       value="<?php echo isset($_GET['codigo']) ? $_GET['codigo'] : ''; ?>" 
                       min="1" required>
                <button type="submit" class="btn">🔍 Buscar Produto</button>
            </form>

            <?php if ($produto): ?>
                <div class="product-info">
                    <h2>📦 Informações do Produto</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Código</span>
                            <span class="info-value">#<?php echo sprintf('%03d', $produto['codigo']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nome</span>
                            <span class="info-value"><?php echo htmlspecialchars($produto['nome']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Modelo</span>
                            <span class="info-value"><?php echo htmlspecialchars($produto['modelo']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Marca</span>
                            <span class="info-value"><?php echo htmlspecialchars($produto['marca']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Categoria</span>
                            <span class="info-value"><?php echo htmlspecialchars($produto['categoria']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Preço</span>
                            <span class="info-value">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Estoque</span>
                            <span class="info-value"><?php echo (int)$produto['estoque']; ?> unidades</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <?php if ($produto['ativo']): ?>
                                    ✅ Ativo
                                <?php else: ?>
                                    ❌ Inativo
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="warning-box">
                    <h3>⚠️ ATENÇÃO!</h3>
                    <p>
                        Você está prestes a excluir permanentemente o produto <strong><?php echo htmlspecialchars($produto['nome']); ?></strong> 
                        (Código #<?php echo sprintf('%03d', $produto['codigo']); ?>). Esta ação não pode ser desfeita e todos os dados 
                        relacionados a este produto serão perdidos.
                    </p>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $produto['codigo']; ?>)">
                        🗑️ Confirmar Exclusão
                    </button>
                    <a href="listar_produtos.php" class="btn btn-secondary">
                        📋 Voltar para Lista
                    </a>
                </div>

            <?php elseif (isset($_GET['codigo'])): ?>
                <div class="no-product">
                    <h3>❌ Produto não encontrado</h3>
                    <p>Não foi encontrado nenhum produto com o código #<?php echo sprintf('%03d', intval($_GET['codigo'])); ?>.</p>
                    <a href="listar_produtos.php" class="btn btn-secondary" style="margin-top: 1rem;">
                        📋 Ver Lista de Produtos
                    </a>
                </div>
            <?php else: ?>
                <div class="no-product">
                    <h3>🔍 Buscar Produto</h3>
                    <p>Digite o código do produto que você deseja excluir no campo acima e clique em "Buscar Produto".</p>
                    <a href="listar_produtos.php" class="btn btn-secondary" style="margin-top: 1rem;">
                        📋 Ver Lista de Produtos
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function confirmDelete(productId) {
            if (confirm('Tem certeza absoluta que deseja excluir este produto? Esta ação é IRREVERSÍVEL!')) {
                deleteProduct(productId);
            }
        }

        function deleteProduct(productId) {
            const formData = new FormData();
            formData.append('codigo', productId);
            formData.append('action', 'delete');
            
            document.body.classList.add('loading');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                document.body.classList.remove('loading');
                
                if (result.includes('sucesso') || result.includes('excluído')) {
                    showMessage('Produto excluído com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.href = 'listar_produtos.php';
                    }, 2000);
                } else {
                    showMessage('Erro ao excluir produto: ' + result, 'error');
                }
            })
            .catch(error => {
                document.body.classList.remove('loading');
                showMessage('Erro ao excluir produto: ' + error, 'error');
            });
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>
<?php mysqli_close($conectar); ?>
