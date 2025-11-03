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
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div>👤 Logado como: <strong><?php echo htmlspecialchars($nomeAdm); ?></strong> (Super Admin)</div>
            <div>
                <a href="../loja/menu.php">🏠 Menu Principal</a>
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
                                    <a href="alterar_admin.html?id=<?php echo $admin['codigo']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin: 2px;">✏️</a>
                                    <a href="excluir_admin.html?id=<?php echo $admin['codigo']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; margin: 2px;" onclick="return confirm('Tem certeza que deseja excluir este admin?')">🗑️</a>
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
</body>
</html>
