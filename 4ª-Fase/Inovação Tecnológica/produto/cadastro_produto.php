<?php
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conectar, "latin1");

function sanitize($conn, $field) {
    return mysqli_real_escape_string($conn, isset($_POST[$field]) ? $_POST[$field] : '');
}

// Gravar (cadastrar)
if (isset($_POST['gravar'])) {
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
    $ativo = intval($_POST['ativo']);
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
    $ativo = intval($_POST['ativo']);
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
                $mensagem = "Nenhuma alteração realizada ou produto não encontrado.";
                $tipo_mensagem = "warning";
            }
        } else {
            $mensagem = "Falha ao alterar o produto: " . mysqli_error($conectar);
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
    <title>Resultado - Produto</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #1a1a2e; color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: rgba(255,255,255,0.08); padding: 2rem; border-radius: 16px; width: min(90%, 700px); text-align: center; border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(6px); }
        .title { font-family: 'Orbitron', monospace; font-size: 1.6rem; margin-bottom: 1rem; }
        .msg { margin: 1rem 0; font-size: 1rem; }
        .btn { display: inline-block; margin: .5rem; padding: .75rem 1.25rem; border-radius: 10px; background: linear-gradient(45deg, #ff6b6b, #4ecdc4); color: #fff; text-decoration: none; font-weight: 600; }
        .btn:hover { filter: brightness(1.05); }
    </style>
    <meta http-equiv="refresh" content="3; URL=cadastro_produto.html" />
</head>
<body>
    <div class="card">
        <div class="title">Cadastro de Produto</div>
        <div class="msg"><?php echo isset($mensagem) ? $mensagem : 'Sem ação realizada.'; ?></div>
        <div>
            <a class="btn" href="cadastro_produto.html">Voltar ao cadastro</a>
            <a class="btn" href="listar_produtos.php">Listar produtos</a>
        </div>
    </div>
</body>
</html>


