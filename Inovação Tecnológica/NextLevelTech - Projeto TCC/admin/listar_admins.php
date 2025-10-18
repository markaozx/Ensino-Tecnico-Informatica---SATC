<?php
session_start();
if (!isset($_SESSION['adm_id'])) { header('Location: ../loja/login_adm.php'); exit; }
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'super') { header('Location: ../loja/menu.php'); exit; }
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');

if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}

// Buscar todos os administradores
$sql = "SELECT codigo, nome, email, nivel_acesso FROM administrador ORDER BY codigo";
$resultado = mysqli_query($conectar, $sql);
$total_admins = mysqli_num_rows($resultado);

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Administradores</title>
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
        }

        .container {
            max-width: 1400px;
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
        }

        h1, h2 {
            font-family: 'Orbitron', monospace;
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 2.5rem;
            color: #fff;
        }

        h2 {
            font-size: 1.5rem;
            color: #e0e0e0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4fc3f7;
            font-family: 'Orbitron', monospace;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #e0e0e0;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.5rem;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .admin-table th {
            background: rgba(255, 255, 255, 0.1);
            color: #4fc3f7;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            border-bottom: 2px solid rgba(79, 195, 247, 0.3);
        }

        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .admin-table tr:hover td {
            background: rgba(255, 255, 255, 0.1);
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge-admin {
            background: linear-gradient(45deg, #4caf50, #388e3c);
            color: white;
        }

        .badge-super {
            background: linear-gradient(45deg, #ff9800, #f57c00);
            color: white;
        }

        .btn {
            padding: 1rem 2rem;
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
            margin: 0.5rem;
        }

        .btn-secondary {
            background: linear-gradient(45deg, #607d8b, #455a64);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #546e7a, #37474f);
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .options-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
        }

        .search-filter {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #4fc3f7;
            box-shadow: 0 0 20px rgba(79, 195, 247, 0.3);
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .admin-code {
            font-family: 'Orbitron', monospace;
            font-weight: 600;
            color: #4fc3f7;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .admin-table {
                font-size: 0.8rem;
            }
            
            .admin-table th,
            .admin-table td {
                padding: 0.5rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .menu-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .admin-table th:nth-child(3),
            .admin-table td:nth-child(3) {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="cosmic-bg"></div>
    <div class="container">
        <nav class="nav-menu">
            <ul>
                <li><a href="../Produto/cadastro_produto.html">Produtos</a></li>
                <li><a href="../Categoria/cadastro_categoria.html">Categorias</a></li>
                <li><a href="../Marca/cadastro_marca.html">Marcas</a></li>
                <li><a href="../Loja/menu.php">Menu</a></li>
            </ul>
        </nav>
        
        <div class="card">
            <h1>📋 Lista de Administradores</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_admins; ?></div>
                    <div class="stat-label">Total de Administradores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <?php 
                        mysqli_data_seek($resultado, 0);
                        $super_admins = 0;
                        while ($row = mysqli_fetch_assoc($resultado)) {
                            if ($row['nivel_acesso'] == 2) $super_admins++;
                        }
                        echo $super_admins;
                        ?>
                    </div>
                    <div class="stat-label">Super Administradores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_admins - $super_admins; ?></div>
                    <div class="stat-label">Administradores Padrão</div>
                </div>
            </div>

            <div class="search-filter">
                <input type="text" id="searchInput" class="search-input" placeholder="🔍 Buscar por nome, email ou código...">
            </div>

            <?php if ($total_admins > 0): ?>
                <div style="overflow-x: auto;">
                    <table class="admin-table" id="adminTable">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Nível de Acesso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($resultado, 0);
                            while ($admin = mysqli_fetch_assoc($resultado)): 
                            ?>
                                <tr>
                                    <td><span class="admin-code">#<?php echo sprintf('%03d', $admin['codigo']); ?></span></td>
                                    <td><?php echo htmlspecialchars($admin['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                    <td>
                                        <?php if ($admin['nivel_acesso'] == 2): ?>
                                            <span class="badge badge-super">🔰 Super Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-admin">👤 Admin Padrão</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <h2>🚫 Nenhum administrador encontrado</h2>
                    <p>Não há administradores cadastrados no sistema.</p>
                </div>
            <?php endif; ?>

            <div class="options-section">
                <h2>🔧 Opções de Gerenciamento</h2>
                <div class="menu-grid">
                    <a href="cadastro_admin.html" class="btn btn-secondary">👤 Cadastrar Administrador</a>
                    <a href="alterar_admin.html" class="btn btn-secondary">✏️ Alterar Administrador</a>
                    <a href="excluir_admin.html" class="btn btn-secondary">🗑️ Excluir Administrador</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('adminTable');
            const tbody = table ? table.querySelector('tbody') : null;

            if (searchInput && tbody) {
                const originalRows = Array.from(tbody.querySelectorAll('tr'));

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    originalRows.forEach(row => {
                        const codigo = row.cells[0].textContent.toLowerCase();
                        const nome = row.cells[1].textContent.toLowerCase();
                        const email = row.cells[2].textContent.toLowerCase();
                        const nivel = row.cells[3].textContent.toLowerCase();
                        
                        const matches = codigo.includes(searchTerm) || 
                                      nome.includes(searchTerm) || 
                                      email.includes(searchTerm) ||
                                      nivel.includes(searchTerm);
                        
                        if (matches) {
                            row.style.display = '';
                            row.style.animation = 'fadeIn 0.3s ease';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Verificar se há resultados visíveis
                    const visibleRows = originalRows.filter(row => row.style.display !== 'none');
                    if (visibleRows.length === 0 && searchTerm !== '') {
                        if (!document.querySelector('.no-results')) {
                            const noResults = document.createElement('tr');
                            noResults.className = 'no-results';
                            noResults.innerHTML = '<td colspan="4" style="text-align: center; padding: 2rem; font-style: italic; color: rgba(255, 255, 255, 0.7);">🔍 Nenhum administrador encontrado para: "' + searchTerm + '"</td>';
                            tbody.appendChild(noResults);
                        }
                    } else {
                        const noResults = document.querySelector('.no-results');
                        if (noResults) {
                            noResults.remove();
                        }
                    }
                });

                // Adicionar efeito de foco no input de busca
                searchInput.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            }

            // Adicionar animação de entrada nas linhas da tabela
            if (tbody) {
                const rows = tbody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        row.style.transition = 'all 0.5s ease';
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            }
        });

        // Adicionar CSS para animações
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>