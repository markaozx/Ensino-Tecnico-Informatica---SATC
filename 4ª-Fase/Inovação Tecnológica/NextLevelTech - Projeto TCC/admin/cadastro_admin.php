<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();
if (!isset($_SESSION['adm_id'])) { header('Location: ../loja/login_adm.php'); exit; }
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'super') { header('Location: ../loja/menu.php'); exit; }
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conectar, "latin1");

// Retornar dados do administrador em JSON para requisições AJAX
if (isset($_GET['codigo']) && isset($_GET['ajax'])) {
    $codigo = intval($_GET['codigo']);
    $sql = "SELECT * FROM administrador WHERE codigo = $codigo";
    $resultado = mysqli_query($conectar, $sql);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $admin = mysqli_fetch_assoc($resultado);
        // Não retornar a senha
        unset($admin['senha']);
        
        header('Content-Type: application/json');
        echo json_encode(['admin' => $admin]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Administrador não encontrado']);
        exit;
    }
}

// Processar cadastro de administrador
if (isset($_POST['cadastrar'])) {
    $nome = mysqli_real_escape_string($conectar, $_POST['nome']);
    $email = mysqli_real_escape_string($conectar, $_POST['email']);
    $senha = mysqli_real_escape_string($conectar, $_POST['senha']);
    $confirma_senha = isset($_POST['confirma_senha']) ? mysqli_real_escape_string($conectar, $_POST['confirma_senha']) : '';
    $nivel_acesso = intval($_POST['nivel_acesso']);

    // Validar se as senhas coincidem
    if ($senha !== $confirma_senha) {
        $mensagem = "As senhas não coincidem.";
        $tipo_mensagem = "error";
    } else {
        // Verificar se o email já existe
        $sql_check = "SELECT COUNT(*) as total FROM administrador WHERE email = '$email'";
        $resultado_check = mysqli_query($conectar, $sql_check);
        $row_check = mysqli_fetch_assoc($resultado_check);

        if ($row_check['total'] > 0) {
            $mensagem = "Já existe um administrador com este email.";
            $tipo_mensagem = "warning";
        } else {
            // Criptografar a senha
            $senha_hash = md5($senha);
            
            // Inserir novo administrador
            $sql = "INSERT INTO administrador (nome, email, senha, nivel_acesso) VALUES ('$nome', '$email', '$senha_hash', $nivel_acesso)";
            $resultado = mysqli_query($conectar, $sql);

            if ($resultado) {
                $mensagem = "Administrador cadastrado com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Falha ao cadastrar o administrador: " . mysqli_error($conectar);
                $tipo_mensagem = "error";
            }
        }
    }
}

// Processar exclusão de administrador
if (isset($_POST['excluir'])) {
    $codigo = intval($_POST['codigo']);

    // Verificar se não é o último administrador
    $sql_check = "SELECT COUNT(*) as total FROM administrador";
    $resultado_check = mysqli_query($conectar, $sql_check);
    $row_check = mysqli_fetch_assoc($resultado_check);

    if ($row_check['total'] <= 1) {
        $mensagem = "Não é possível excluir o último administrador do sistema.";
        $tipo_mensagem = "error";
    } else {
        // Excluir administrador
        $sql = "DELETE FROM administrador WHERE codigo = $codigo";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado) {
            if (mysqli_affected_rows($conectar) > 0) {
                $mensagem = "Administrador excluído com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Administrador não encontrado.";
                $tipo_mensagem = "warning";
            }
        } else {
            $mensagem = "Falha ao excluir o administrador: " . mysqli_error($conectar);
            $tipo_mensagem = "error";
        }
    }
    
    // Se for requisição AJAX, retornar apenas a mensagem
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo $mensagem;
        exit;
    }
}

// Processar alteração de administrador
if (isset($_POST['alterar'])) {
    $codigo = intval($_POST['codigo']);
    
    // Buscar dados atuais do administrador
    $sql_atual = "SELECT * FROM administrador WHERE codigo = $codigo";
    $resultado_atual = mysqli_query($conectar, $sql_atual);
    
    if ($resultado_atual && mysqli_num_rows($resultado_atual) > 0) {
        $admin_atual = mysqli_fetch_assoc($resultado_atual);
        
        // Se o campo estiver vazio, usa o valor atual do banco
        $nome = !empty($_POST['nome']) ? mysqli_real_escape_string($conectar, $_POST['nome']) : $admin_atual['nome'];
        $email = !empty($_POST['email']) ? mysqli_real_escape_string($conectar, $_POST['email']) : $admin_atual['email'];
        $nivel_acesso = isset($_POST['nivel_acesso']) && $_POST['nivel_acesso'] !== '' ? intval($_POST['nivel_acesso']) : $admin_atual['nivel_acesso'];
        $nova_senha = isset($_POST['nova_senha']) ? mysqli_real_escape_string($conectar, $_POST['nova_senha']) : '';
        
        // Verificar se já existe outro administrador com o mesmo email
        $sql_check = "SELECT COUNT(*) as total FROM administrador WHERE email = '$email' AND codigo != $codigo";
        $resultado_check = mysqli_query($conectar, $sql_check);
        $row_check = mysqli_fetch_assoc($resultado_check);
        
        if ($row_check['total'] > 0) {
            $mensagem = "Já existe um administrador com este email.";
            $tipo_mensagem = "warning";
        } else {
            // Construir query de atualização
            if (!empty($nova_senha)) {
                $senha_hash = md5($nova_senha);
                $sql = "UPDATE administrador SET nome='$nome', email='$email', senha='$senha_hash', nivel_acesso=$nivel_acesso WHERE codigo = $codigo";
            } else {
                $sql = "UPDATE administrador SET nome='$nome', email='$email', nivel_acesso=$nivel_acesso WHERE codigo = $codigo";
            }
            
            $resultado = mysqli_query($conectar, $sql);
            
            if ($resultado) {
                if (mysqli_affected_rows($conectar) > 0) {
                    $mensagem = "Administrador alterado com sucesso!";
                    $tipo_mensagem = "success";
                } else {
                    $mensagem = "Nenhuma alteração foi feita (dados idênticos aos atuais).";
                    $tipo_mensagem = "warning";
                }
            } else {
                $mensagem = "Falha ao alterar o administrador: " . mysqli_error($conectar);
                $tipo_mensagem = "error";
            }
        }
    } else {
        $mensagem = "Administrador não encontrado com o código informado.";
        $tipo_mensagem = "error";
    }
    
    // Se for requisição AJAX, retornar apenas a mensagem
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo $mensagem;
        exit;
    }
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pós Cadastro - Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php include 'admin_styles.php'; ?>
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: 1.1rem;
        }
        .alert-success {
            color: #fff;
            background-color: rgba(76, 175, 80, 0.8);
            border-color: rgba(76, 175, 80, 0.5);
        }
        .alert-error {
            color: #fff;
            background-color: rgba(244, 67, 54, 0.8);
            border-color: rgba(244, 67, 54, 0.5);
        }
        .alert-warning {
            color: #fff;
            background-color: rgba(255, 152, 0, 0.8);
            border-color: rgba(255, 152, 0, 0.5);
        }
        .menu-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 1rem; 
            margin-top: 1.5rem; 
        }
        h2 { 
            font-size: 1.3rem; 
            margin-top: 2rem; 
            margin-bottom: 1rem; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div>👤 Cadastro de Administrador</div>
            <div>
                <a href="cadastro_admin.html">➕ Novo</a>
                <a href="listar_admins.php">📋 Listar</a>
                <a href="alterar_admin.html">✏️ Alterar</a>
                <a href="excluir_admin.html">🗑️ Excluir</a>
                <a href="../loja/menu.php">🏠 Menu</a>
            </div>
        </div>
        <div class="card">
            <?php if (isset($mensagem)): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>
            
            <h1>Navegação Rápida</h1>
            <div class="menu-grid">
                <a href="cadastro_admin.html" class="btn">🔄 Cadastrar Novo Admin</a>
                <a href="listar_admins.php" class="btn">📋 Listar Administradores</a>
                <a href="alterar_admin.html" class="btn">✏️ Alterar Admin</a>
                <a href="excluir_admin.html" class="btn">🗑️ Excluir Admin</a>
            </div>
            
            <h2 style="margin-top: 2rem; margin-bottom: 1rem;">Outras Áreas</h2>
            <div class="menu-grid">
                <a href="../produto/cadastro_produto.html" class="btn">📦 Produtos</a>
                <a href="../marca/cadastro_marca.html" class="btn">🏷️ Marcas</a>
                <a href="../categoria/cadastro_categoria.html" class="btn">📂 Categorias</a>
                <a href="../loja/menu.php" class="btn">🏠 Menu Principal</a>
            </div>
        </div>
    </div>
</body>
</html>