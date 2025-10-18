<?php
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');

if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}

// Buscar todas as categorias
$sql = "SELECT codigo, nome, descricao FROM categoria ORDER BY codigo";
$resultado = mysqli_query($conectar, $sql);
$total_categorias = mysqli_num_rows($resultado);

// Buscar categorias com produtos
$sql_com_produtos = "SELECT DISTINCT c.codigo FROM categoria c INNER JOIN produto p ON c.codigo = p.codcategoria";
$resultado_com_produtos = mysqli_query($conectar, $sql_com_produtos);
$categorias_com_produtos = mysqli_num_rows($resultado_com_produtos);

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Categorias</title>
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

        .categoria-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .categoria-table th {
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

        .categoria-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .categoria-table tr:hover td {
            background: rgba(255, 255, 255, 0.1);
        }

        .categoria-table tr:last-child td {
            border-bottom: none;
        }

        .categoria-descricao {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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

        .categoria-code {
            font-family: 'Orbitron', monospace;
            font-weight: 600;
            color: #4fc3f7;
        }

        .actions-cell {
            width: 120px;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            gap: 0.3rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-edit, .btn-save, .btn-cancel, .btn-delete {
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-edit {
            background: linear-gradient(45deg, #4fc3f7, #29b6f6);
            color: white;
        }

        .btn-save {
            background: linear-gradient(45deg, #4caf50, #388e3c);
            color: white;
        }

        .btn-cancel {
            background: linear-gradient(45deg, #ff9800, #f57c00);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
        }

        .btn-edit:hover, .btn-save:hover, .btn-cancel:hover, .btn-delete:hover {
            transform: translateY(-2px) scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .editable-field {
            cursor: pointer;
            padding: 0.3rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: inline-block;
            min-width: 80px;
        }

        .editable-field:hover {
            background: rgba(79, 195, 247, 0.2);
            transform: scale(1.05);
        }

        .editable-field.editing {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid #4fc3f7;
        }

        .editable-field input, .editable-field textarea {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border: none;
            padding: 0.3rem;
            border-radius: 5px;
            font-size: 0.9rem;
            width: 100%;
            min-width: 80px;
            font-family: 'Montserrat', sans-serif;
        }

        .editable-field input:focus, .editable-field textarea:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(79, 195, 247, 0.5);
        }

        .editable-field textarea {
            min-height: 60px;
            resize: vertical;
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

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .modal h3 {
            font-family: 'Orbitron', monospace;
            color: #4fc3f7;
            margin-bottom: 1rem;
        }

        .modal p {
            margin-bottom: 2rem;
            color: #e0e0e0;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-confirm {
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel-modal {
            background: linear-gradient(45deg, #607d8b, #455a64);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover, .btn-cancel-modal:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .categoria-table {
                font-size: 0.8rem;
            }
            
            .categoria-table th,
            .categoria-table td {
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
            .categoria-table th:nth-child(3),
            .categoria-table td:nth-child(3) {
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
                <li><a href="../Marca/cadastro_marca.html">Marcas</a></li>
                <li><a href="../Admin/cadastro_admin.html">Administradores</a></li>
                <li><a href="../Loja/menu.php">Menu</a></li>
            </ul>
        </nav>
        
        <div class="card">
            <h1>📋 Lista de Categorias</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_categorias; ?></div>
                    <div class="stat-label">Total de Categorias</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $categorias_com_produtos; ?></div>
                    <div class="stat-label">Com Produtos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_categorias - $categorias_com_produtos; ?></div>
                    <div class="stat-label">Sem Produtos</div>
                </div>
            </div>

            <div class="search-filter">
                <input type="text" id="searchInput" class="search-input" placeholder="🔍 Buscar por nome, descrição ou código...">
            </div>

            <?php if ($total_categorias > 0): ?>
                <div style="overflow-x: auto;">
                    <table class="categoria-table" id="categoriaTable">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($resultado, 0);
                            while ($categoria = mysqli_fetch_assoc($resultado)): 
                            ?>
                                <tr data-categoria-id="<?php echo $categoria['codigo']; ?>">
                                    <td><span class="categoria-code">#<?php echo sprintf('%03d', $categoria['codigo']); ?></span></td>
                                    <td>
                                        <span class="editable-field" data-field="nome" data-value="<?php echo htmlspecialchars($categoria['nome']); ?>">
                                            <?php echo htmlspecialchars($categoria['nome']); ?>
                                        </span>
                                    </td>
                                    <td class="categoria-descricao">
                                        <span class="editable-field" data-field="descricao" data-value="<?php echo htmlspecialchars($categoria['descricao']); ?>">
                                            <?php 
                                            if (!empty($categoria['descricao'])) {
                                                echo htmlspecialchars($categoria['descricao']);
                                            } else {
                                                echo '<em style="color: rgba(255, 255, 255, 0.5);">Sem descrição</em>';
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <div class="action-buttons">
                                            <button class="btn-edit" onclick="toggleEdit(this)" title="Editar">✏️</button>
                                            <button class="btn-save" onclick="saveChanges(this)" title="Salvar" style="display: none;">💾</button>
                                            <button class="btn-cancel" onclick="cancelEdit(this)" title="Cancelar" style="display: none;">❌</button>
                                            <button class="btn-delete" onclick="deleteCategoria(<?php echo $categoria['codigo']; ?>)" title="Excluir">🗑️</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <h2>🚫 Nenhuma categoria encontrada</h2>
                    <p>Não há categorias cadastradas no sistema.</p>
                </div>
            <?php endif; ?>

            <div class="options-section">
                <h2>🔧 Opções de Gerenciamento</h2>
                <div class="menu-grid">
                    <a href="cadastro_categoria.html" class="btn btn-secondary">🏷️ Cadastrar Categoria</a>
                    <a href="alterar_categoria.html" class="btn btn-secondary">✏️ Alterar Categoria</a>
                    <a href="excluir_categoria.html" class="btn btn-secondary">🗑️ Excluir Categoria</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let editingRow = null;
        let originalValues = {};

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('categoriaTable');
            const tbody = table ? table.querySelector('tbody') : null;

            if (searchInput && tbody) {
                const originalRows = Array.from(tbody.querySelectorAll('tr'));

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    originalRows.forEach(row => {
                        const codigo = row.cells[0].textContent.toLowerCase();
                        const nome = row.cells[1].textContent.toLowerCase();
                        const descricao = row.cells[2].textContent.toLowerCase();
                        
                        const matches = codigo.includes(searchTerm) || 
                                      nome.includes(searchTerm) || 
                                      descricao.includes(searchTerm);
                        
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
                            noResults.innerHTML = '<td colspan="4" style="text-align: center; padding: 2rem; font-style: italic; color: rgba(255, 255, 255, 0.7);">🔍 Nenhuma categoria encontrada para: "' + searchTerm + '"</td>';
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

        function toggleEdit(button) {
            const row = button.closest('tr');
            const editBtn = row.querySelector('.btn-edit');
            const saveBtn = row.querySelector('.btn-save');
            const cancelBtn = row.querySelector('.btn-cancel');
            
            if (editingRow && editingRow !== row) {
                cancelEdit(editingRow.querySelector('.btn-cancel'));
            }
            
            if (row.classList.contains('editing')) {
                cancelEdit(button);
                return;
            }
            
            // Iniciar edição
            row.classList.add('editing');
            editingRow = row;
            originalValues = {};
            
            const editableFields = row.querySelectorAll('.editable-field');
            editableFields.forEach(field => {
                const fieldName = field.dataset.field;
                const currentValue = field.dataset.value;
                originalValues[fieldName] = currentValue;
                
                field.classList.add('editing');
                
                if (fieldName === 'descricao') {
                    // Textarea para descrição
                    const textarea = document.createElement('textarea');
                    textarea.value = currentValue;
                    field.innerHTML = '';
                    field.appendChild(textarea);
                } else {
                    // Input de texto para nome
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentValue;
                    field.innerHTML = '';
                    field.appendChild(input);
                }
            });
            
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-flex';
            cancelBtn.style.display = 'inline-flex';
        }

        function cancelEdit(button) {
            const row = button.closest('tr');
            row.classList.remove('editing');
            editingRow = null;
            
            const editableFields = row.querySelectorAll('.editable-field');
            editableFields.forEach(field => {
                const fieldName = field.dataset.field;
                const originalValue = originalValues[fieldName];
                
                field.classList.remove('editing');
                field.innerHTML = '';
                
                if (fieldName === 'descricao') {
                    if (originalValue && originalValue.trim() !== '') {
                        field.innerHTML = originalValue;
                    } else {
                        field.innerHTML = '<em style="color: rgba(255, 255, 255, 0.5);">Sem descrição</em>';
                    }
                } else {
                    field.innerHTML = originalValue;
                }
            });
            
            const editBtn = row.querySelector('.btn-edit');
            const saveBtn = row.querySelector('.btn-save');
            const cancelBtn = row.querySelector('.btn-cancel');
            
            editBtn.style.display = 'inline-flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
        }

        function saveChanges(button) {
            const row = button.closest('tr');
            const categoriaId = row.dataset.categoriaId;
            
            row.classList.add('loading');
            
            const formData = new FormData();
            formData.append('codigo', categoriaId);
            formData.append('action', 'update');
            
            const editableFields = row.querySelectorAll('.editable-field');
            editableFields.forEach(field => {
                const fieldName = field.dataset.field;
                const input = field.querySelector('input, textarea');
                if (input) {
                    formData.append(fieldName, input.value);
                }
            });
            
            fetch('alterar_categoria.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                row.classList.remove('loading');
                
                if (result.includes('sucesso') || result.includes('alterado')) {
                    showMessage('Categoria atualizada com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showMessage('Erro ao atualizar categoria: ' + result, 'error');
                }
            })
            .catch(error => {
                row.classList.remove('loading');
                showMessage('Erro ao atualizar categoria: ' + error, 'error');
            });
        }

        function deleteCategoria(categoriaId) {
            showConfirmModal(
                'Confirmar Exclusão',
                `Tem certeza que deseja excluir a categoria #${String(categoriaId).padStart(3, '0')}? Esta ação não pode ser desfeita.`,
                () => {
                    const formData = new FormData();
                    formData.append('codigo', categoriaId);
                    formData.append('action', 'delete');
                    
                    fetch('excluir_categoria.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result.includes('sucesso') || result.includes('excluído')) {
                            showMessage('Categoria excluída com sucesso!', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showMessage('Erro ao excluir categoria: ' + result, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Erro ao excluir categoria: ' + error, 'error');
                    });
                }
            );
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        function showConfirmModal(title, message, onConfirm) {
            const modalOverlay = document.createElement('div');
            modalOverlay.className = 'modal-overlay';
            
            modalOverlay.innerHTML = `
                <div class="modal">
                    <h3>${title}</h3>
                    <p>${message}</p>
                    <div class="modal-buttons">
                        <button class="btn-confirm" onclick="confirmAction()">Confirmar</button>
                        <button class="btn-cancel-modal" onclick="closeModal()">Cancelar</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modalOverlay);
            
            window.confirmAction = function() {
                onConfirm();
                closeModal();
            };
            
            window.closeModal = function() {
                document.body.removeChild(modalOverlay);
                delete window.confirmAction;
                delete window.closeModal;
            };
        }

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