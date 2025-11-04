<?php
date_default_timezone_set('America/Sao_Paulo');
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conectar, "latin1");

function sanitize($conn, $field) {
    return mysqli_real_escape_string($conn, isset($_POST[$field]) ? $_POST[$field] : '');
}

// Gravar (cadastrar)
if (isset($_POST['cadastrar'])) {
    $nome = sanitize($conectar, 'nome');
    $modelo = sanitize($conectar, 'modelo');
    $cor = sanitize($conectar, 'cor');
    $codmarca = intval($_POST['codmarca']);
    $codcategoria = intval($_POST['codcategoria']);
    $descricao = sanitize($conectar, 'descricao');
    $especificacoes = sanitize($conectar, 'especificacoes');
    $preco = floatval(str_replace(',', '.', $_POST['preco']));
    $estoque = intval($_POST['estoque']);
    $estoque_minimo = intval($_POST['estoque_minimo']);
    $ativo = isset($_POST['ativo']) ? intval($_POST['ativo']) : 1; // Padrão: ativo (1)
    $data_cadastro = date('Y-m-d H:i:s');

    // Upload de fotos (salva nomes gerados)
    $diretorio = __DIR__ . DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR;
    if (!is_dir($diretorio)) { @mkdir($diretorio, 0777, true); }
    $foto1 = '';
    $foto2 = '';
    if (isset($_FILES['foto1']) && is_uploaded_file($_FILES['foto1']['tmp_name'])) {
        $ext1 = strtolower(pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION));
        $nome1 = md5(uniqid('', true)) . '.' . $ext1;
        if (move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio . $nome1)) { $foto1 = $nome1; }
    }
    if (isset($_FILES['foto2']) && is_uploaded_file($_FILES['foto2']['tmp_name'])) {
        $ext2 = strtolower(pathinfo($_FILES['foto2']['name'], PATHINFO_EXTENSION));
        $nome2 = md5(uniqid('', true)) . '.' . $ext2;
        if (move_uploaded_file($_FILES['foto2']['tmp_name'], $diretorio . $nome2)) { $foto2 = $nome2; }
    }

    // Verificações simples
    if ($preco < 0) { $preco = 0; }
    if ($estoque < 0) { $estoque = 0; }
    if ($estoque_minimo < 0) { $estoque_minimo = 0; }

    // Checar existência por nome+modelo
    $sql_check = "SELECT COUNT(*) as total FROM produto WHERE nome='$nome' AND modelo='$modelo'";
    $resultado_check = mysqli_query($conectar, $sql_check);
    $row_check = $resultado_check ? mysqli_fetch_assoc($resultado_check) : array('total' => 0);

    if ($row_check['total'] > 0) {
        $mensagem = "Já existe um produto com este nome e modelo.";
        $tipo_mensagem = "warning";
    } else {
        $sql = "INSERT INTO produto (nome, modelo, cor, codmarca, codcategoria, descricao, especificacoes, preco, estoque, estoque_minimo, ativo, foto1, foto2, data_cadastro) VALUES ('$nome', '$modelo', '$cor', $codmarca, $codcategoria, '$descricao', '$especificacoes', $preco, $estoque, $estoque_minimo, $ativo, '$foto1', '$foto2', '$data_cadastro')";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado) {
            $mensagem = "Produto cadastrado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Falha ao cadastrar o produto: " . mysqli_error($conectar);
            $tipo_mensagem = "error";
        }
    }
}

// Excluir
if (isset($_POST['excluir'])) {
    $codigo = intval($_POST['codigo']);
    $sql = "DELETE FROM produto WHERE codigo = $codigo";
    $resultado = mysqli_query($conectar, $sql);
    if ($resultado && mysqli_affected_rows($conectar) > 0) {
        $mensagem = "Produto excluído com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Produto não encontrado ou erro ao excluir.";
        $tipo_mensagem = "warning";
    }
}

// Alterar
if (isset($_POST['alterar'])) {
    $codigo = intval($_POST['codigo']);
    
    // Buscar dados atuais do produto
    $sql_atual = "SELECT * FROM produto WHERE codigo = $codigo";
    $resultado_atual = mysqli_query($conectar, $sql_atual);
    
    if ($resultado_atual && mysqli_num_rows($resultado_atual) > 0) {
        $produto_atual = mysqli_fetch_assoc($resultado_atual);
        
        // Se o campo estiver vazio, usa o valor atual do banco
        $nome = !empty($_POST['nome']) ? sanitize($conectar, 'nome') : $produto_atual['nome'];
        $modelo = !empty($_POST['modelo']) ? sanitize($conectar, 'modelo') : $produto_atual['modelo'];
        $cor = !empty($_POST['cor']) ? sanitize($conectar, 'cor') : $produto_atual['cor'];
        $codmarca = !empty($_POST['codmarca']) ? intval($_POST['codmarca']) : $produto_atual['codmarca'];
        $codcategoria = !empty($_POST['codcategoria']) ? intval($_POST['codcategoria']) : $produto_atual['codcategoria'];
        $descricao = !empty($_POST['descricao']) ? sanitize($conectar, 'descricao') : $produto_atual['descricao'];
        $especificacoes = !empty($_POST['especificacoes']) ? sanitize($conectar, 'especificacoes') : $produto_atual['especificacoes'];
        $preco = !empty($_POST['preco']) ? floatval(str_replace(',', '.', $_POST['preco'])) : $produto_atual['preco'];
        $estoque = isset($_POST['estoque']) && $_POST['estoque'] !== '' ? intval($_POST['estoque']) : $produto_atual['estoque'];
        $estoque_minimo = isset($_POST['estoque_minimo']) && $_POST['estoque_minimo'] !== '' ? intval($_POST['estoque_minimo']) : $produto_atual['estoque_minimo'];
        $ativo = isset($_POST['ativo']) && $_POST['ativo'] !== '' ? intval($_POST['ativo']) : $produto_atual['ativo'];
    // Upload opcional ao alterar
    $diretorio = __DIR__ . DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR;
    if (!is_dir($diretorio)) { @mkdir($diretorio, 0777, true); }
    $setFotos = '';
    if (isset($_FILES['foto1']) && is_uploaded_file($_FILES['foto1']['tmp_name'])) {
        $ext1 = strtolower(pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION));
        $nome1 = md5(uniqid('', true)) . '.' . $ext1;
        if (move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio . $nome1)) { $setFotos .= ", foto1='$nome1'"; }
    }
    if (isset($_FILES['foto2']) && is_uploaded_file($_FILES['foto2']['tmp_name'])) {
        $ext2 = strtolower(pathinfo($_FILES['foto2']['name'], PATHINFO_EXTENSION));
        $nome2 = md5(uniqid('', true)) . '.' . $ext2;
        if (move_uploaded_file($_FILES['foto2']['tmp_name'], $diretorio . $nome2)) { $setFotos .= ", foto2='$nome2'"; }
    }

    $sql_check = "SELECT COUNT(*) as total FROM produto WHERE nome='$nome' AND modelo='$modelo' AND codigo != $codigo";
    $resultado_check = mysqli_query($conectar, $sql_check);
    $row_check = $resultado_check ? mysqli_fetch_assoc($resultado_check) : array('total' => 0);

    if ($row_check['total'] > 0) {
        $mensagem = "Já existe outro produto com este nome e modelo.";
        $tipo_mensagem = "warning";
    } else {
        $sql = "UPDATE produto SET nome='$nome', modelo='$modelo', cor='$cor', codmarca=$codmarca, codcategoria=$codcategoria, descricao='$descricao', especificacoes='$especificacoes', preco=$preco, estoque=$estoque, estoque_minimo=$estoque_minimo, ativo=$ativo$setFotos WHERE codigo=$codigo";
        $resultado = mysqli_query($conectar, $sql);
        if ($resultado) {
            if (mysqli_affected_rows($conectar) > 0) {
                $mensagem = "Produto alterado com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Nenhuma alteração realizada (dados idênticos aos atuais).";
                $tipo_mensagem = "warning";
            }
        } else {
            $mensagem = "Falha ao alterar o produto: " . mysqli_error($conectar);
            $tipo_mensagem = "error";
        }
    }
} else {
    $mensagem = "Produto não encontrado com o código informado.";
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
    <title>Resultado - Produto</title>
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
            <div>📦 Cadastro de Produto</div>
            <div>
                <a href="cadastro_produto.html">➕ Novo</a>
                <a href="listar_produtos.php">📋 Listar</a>
                <a href="alterar_produto.html">✏️ Alterar</a>
                <a href="excluir_produto.html">🗑️ Excluir</a>
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
                <a class="btn" href="cadastro_produto.html">🔄 Cadastrar Novo Produto</a>
                <a class="btn" href="listar_produtos.php">📋 Listar Produtos</a>
                <a class="btn" href="alterar_produto.html">✏️ Alterar Produto</a>
                <a class="btn" href="excluir_produto.html">🗑️ Excluir Produto</a>
            </div>
            
            <h2>Outras Áreas</h2>
            <div class="menu-grid">
                <a class="btn" href="../marca/cadastro_marca.html">🏷️ Marcas</a>
                <a class="btn" href="../categoria/cadastro_categoria.html">📂 Categorias</a>
                <a class="btn" href="../admin/cadastro_admin.html">👤 Administradores</a>
                <a class="btn" href="../loja/menu.php">🏠 Menu Principal</a>
            </div>
        </div>
    </div>
</body>
</html>


