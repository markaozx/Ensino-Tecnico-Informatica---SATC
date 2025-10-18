<?php
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');

if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}

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
    $nome = mysqli_real_escape_string($conectar, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conectar, $_POST['descricao']);

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
                $mensagem = "Nenhuma alteração foi feita ou categoria não encontrada.";
                $tipo_mensagem = "warning";
            }
        } else {
            $mensagem = "Falha ao alterar a categoria: " . mysqli_error($conectar);
            $tipo_mensagem = "error";
        }
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #fff;
            padding: 20px;
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-menu {
            margin-bottom: 2rem;
        }

        .nav-menu ul {
            list-style: none;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .nav-menu a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        h1 {
            font-family: 'Orbitron', monospace;
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            font-size: 2.5rem;
            color: #fff;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 1rem 2rem;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', monospace;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #ff5252, #26a69a);
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