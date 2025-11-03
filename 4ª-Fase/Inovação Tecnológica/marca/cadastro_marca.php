<?php
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conectar, "latin1");

// Processar cadastro de marca
if (isset($_POST['cadastrar'])) {
    $nome = mysqli_real_escape_string($conectar, $_POST['nome']);
    $pais = mysqli_real_escape_string($conectar, $_POST['pais']);

    // Inserir nova marca
    $sql = "INSERT INTO marca (nome, pais) VALUES ('$nome', '$pais')";
    $resultado = mysqli_query($conectar, $sql);

    if ($resultado) {
        $mensagem = "Marca cadastrada com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Falha ao cadastrar a marca: " . mysqli_error($conectar);
        $tipo_mensagem = "error";
    }
}

// Processar exclusão de marca
if (isset($_POST['excluir'])) {
    $codigo = intval($_POST['codigo']);

    // Verificar se a marca está sendo usada em produtos
    $sql_check = "SELECT COUNT(*) as total FROM produto WHERE codmarca = $codigo";
    $resultado_check = mysqli_query($conectar, $sql_check);
    $row_check = mysqli_fetch_assoc($resultado_check);

    if ($row_check['total'] > 0) {
        $mensagem = "Não é possível excluir a marca. Existem produtos vinculados a ela.";
        $tipo_mensagem = "error";
    } else {
        // Excluir marca
        $sql = "DELETE FROM marca WHERE codigo = $codigo";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado) {
            $mensagem = "Marca excluída com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Falha ao excluir a marca: " . mysqli_error($conectar);
            $tipo_mensagem = "error";
        }
    }
}

// Processar alteração de marca
if (isset($_POST['alterar'])) {
    $codigo = intval($_POST['codigo']);
    $nome = mysqli_real_escape_string($conectar, $_POST['nome']);
    $pais = mysqli_real_escape_string($conectar, $_POST['pais']);

    // Alterar marca
    $sql = "UPDATE marca SET nome='$nome', pais='$pais' WHERE codigo = $codigo";
    $resultado = mysqli_query($conectar, $sql);

    if ($resultado) {
        if (mysqli_affected_rows($conectar) > 0) {
            $mensagem = "Marca alterada com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Nenhuma alteração foi feita ou marca não encontrada.";
            $tipo_mensagem = "warning";
        }
    } else {
        $mensagem = "Falha ao alterar a marca: " . mysqli_error($conectar);
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
    <title>Pós Cadastro - Marca</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert-error {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }
    </style>
</head>
<body>
    <div class="cosmic-bg"></div>
    <div class="container">
        <nav class="nav-menu">
            <ul>
                <li><a href="../loja/menu.php">Menu</a></li>
            </ul>
        </nav>
        <div class="card">
            <?php if (isset($mensagem)): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>
            
            <h1>Voltar Para:</h1>
            <div class="menu-grid">
                <a href="../Produto/cadastro_produto.html" class="btn">Cadastrar Produto</a>
                <a href="../Marca/cadastro_marca.html" class="btn">Cadastrar Marca</a>
                <a href="../Categoria/cadastro_categoria.html" class="btn">Cadastrar Categoria</a>
                <a href="../Admin/cadastro_admin.html" class="btn">Cadastrar Administrador</a>
            </div>
        </div>
    </div>
</body> 
</html>