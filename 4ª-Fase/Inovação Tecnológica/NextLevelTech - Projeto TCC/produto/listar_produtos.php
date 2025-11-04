<?php
// Configurar timezone
date_default_timezone_set('America/Sao_Paulo');

session_start();

// Verificar se é admin
if (!isset($_SESSION['adm_id'])) {
    header('Location: ../loja/login_adm.php');
    exit;
}

$nomeAdm = isset($_SESSION['adm_nome']) ? $_SESSION['adm_nome'] : "Administrador";

// Conectar ao banco
$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "latin1");

// Filtro de busca
$filtro = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$sql = "SELECT p.*, m.nome AS marca, c.nome AS categoria 
        FROM produto p 
        JOIN marca m ON p.codmarca=m.codigo 
        JOIN categoria c ON p.codcategoria=c.codigo";

if ($filtro !== '') {
    $sql .= " WHERE p.nome LIKE '%$filtro%' OR p.modelo LIKE '%$filtro%'";
}

$sql .= " ORDER BY p.codigo DESC";
$resultado = mysqli_query($conn, $sql);
$total_produtos = mysqli_num_rows($resultado);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - NextLevel Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php include '../admin/admin_styles.php'; ?>
    <style>
        .editable {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .editable:hover {
            background: #f0f0f0;
        }
        .editable.editing {
            background: #fff;
        }
        .editable input {
            width: 100%;
            border: 2px solid var(--accent-color);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: inherit;
            background: #fff;
            color: var(--text-primary);
        }
        .saving {
            opacity: 0.6;
            pointer-events: none;
        }
        /* Modal de edição rápida */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .modal-header h2 {
            margin: 0;
            color: var(--text-primary);
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-secondary);
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background: #f0f0f0;
            color: var(--text-primary);
        }
        .modal-form .form-group {
            margin-bottom: 1rem;
        }
        .modal-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }
        .modal-form input,
        .modal-form select,
        .modal-form textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
            background: white;
            color: var(--text-primary);
        }
        .modal-form input:focus,
        .modal-form select:focus,
        .modal-form textarea:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        .modal-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 2px solid #f0f0f0;
        }
        .modal-loading {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div>👤 Logado como: <strong><?php echo htmlspecialchars($nomeAdm); ?></strong></div>
            <div>
                <a href="cadastro_produto.html">➕ Novo</a>
                <a href="alterar_produto.html">✏️ Alterar</a>
                <a href="excluir_produto.html">🗑️ Excluir</a>
                <a href="../marca/listar_marcas.php">🏷️ Marcas</a>
                <a href="../categoria/listar_categorias.php">📂 Categorias</a>
                <a href="../loja/menu.php">🏠 Menu</a>
                <a href="../loja/menu.php?acao=sair">🚪 Sair</a>
            </div>
        </div>

        <div class="card">
            <h1>📦 Produtos Cadastrados</h1>
            <p style="color: var(--text-secondary); margin-bottom: 30px;">
                Total de <?php echo $total_produtos; ?> produto(s) no catálogo
            </p>

            <div class="search-bar">
                <form method="get" style="display: inline-flex; gap: 10px; margin-bottom: 20px;">
                    <input type="text" name="q" placeholder="Buscar por nome ou modelo..." value="<?php echo htmlspecialchars($filtro); ?>">
                    <button type="submit" class="btn">🔍 Buscar</button>
                    <?php if ($filtro): ?>
                        <a href="?" class="btn btn-secondary">✕ Limpar</a>
                    <?php endif; ?>
                </form>
                <br>
                <a href="cadastro_produto.html" class="btn">➕ Cadastrar Produto</a>
                <a href="alterar_produto.html" class="btn btn-secondary">✏️ Alterar</a>
                <a href="excluir_produto.html" class="btn btn-danger">🗑️ Excluir</a>
            </div>

            <?php if ($total_produtos > 0): ?>
                <table>
                <thead>
                        <tr>
                            <th>Código</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Marca</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                </thead>
                <tbody>
                        <?php while ($produto = mysqli_fetch_assoc($resultado)): ?>
                            <tr data-id="<?php echo $produto['codigo']; ?>">
                                <td><strong>#<?php echo $produto['codigo']; ?></strong></td>
                                <td>
                                    <?php if ($produto['foto1']): ?>
                                        <img src="fotos/<?php echo htmlspecialchars($produto['foto1']); ?>" 
                                             alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                                             class="product-image">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">🎮</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="editable" data-field="nome" data-type="text"><?php echo htmlspecialchars($produto['nome']); ?></span>
                                    <br><small style="color: var(--text-secondary);"><span class="editable" data-field="modelo" data-type="text"><?php echo htmlspecialchars($produto['modelo']); ?></span></small>
                                </td>
                                <td><?php echo htmlspecialchars($produto['marca']); ?></td>
                                <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                                <td><span class="editable" data-field="preco" data-type="number">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span></td>
                                <td><span class="editable" data-field="estoque" data-type="number"><?php echo $produto['estoque']; ?></span></td>
                                <td>
                                    <?php if ($produto['ativo'] == 1): ?>
                                        <span class="badge badge-success">✓ Ativo</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger">✗ Inativo</span>
                                        <?php endif; ?>
                                </td>
                                <td>
                                    <button onclick="editarProduto(<?php echo $produto['codigo']; ?>)" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin: 2px;" title="Editar Produto">✏️</button>
                                    <button onclick="excluirProduto(<?php echo $produto['codigo']; ?>, '<?php echo htmlspecialchars(addslashes($produto['nome'])); ?>')" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; margin: 2px;" title="Excluir Produto">🗑️</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3 style="color: var(--text-primary);">Nenhum produto encontrado</h3>
                    <p>
                        <?php if ($filtro): ?>
                            Nenhum resultado para "<?php echo htmlspecialchars($filtro); ?>"
                        <?php else: ?>
                            Cadastre o primeiro produto do sistema
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
                    </div>
                </div>

    <!-- Modal de Edição Rápida -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Produto</h2>
                <button class="modal-close" onclick="fecharModal()">✕</button>
            </div>
            <div id="modalBody">
                <div class="modal-loading">Carregando...</div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tornar campos editáveis ao clicar
        document.querySelectorAll('.editable').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.stopPropagation();
                
                if (this.classList.contains('editing')) return;
                if (this.querySelector('input')) return;
                
                this.classList.add('editing');
                const field = this.dataset.field;
                const type = this.dataset.type;
                
                let currentValue = this.textContent.trim();
                if (field === 'preco') {
                    // Remove R$ e converte formato brasileiro para formato numérico
                    currentValue = currentValue.replace('R$ ', '').trim();
                    currentValue = currentValue.replace(/\./g, '').replace(',', '.');
                }
                
                const input = document.createElement('input');
                input.type = type === 'number' ? 'number' : 'text';
                input.value = currentValue;
                input.style.width = '100%';
                
                const originalHTML = this.innerHTML;
                this.innerHTML = '';
                this.appendChild(input);
                input.focus();
                input.select();
                
                const save = () => {
                    if (this.classList.contains('saving')) return;
                    
                    const newValue = input.value.trim();
                    if (newValue === currentValue) {
                        this.innerHTML = originalHTML;
                        this.classList.remove('editing');
                        return;
                    }
                    
                    this.classList.add('saving');
                    const produtoId = this.closest('tr').dataset.id;
                    
                    const formData = new FormData();
                    formData.append('action', 'update');
                    formData.append('codigo', produtoId);
                    formData.append(field, newValue);
                    
                    fetch('alterar_produto.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        console.log('Result:', result);
                        if (result.includes('sucesso')) {
                            // Atualizar visualmente
                            if (field === 'preco') {
                                const formattedValue = parseFloat(newValue).toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                                this.innerHTML = 'R$ ' + formattedValue;
                            } else if (field === 'estoque') {
                                // Atualizar badge de estoque se necessário
                                this.innerHTML = newValue;
                            } else {
                                this.innerHTML = newValue;
                            }
                        } else {
                            this.innerHTML = originalHTML;
                            alert('Erro ao atualizar: ' + result);
                        }
                        this.classList.remove('editing', 'saving');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.innerHTML = originalHTML;
                        this.classList.remove('editing', 'saving');
                        alert('Erro ao atualizar');
                    });
                };
                
                input.addEventListener('blur', save);
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        save();
                    } else if (e.key === 'Escape') {
                        this.innerHTML = originalHTML;
                        this.classList.remove('editing');
                    }
                });
            });
        });
        
        // Função para excluir produto
        window.excluirProduto = function(codigo, nome) {
            if (!confirm(`Tem certeza que deseja excluir o produto "${nome}"?\n\nEsta ação não pode ser desfeita!`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('codigo', codigo);
            
            const row = document.querySelector(`tr[data-id="${codigo}"]`);
            if (row) row.style.opacity = '0.5';
            
            fetch('excluir_produto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result.includes('sucesso') || result.includes('excluído')) {
                    if (row) {
                        row.style.transition = 'opacity 0.3s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                        showNotification('Produto excluído com sucesso!', 'success');
                    }
                } else {
                    if (row) row.style.opacity = '1';
                    alert('Erro ao excluir: ' + result);
                }
            })
            .catch(error => {
                if (row) row.style.opacity = '1';
                alert('Erro ao excluir produto');
            });
        };
        
        // Função para editar produto (abre modal)
        window.editarProduto = function(codigo) {
            const modal = document.getElementById('editModal');
            const modalBody = document.getElementById('modalBody');
            
            modal.classList.add('active');
            modalBody.innerHTML = '<div class="modal-loading">Carregando dados do produto...</div>';
            
            // Buscar dados do produto
            fetch(`alterar_produto.php?codigo=${codigo}&ajax=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        modalBody.innerHTML = `<div class="modal-loading">${data.error}</div>`;
                        return;
                    }
                    
                    const produto = data.produto;
                    const marcas = data.marcas;
                    const categorias = data.categorias;
                    
                    // Função para escapar HTML
                    function escapeHtml(text) {
                        if (!text) return '';
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    }
                    
                    // Criar formulário
                    modalBody.innerHTML = `
                        <form class="modal-form" id="editForm" onsubmit="salvarProduto(event, ${codigo})">
                            <div class="form-group">
                                <label>Nome *</label>
                                <input type="text" name="nome" value="${escapeHtml(produto.nome)}" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Modelo *</label>
                                    <input type="text" name="modelo" value="${escapeHtml(produto.modelo)}" required>
                                </div>
                                <div class="form-group">
                                    <label>Cor</label>
                                    <input type="text" name="cor" value="${escapeHtml(produto.cor || '')}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Marca *</label>
                                    <select name="codmarca" required>
                                        ${marcas.map(m => `<option value="${m.codigo}" ${m.codigo == produto.codmarca ? 'selected' : ''}>${escapeHtml(m.nome)}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Categoria *</label>
                                    <select name="codcategoria" required>
                                        ${categorias.map(c => `<option value="${c.codigo}" ${c.codigo == produto.codcategoria ? 'selected' : ''}>${escapeHtml(c.nome)}</option>`).join('')}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea name="descricao" rows="3">${escapeHtml(produto.descricao || '')}</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Preço *</label>
                                    <input type="number" name="preco" step="0.01" value="${produto.preco}" required>
                                </div>
                                <div class="form-group">
                                    <label>Estoque *</label>
                                    <input type="number" name="estoque" value="${produto.estoque}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Estoque Mínimo</label>
                                    <input type="number" name="estoque_minimo" value="${produto.estoque_minimo || 0}">
                                </div>
                                <div class="form-group">
                                    <label>Status *</label>
                                    <select name="ativo" required>
                                        <option value="1" ${produto.ativo == 1 ? 'selected' : ''}>Ativo</option>
                                        <option value="0" ${produto.ativo == 0 ? 'selected' : ''}>Inativo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-actions">
                                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                                <button type="submit" class="btn">💾 Salvar Alterações</button>
                            </div>
                        </form>
                    `;
                })
                .catch(error => {
                    modalBody.innerHTML = `<div class="modal-loading">Erro ao carregar dados: ${error.message}</div>`;
                });
        };
        
        // Função para fechar modal
        window.fecharModal = function() {
            document.getElementById('editModal').classList.remove('active');
        };
        
        // Fechar modal ao clicar fora
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
        
        // Função para salvar produto
        window.salvarProduto = function(event, codigo) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            formData.append('action', 'update');
            formData.append('codigo', codigo);
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvando...';
            
            fetch('alterar_produto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result.includes('sucesso') || result.includes('atualizado')) {
                    showNotification('Produto atualizado com sucesso!', 'success');
                    fecharModal();
                    // Recarregar a página após 1 segundo
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Erro ao atualizar: ' + result);
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                alert('Erro ao salvar produto');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        };
        
        // Função para mostrar notificações
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 10px;
                background: ${type === 'success' ? 'rgba(76, 175, 80, 0.9)' : 'rgba(244, 67, 54, 0.9)'};
                color: white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Adicionar animações CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(400px); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    });
    </script>
</body>
</html>
