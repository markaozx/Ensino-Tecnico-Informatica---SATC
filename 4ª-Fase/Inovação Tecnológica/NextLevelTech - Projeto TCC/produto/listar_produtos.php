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
                            <tr>
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
                                    <strong><?php echo htmlspecialchars($produto['nome']); ?></strong>
                                    <?php if ($produto['modelo']): ?>
                                        <br><small style="color: var(--text-secondary);"><?php echo htmlspecialchars($produto['modelo']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($produto['marca']); ?></td>
                                <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                                <td><strong>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></strong></td>
                                <td>
                                    <?php if ($produto['estoque'] <= 0): ?>
                                        <span class="badge badge-danger">0 unidades</span>
                                    <?php elseif ($produto['estoque'] <= $produto['estoque_minimo']): ?>
                                        <span class="badge badge-warning"><?php echo $produto['estoque']; ?> unidades</span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?php echo $produto['estoque']; ?> unidades</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($produto['ativo'] == 1): ?>
                                        <span class="badge badge-success">✓ Ativo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">✗ Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="alterar_produto.html?id=<?php echo $produto['codigo']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin: 2px;">✏️</a>
                                    <a href="excluir_produto.html?id=<?php echo $produto['codigo']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; margin: 2px;">🗑️</a>
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
</body>
</html>
