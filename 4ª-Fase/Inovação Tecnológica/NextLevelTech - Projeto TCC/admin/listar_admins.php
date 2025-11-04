<?php
// Configurar timezone
date_default_timezone_set('America/Sao_Paulo');

session_start();

// Verificar se é admin
if (!isset($_SESSION['adm_id'])) {
    header('Location: ../loja/login_adm.php');
    exit;
}

// Verificar se é super admin
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'super') {
    header('Location: ../loja/menu.php');
    exit;
}

$nomeAdm = isset($_SESSION['adm_nome']) ? $_SESSION['adm_nome'] : "Administrador";

// Conectar ao banco
$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "latin1");

// Buscar administradores
$sql = "SELECT codigo, nome, email, nivel_acesso FROM administrador ORDER BY codigo";
$resultado = mysqli_query($conn, $sql);
$total_admins = mysqli_num_rows($resultado);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administradores - NextLevel Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php include 'admin_styles.php'; ?>
    <style>
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
            max-width: 500px;
            width: 90%;
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
        .modal-form select {
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
        .modal-form select:focus {
            outline: none;
            border-color: var(--accent-color);
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
            <div>👤 Logado como: <strong><?php echo htmlspecialchars($nomeAdm); ?></strong> (Super Admin)</div>
            <div>
                <a href="cadastro_admin.html">➕ Novo</a>
                <a href="alterar_admin.html">✏️ Alterar</a>
                <a href="excluir_admin.html">🗑️ Excluir</a>
                <a href="../produto/listar_produtos.php">📦 Produtos</a>
                <a href="../marca/listar_marcas.php">🏷️ Marcas</a>
                <a href="../categoria/listar_categorias.php">📂 Categorias</a>
                <a href="../loja/menu.php">🏠 Menu</a>
                <a href="../loja/menu.php?acao=sair">🚪 Sair</a>
            </div>
        </div>

        <div class="card">
            <h1>👥 Administradores do Sistema</h1>
            <p style="color: var(--text-secondary); margin-bottom: 30px;">
                Total de <?php echo $total_admins; ?> administrador(es) cadastrado(s)
            </p>

            <div class="search-bar">
                <a href="cadastro_admin.html" class="btn">➕ Cadastrar Novo Admin</a>
                <a href="alterar_admin.html" class="btn btn-secondary">✏️ Alterar</a>
                <a href="excluir_admin.html" class="btn btn-danger">🗑️ Excluir</a>
            </div>

            <?php if ($total_admins > 0): ?>
                <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Nível de Acesso</th>
                            <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($resultado, 0);
                            while ($admin = mysqli_fetch_assoc($resultado)): 
                            ?>
                                <tr>
                                <td><strong>#<?php echo $admin['codigo']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($admin['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                    <td>
                                        <?php if ($admin['nivel_acesso'] == 2): ?>
                                        <span class="badge badge-success">🌟 Super Admin</span>
                                        <?php else: ?>
                                        <span class="badge badge-info">👤 Admin Padrão</span>
                                        <?php endif; ?>
                                    </td>
                                <td>
                                    <button onclick="editarAdmin(<?php echo $admin['codigo']; ?>)" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin: 2px;" title="Editar Admin">✏️</button>
                                    <button onclick="excluirAdmin(<?php echo $admin['codigo']; ?>, '<?php echo htmlspecialchars(addslashes($admin['nome'])); ?>')" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; margin: 2px;" title="Excluir Admin">🗑️</button>
                                </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <p>Nenhum administrador cadastrado</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal de Edição Rápida -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Administrador</h2>
                <button class="modal-close" onclick="fecharModal()">✕</button>
            </div>
            <div id="modalBody">
                <div class="modal-loading">Carregando...</div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Função para excluir admin
        window.excluirAdmin = function(codigo, nome) {
            if (!confirm(`Tem certeza que deseja excluir o administrador "${nome}"?\n\nEsta ação não pode ser desfeita!`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('excluir', '1');
            formData.append('codigo', codigo);
            
            const allRows = document.querySelectorAll('tbody tr');
            let targetRow = null;
            allRows.forEach(r => {
                if (r.querySelector(`td:first-child`).textContent.includes(`#${codigo}`)) {
                    targetRow = r;
                }
            });
            
            if (targetRow) targetRow.style.opacity = '0.5';
            
            fetch('cadastro_admin.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result.includes('sucesso') || result.includes('excluído')) {
                    if (targetRow) {
                        targetRow.style.transition = 'opacity 0.3s';
                        targetRow.style.opacity = '0';
                        setTimeout(() => targetRow.remove(), 300);
                        showNotification('Administrador excluído com sucesso!', 'success');
                    }
                } else {
                    if (targetRow) targetRow.style.opacity = '1';
                    alert('Erro ao excluir: ' + result);
                }
            })
            .catch(error => {
                if (targetRow) targetRow.style.opacity = '1';
                alert('Erro ao excluir administrador');
            });
        };
        
        // Função para editar admin (abre modal)
        window.editarAdmin = function(codigo) {
            const modal = document.getElementById('editModal');
            const modalBody = document.getElementById('modalBody');
            
            modal.classList.add('active');
            modalBody.innerHTML = '<div class="modal-loading">Carregando dados do administrador...</div>';
            
            // Buscar dados do administrador
            fetch(`cadastro_admin.php?codigo=${codigo}&ajax=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        modalBody.innerHTML = `<div class="modal-loading">${data.error}</div>`;
                        return;
                    }
                    
                    const admin = data.admin;
                    
                    // Função para escapar HTML
                    const escapeHtml = (text) => {
                        if (!text) return '';
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    };
                    
                    // Criar formulário
                    modalBody.innerHTML = `
                        <form class="modal-form" id="editForm" onsubmit="salvarAdmin(event, ${codigo})">
                            <div class="form-group">
                                <label>Nome *</label>
                                <input type="text" name="nome" value="${escapeHtml(admin.nome)}" required>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" value="${escapeHtml(admin.email)}" required>
                            </div>
                            <div class="form-group">
                                <label>Nova Senha (deixe vazio para não alterar)</label>
                                <input type="password" name="nova_senha" placeholder="Deixe vazio para manter a senha atual">
                            </div>
                            <div class="form-group">
                                <label>Nível de Acesso *</label>
                                <select name="nivel_acesso" required>
                                    <option value="1" ${admin.nivel_acesso == 1 ? 'selected' : ''}>👤 Admin Padrão</option>
                                    <option value="2" ${admin.nivel_acesso == 2 ? 'selected' : ''}>🌟 Super Admin</option>
                                </select>
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
        
        // Função para salvar admin
        window.salvarAdmin = function(event, codigo) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            formData.append('alterar', '1');
            formData.append('codigo', codigo);
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvando...';
            
            fetch('cadastro_admin.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result.includes('sucesso') || result.includes('alterado')) {
                    showNotification('Administrador atualizado com sucesso!', 'success');
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
                alert('Erro ao salvar administrador');
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
