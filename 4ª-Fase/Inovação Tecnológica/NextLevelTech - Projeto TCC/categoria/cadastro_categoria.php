<?php
date_default_timezone_set('America/Sao_Paulo');
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conectar, "latin1");

// Processar cadastro de categoria
if (isset($_POST['cadastrar'])) {
    $nome = mysqli_real_escape_string($conectar, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conectar, $_POST['descricao']);

    // Verificar se a categoria já existe
    $sql_check = "SELECT COUNT(*) as total FROM categoria WHERE nome = '$nome'";
    $resultado_check = mysqli_query($conectar, $sql_check);
    $row_check = mysqli_fetch_assoc($resultado_check);

    if ($row_check['total'] > 0) {
        $mensagem = "Já existe uma categoria com este nome.";
        $tipo_mensagem = "warning";
    } else {
        // Inserir nova categoria
        $sql = "INSERT INTO categoria (nome, descricao) VALUES ('$nome', '$descricao')";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado) {
            $mensagem = "Categoria cadastrada com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Falha ao cadastrar a categoria: " . mysqli_error($conectar);
            $tipo_mensagem = "error";
        }
    }
}

// Processar exclusão de categoria
if (isset($_POST['excluir'])) {
    $codigo = intval($_POST['codigo']);

    // Verificar se a categoria está sendo usada em produtos
    $sql_check = "SELECT COUNT(*) as total FROM produto WHERE codcategoria = $codigo";
    $resultado_check = mysqli_query($conectar, $sql_check);
    $row_check = mysqli_fetch_assoc($resultado_check);

    if ($row_check['total'] > 0) {
        $mensagem = "Não é possível excluir a categoria. Existem produtos vinculados a ela.";
        $tipo_mensagem = "error";
    } else {
        // Excluir categoria
        $sql = "DELETE FROM categoria WHERE codigo = $codigo";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado) {
            $mensagem = "Categoria excluída com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Falha ao excluir a categoria: " . mysqli_error($conectar);
            $tipo_mensagem = "error";
        }
    }
}

// Processar alteração de categoria
if (isset($_POST['alterar'])) {
    $codigo = intval($_POST['codigo']);
    
    // Buscar dados atuais da categoria
    $sql_atual = "SELECT * FROM categoria WHERE codigo = $codigo";
    $resultado_atual = mysqli_query($conectar, $sql_atual);
    
    if ($resultado_atual && mysqli_num_rows($resultado_atual) > 0) {
        $cat_atual = mysqli_fetch_assoc($resultado_atual);
        
        // Se o campo estiver vazio, usa o valor atual do banco
        $nome = !empty($_POST['nome']) ? mysqli_real_escape_string($conectar, $_POST['nome']) : $cat_atual['nome'];
        $descricao = !empty($_POST['descricao']) ? mysqli_real_escape_string($conectar, $_POST['descricao']) : $cat_atual['descricao'];
        
        // Verificar se já existe outra categoria com o mesmo nome
        $sql_check = "SELECT COUNT(*) as total FROM categoria WHERE nome = '$nome' AND codigo != $codigo";
        $resultado_check = mysqli_query($conectar, $sql_check);
        $row_check = mysqli_fetch_assoc($resultado_check);
        
        if ($row_check['total'] > 0) {
            $mensagem = "Já existe uma categoria com este nome.";
            $tipo_mensagem = "warning";
        } else {
            // Alterar categoria
            $sql = "UPDATE categoria SET nome='$nome', descricao='$descricao' WHERE codigo = $codigo";
            $resultado = mysqli_query($conectar, $sql);
            
            if ($resultado) {
                if (mysqli_affected_rows($conectar) > 0) {
                    $mensagem = "Categoria alterada com sucesso!";
                    $tipo_mensagem = "success";
                } else {
                    $mensagem = "Nenhuma alteração foi feita (dados idênticos aos atuais).";
                    $tipo_mensagem = "warning";
                }
            } else {
                $mensagem = "Falha ao alterar a categoria: " . mysqli_error($conectar);
                $tipo_mensagem = "error";
            }
        }
    } else {
        $mensagem = "Categoria não encontrada com o código informado.";
        $tipo_mensagem = "error";
    }
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pós Cadastro - Categoria</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php include '../admin/admin_styles.php'; ?>
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
            <div>📂 Cadastro de Categoria</div>
            <div>
                <a href="cadastro_categoria.html">➕ Novo</a>
                <a href="listar_categorias.php">📋 Listar</a>
                <a href="alterar_categoria.html">✏️ Alterar</a>
                <a href="excluir_categoria.html">🗑️ Excluir</a>
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
                <a href="cadastro_categoria.html" class="btn">🔄 Cadastrar Nova Categoria</a>
                <a href="listar_categorias.php" class="btn">📋 Listar Categorias</a>
                <a href="alterar_categoria.html" class="btn">✏️ Alterar Categoria</a>
                <a href="excluir_categoria.html" class="btn">🗑️ Excluir Categoria</a>
            </div>
            
            <h2 style="margin-top: 2rem; margin-bottom: 1rem;">Outras Áreas</h2>
            <div class="menu-grid">
                <a href="../produto/cadastro_produto.html" class="btn">📦 Produtos</a>
                <a href="../marca/cadastro_marca.html" class="btn">🏷️ Marcas</a>
                <a href="../admin/cadastro_admin.html" class="btn">👤 Administradores</a>
                <a href="../loja/menu.php" class="btn">🏠 Menu Principal</a>
            </div>
        </div>
    </div>
</body>
</html>