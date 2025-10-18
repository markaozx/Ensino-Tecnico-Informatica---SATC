<?php
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}
$filtro = isset($_GET['q']) ? mysqli_real_escape_string($conectar, $_GET['q']) : '';
$sql = "SELECT p.*, m.nome AS marca, c.nome AS categoria FROM produto p JOIN marca m ON p.codmarca=m.codigo JOIN categoria c ON p.codcategoria=c.codigo";
if ($filtro !== '') {
    $sql .= " WHERE p.nome LIKE '%$filtro%' OR p.modelo LIKE '%$filtro%'";
}
$sql .= " ORDER BY p.codigo DESC";
$resultado = mysqli_query($conectar, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextLevel Tech - Produtos</title>
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
            color: #fff; 
            margin: 0; 
            padding: 20px;
            min-height: 100vh;
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
            max-width: 1400px; 
            margin: 0 auto; 
        }

        .card { 
            background: rgba(255,255,255,0.1); 
            padding: 2rem; 
            border-radius: 20px; 
            border: 1px solid rgba(255,255,255,0.2); 
            backdrop-filter: blur(15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
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

        h1 { 
            font-family: 'Orbitron', monospace; 
            margin: 0 0 2rem;
            font-size: 2.5rem;
            text-align: center;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
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

        .topbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-container {
            position: relative;
        }

        input[type=text] { 
            padding: 1rem 1.5rem; 
            border-radius: 25px; 
            border: 2px solid rgba(255,255,255,0.2); 
            background: rgba(255,255,255,0.1); 
            color: #fff;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            width: 300px;
        }

        input[type=text]:focus {
            outline: none;
            border-color: #4fc3f7;
            box-shadow: 0 0 20px rgba(79, 195, 247, 0.3);
            transform: translateY(-2px);
        }

        input[type=text]::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn { 
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0.5rem; 
            padding: 1rem 2rem; 
            border-radius: 25px; 
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4); 
            color: #fff; 
            text-decoration: none; 
            font-weight: 600;
            font-family: 'Orbitron', monospace;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #ff5252, #26a69a);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #607d8b, #455a64);
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #546e7a, #37474f);
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-top: 2rem;
        }

        .products-table th {
            background: rgba(255, 255, 255, 0.1);
            color: #4fc3f7;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            border-bottom: 2px solid rgba(79, 195, 247, 0.3);
            font-family: 'Orbitron', monospace;
        }

        .products-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .products-table tr:hover td {
            background: rgba(255, 255, 255, 0.1);
        }

        .products-table tr:last-child td {
            border-bottom: none;
        }

        .product-code {
            font-family: 'Orbitron', monospace;
            font-weight: 600;
            color: #4fc3f7;
        }

        .product-price {
            font-family: 'Orbitron', monospace;
            font-weight: 600;
            color: #4caf50;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-active {
            background: linear-gradient(45deg, #4caf50, #388e3c);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
        }

        .stock-indicator {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: 'Orbitron', monospace;
        }

        .stock-low {
            background: linear-gradient(45deg, #ff9800, #f57c00);
            color: white;
        }

        .stock-ok {
            background: linear-gradient(45deg, #4caf50, #388e3c);
            color: white;
        }

        .stock-empty {
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
        }

        .no-products {
            text-align: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
        }

        .actions-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .topbar {
                flex-direction: column;
                text-align: center;
            }
            
            input[type=text] {
                width: 100%;
            }
            
            .products-table {
                font-size: 0.8rem;
            }
            
            .products-table th,
            .products-table td {
                padding: 0.5rem;
            }
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

        .editable-field input, .editable-field select {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border: none;
            padding: 0.3rem;
            border-radius: 5px;
            font-size: 0.9rem;
            width: 100%;
            min-width: 80px;
        }

        .editable-field input:focus, .editable-field select:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(79, 195, 247, 0.5);
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

        @media (max-width: 480px) {
            .products-table th:nth-child(3),
            .products-table td:nth-child(3),
            .products-table th:nth-child(4),
            .products-table td:nth-child(4) {
                display: none;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.2rem;
            }
            
            .btn-edit, .btn-save, .btn-cancel, .btn-delete {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="cosmic-bg"></div>
    <div class="container">
        <div class="card">
            <h1>📦 Produtos</h1>
            
            <div class="topbar">
                <div class="search-container">
                <form method="get">
                        <input type="text" name="q" value="<?php echo htmlspecialchars($filtro); ?>" placeholder="🔍 Buscar por nome ou modelo...">
                </form>
                </div>
                <div>
                    <a class="btn" href="cadastro_produto.html">➕ Cadastrar Produto</a>
                    <a class="btn btn-secondary" href="../loja/menu.php">🏠 Voltar ao Menu</a>
                </div>
            </div>

            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <table class="products-table">
                <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Modelo</th>
                            <th>Marca</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Status</th>
                            <th>Estoque</th>
                            <th>Ações</th>
                        </tr>
                </thead>
                <tbody>
                        <?php 
                        mysqli_data_seek($resultado, 0);
                        while($row = mysqli_fetch_assoc($resultado)) { 
                            // Determinar classe do estoque
                            $estoque = (int)$row['estoque'];
                            $stockClass = '';
                            if ($estoque == 0) {
                                $stockClass = 'stock-empty';
                            } elseif ($estoque <= 10) {
                                $stockClass = 'stock-low';
                            } else {
                                $stockClass = 'stock-ok';
                            }
                        ?>
                            <tr data-product-id="<?php echo $row['codigo']; ?>">
                                <td><span class="product-code">#<?php echo sprintf('%03d', $row['codigo']); ?></span></td>
                                <td>
                                    <span class="editable-field" data-field="nome" data-value="<?php echo htmlspecialchars($row['nome']); ?>">
                                        <?php echo htmlspecialchars($row['nome']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable-field" data-field="modelo" data-value="<?php echo htmlspecialchars($row['modelo']); ?>">
                                        <?php echo htmlspecialchars($row['modelo']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable-field" data-field="codmarca" data-value="<?php echo $row['codmarca']; ?>" data-display="<?php echo htmlspecialchars($row['marca']); ?>">
                                        <?php echo htmlspecialchars($row['marca']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable-field" data-field="codcategoria" data-value="<?php echo $row['codcategoria']; ?>" data-display="<?php echo htmlspecialchars($row['categoria']); ?>">
                                        <?php echo htmlspecialchars($row['categoria']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable-field" data-field="preco" data-value="<?php echo $row['preco']; ?>">
                                        <span class="product-price">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></span>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable-field" data-field="ativo" data-value="<?php echo $row['ativo']; ?>">
                                        <?php if ($row['ativo']): ?>
                                            <span class="status-badge status-active">✅ Ativo</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">❌ Inativo</span>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable-field" data-field="estoque" data-value="<?php echo $estoque; ?>">
                                        <span class="stock-indicator <?php echo $stockClass; ?>">
                                            <?php echo $estoque; ?> unid.
                                        </span>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <button class="btn-edit" onclick="toggleEdit(this)" title="Editar">✏️</button>
                                        <button class="btn-save" onclick="saveChanges(this)" title="Salvar" style="display: none;">💾</button>
                                        <button class="btn-cancel" onclick="cancelEdit(this)" title="Cancelar" style="display: none;">❌</button>
                                        <button class="btn-delete" onclick="deleteProduct(<?php echo $row['codigo']; ?>)" title="Excluir">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="no-products">
                    <h2>🚫 Nenhum produto encontrado</h2>
                    <p>Não há produtos cadastrados ou que correspondam ao filtro aplicado.</p>
                    <a class="btn" href="cadastro_produto.html" style="margin-top: 1rem;">Adicionar Primeiro Produto</a>
                </div>
            <?php endif; ?>

            <div class="actions-section">
                <h2 style="font-family: 'Orbitron', monospace; color: #4fc3f7; margin-bottom: 1rem;">🔧 Ações Disponíveis</h2>
                <a class="btn" href="cadastro_produto.html">📦 Cadastrar Produto</a>
                <a class="btn btn-secondary" href="alterar_produto.html">✏️ Alterar Produto</a>
                <a class="btn btn-secondary" href="../loja/home.php">🏪 Ver na Loja</a>
            </div>
        </div>
    </div>

    <script>
        let editingRow = null;
        let originalValues = {};

        document.addEventListener('DOMContentLoaded', function() {
            // Adicionar animação de entrada nas linhas da tabela
            const rows = document.querySelectorAll('.products-table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Auto-submit do formulário de busca após 1 segundo de inatividade
            let searchTimeout;
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.form.submit();
                    }, 1000);
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
                // Cancelar edição
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
                
                if (fieldName === 'ativo') {
                    // Select para ativo/inativo
                    const select = document.createElement('select');
                    select.innerHTML = `
                        <option value="1" ${currentValue == '1' ? 'selected' : ''}>Ativo</option>
                        <option value="0" ${currentValue == '0' ? 'selected' : ''}>Inativo</option>
                    `;
                    field.innerHTML = '';
                    field.appendChild(select);
                } else if (fieldName === 'preco') {
                    // Input numérico para preço
                    const input = document.createElement('input');
                    input.type = 'number';
                    input.step = '0.01';
                    input.value = currentValue;
                    field.innerHTML = '';
                    field.appendChild(input);
                } else if (fieldName === 'estoque') {
                    // Input numérico para estoque
                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '0';
                    input.value = currentValue;
                    field.innerHTML = '';
                    field.appendChild(input);
                } else {
                    // Input de texto para outros campos
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = fieldName === 'nome' ? field.dataset.value : 
                                  fieldName === 'modelo' ? field.dataset.value :
                                  fieldName === 'codmarca' ? field.dataset.value :
                                  fieldName === 'codcategoria' ? field.dataset.value : field.dataset.value;
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
                const displayValue = field.dataset.display;
                
                field.classList.remove('editing');
                field.innerHTML = '';
                
                if (fieldName === 'preco') {
                    field.innerHTML = `<span class="product-price">R$ ${parseFloat(originalValue).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                } else if (fieldName === 'ativo') {
                    if (originalValue == '1') {
                        field.innerHTML = '<span class="status-badge status-active">✅ Ativo</span>';
                    } else {
                        field.innerHTML = '<span class="status-badge status-inactive">❌ Inativo</span>';
                    }
                } else if (fieldName === 'estoque') {
                    const estoque = parseInt(originalValue);
                    let stockClass = '';
                    if (estoque == 0) {
                        stockClass = 'stock-empty';
                    } else if (estoque <= 10) {
                        stockClass = 'stock-low';
                    } else {
                        stockClass = 'stock-ok';
                    }
                    field.innerHTML = `<span class="stock-indicator ${stockClass}">${estoque} unid.</span>`;
                } else {
                    field.innerHTML = displayValue || originalValue;
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
            const productId = row.dataset.productId;
            
            row.classList.add('loading');
            
            const formData = new FormData();
            formData.append('codigo', productId);
            formData.append('action', 'update');
            
            const editableFields = row.querySelectorAll('.editable-field');
            editableFields.forEach(field => {
                const fieldName = field.dataset.field;
                const input = field.querySelector('input, select');
                if (input) {
                    formData.append(fieldName, input.value);
                }
            });
            
            fetch('alterar_produto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                row.classList.remove('loading');
                
                if (result.includes('sucesso') || result.includes('alterado')) {
                    showMessage('Produto atualizado com sucesso!', 'success');
                    // Recarregar a página após 1 segundo
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showMessage('Erro ao atualizar produto: ' + result, 'error');
                }
            })
            .catch(error => {
                row.classList.remove('loading');
                showMessage('Erro ao atualizar produto: ' + error, 'error');
            });
        }

        function deleteProduct(productId) {
            showConfirmModal(
                'Confirmar Exclusão',
                `Tem certeza que deseja excluir o produto #${String(productId).padStart(3, '0')}? Esta ação não pode ser desfeita.`,
                () => {
                    const formData = new FormData();
                    formData.append('codigo', productId);
                    formData.append('action', 'delete');
                    
                    fetch('excluir_produto.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result.includes('sucesso') || result.includes('excluído')) {
                            showMessage('Produto excluído com sucesso!', 'success');
                            // Recarregar a página após 1 segundo
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showMessage('Erro ao excluir produto: ' + result, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Erro ao excluir produto: ' + error, 'error');
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
    </script>
</body>
</html>
<?php mysqli_close($conectar); ?>




