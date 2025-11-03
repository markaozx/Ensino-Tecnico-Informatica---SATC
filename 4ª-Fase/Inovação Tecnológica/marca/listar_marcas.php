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

// Buscar marcas com contagem de produtos
$sql = "SELECT m.*, COUNT(p.codigo) as total_produtos 
        FROM marca m 
        LEFT JOIN produto p ON p.codmarca = m.codigo 
        GROUP BY m.codigo 
        ORDER BY m.nome";
$resultado = mysqli_query($conn, $sql);
$total_marcas = mysqli_num_rows($resultado);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcas - NextLevel Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php include '../admin/admin_styles.php'; ?>
    <style>
        .editable { cursor: pointer; padding: 4px 8px; border-radius: 4px; transition: background 0.2s; }
        .editable:hover { background: #f0f0f0; }
        .editable.editing { background: #fff; }
        .editable input { width: 100%; border: 2px solid var(--accent-color); padding: 4px 8px; border-radius: 4px; font-size: inherit; background: #fff; color: var(--text-primary); }
        .saving { opacity: 0.6; pointer-events: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div>👤 Logado como: <strong><?php echo htmlspecialchars($nomeAdm); ?></strong></div>
            <div>
                <a href="../loja/menu.php">🏠 Menu Principal</a>
                <a href="../loja/menu.php?acao=sair">🚪 Sair</a>
                </div>
            </div>

        <div class="card">
            <h1>🏷️ Marcas de Produtos</h1>
            <p style="color: var(--text-secondary); margin-bottom: 30px;">
                Total de <?php echo $total_marcas; ?> marca(s) cadastrada(s)
            </p>

            <div class="search-bar">
                <a href="cadastro_marca.html" class="btn">➕ Cadastrar Marca</a>
                <a href="alterar_marca.html" class="btn btn-secondary">✏️ Alterar</a>
                <a href="excluir_marca.html" class="btn btn-danger">🗑️ Excluir</a>
            </div>

            <?php if ($total_marcas > 0): ?>
                <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                            <th>País</th>
                            <th>Total de Produtos</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($marca = mysqli_fetch_assoc($resultado)): ?>
                            <tr data-id="<?php echo $marca['codigo']; ?>">
                                <td><strong>#<?php echo $marca['codigo']; ?></strong></td>
                                <td><strong><span class="editable" data-field="nome" data-type="text"><?php echo htmlspecialchars($marca['nome']); ?></span></strong></td>
                                <td><span class="editable" data-field="pais" data-type="text"><?php echo htmlspecialchars($marca['pais']); ?></span></td>
                                <td><span class="badge badge-info"><?php echo $marca['total_produtos']; ?> produtos</span></td>
                                <td>
                                    <a href="alterar_marca.html?id=<?php echo $marca['codigo']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin: 2px;">✏️</a>
                                    <a href="excluir_marca.html?id=<?php echo $marca['codigo']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; margin: 2px;" onclick="return confirm('Tem certeza? Esta marca tem <?php echo $marca['total_produtos']; ?> produto(s).')">🗑️</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">🏷️</div>
                    <h3 style="color: var(--text-primary);">Nenhuma marca cadastrada</h3>
                    <p>Cadastre a primeira marca do sistema</p>
                </div>
            <?php endif; ?>
                    </div>
                </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.editable').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.stopPropagation();
                if (this.classList.contains('editing')) return;
                if (this.querySelector('input')) return;
                
                this.classList.add('editing');
                const field = this.dataset.field;
                const currentValue = this.textContent.trim();
                
                const input = document.createElement('input');
                input.type = 'text';
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
                    const marcaId = this.closest('tr').dataset.id;
                    
                    const formData = new FormData();
                    formData.append('action', 'update');
                    formData.append('codigo', marcaId);
                    formData.append(field, newValue);
                    
                    fetch('alterar_marca.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result.includes('sucesso') || result.includes('atualizada')) {
                            this.innerHTML = newValue;
                        } else {
                            this.innerHTML = originalHTML;
                            alert('Erro ao atualizar');
                        }
                        this.classList.remove('editing', 'saving');
                    })
                    .catch(error => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('editing', 'saving');
                        alert('Erro ao atualizar');
                    });
                };
                
                input.addEventListener('blur', save);
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') { e.preventDefault(); save(); }
                    else if (e.key === 'Escape') { this.innerHTML = originalHTML; this.classList.remove('editing'); }
                });
            });
        });
    });
    </script>
</body>
</html>
