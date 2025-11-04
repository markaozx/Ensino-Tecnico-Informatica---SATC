<?php
date_default_timezone_set('America/Sao_Paulo');
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    die("Erro de conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conectar, "latin1");

// Retornar dados do produto em JSON para requisições AJAX
if (isset($_GET['codigo']) && isset($_GET['ajax'])) {
    $codigo = intval($_GET['codigo']);
    $sql = "SELECT p.*, m.nome AS marca_nome, c.nome AS categoria_nome 
            FROM produto p 
            LEFT JOIN marca m ON p.codmarca = m.codigo 
            LEFT JOIN categoria c ON p.codcategoria = c.codigo 
            WHERE p.codigo = $codigo";
    $resultado = mysqli_query($conectar, $sql);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $produto = mysqli_fetch_assoc($resultado);
        
        // Buscar marcas e categorias para os selects
        $marcas = [];
        $categorias = [];
        
        $sql_marcas = "SELECT codigo, nome FROM marca ORDER BY nome";
        $result_marcas = mysqli_query($conectar, $sql_marcas);
        while ($marca = mysqli_fetch_assoc($result_marcas)) {
            $marcas[] = $marca;
        }
        
        $sql_categorias = "SELECT codigo, nome FROM categoria ORDER BY nome";
        $result_categorias = mysqli_query($conectar, $sql_categorias);
        while ($categoria = mysqli_fetch_assoc($result_categorias)) {
            $categorias[] = $categoria;
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'produto' => $produto,
            'marcas' => $marcas,
            'categorias' => $categorias
        ]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Produto não encontrado']);
        exit;
    }
}

// Verificar se é uma requisição AJAX para edição inline
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
    $codigo = intval($_POST['codigo']);
    
    if ($codigo > 0) {
        // Buscar dados atuais do produto
        $sql_atual = "SELECT * FROM produto WHERE codigo = $codigo";
        $resultado_atual = mysqli_query($conectar, $sql_atual);
        
        if ($resultado_atual && mysqli_num_rows($resultado_atual) > 0) {
            $produto_atual = mysqli_fetch_assoc($resultado_atual);
            
            // Atualizar apenas os campos fornecidos
            $nome = isset($_POST['nome']) ? mysqli_real_escape_string($conectar, $_POST['nome']) : $produto_atual['nome'];
            $modelo = isset($_POST['modelo']) ? mysqli_real_escape_string($conectar, $_POST['modelo']) : $produto_atual['modelo'];
            $codmarca = isset($_POST['codmarca']) ? intval($_POST['codmarca']) : $produto_atual['codmarca'];
            $codcategoria = isset($_POST['codcategoria']) ? intval($_POST['codcategoria']) : $produto_atual['codcategoria'];
            $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : $produto_atual['preco'];
            $estoque = isset($_POST['estoque']) ? intval($_POST['estoque']) : $produto_atual['estoque'];
            $ativo = isset($_POST['ativo']) ? intval($_POST['ativo']) : $produto_atual['ativo'];
            
            $sql = "UPDATE produto SET nome='$nome', modelo='$modelo', codmarca=$codmarca, codcategoria=$codcategoria, preco=$preco, estoque=$estoque, ativo=$ativo WHERE codigo=$codigo";
            $resultado = mysqli_query($conectar, $sql);
            
            if ($resultado) {
                if (mysqli_affected_rows($conectar) > 0) {
                    echo "Produto atualizado com sucesso!";
                } else {
                    echo "Nenhuma alteração foi feita.";
                }
            } else {
                echo "Erro ao atualizar produto: " . mysqli_error($conectar);
            }
        } else {
            echo "Produto não encontrado!";
        }
    } else {
        echo "Código de produto inválido!";
    }
    exit; // Sair aqui para requisições AJAX
}

if (isset($_POST['alterar'])) {
    $codigo = intval($_POST['codigo']);
    
    // Buscar dados atuais do produto
    $sql_atual = "SELECT * FROM produto WHERE codigo = $codigo";
    $resultado_atual = mysqli_query($conectar, $sql_atual);
    
    if ($resultado_atual && mysqli_num_rows($resultado_atual) > 0) {
        $produto_atual = mysqli_fetch_assoc($resultado_atual);
        
        // Se o campo estiver vazio, usa o valor atual do banco
        $nome = !empty($_POST['nome']) ? mysqli_real_escape_string($conectar, $_POST['nome']) : $produto_atual['nome'];
        $modelo = !empty($_POST['modelo']) ? mysqli_real_escape_string($conectar, $_POST['modelo']) : $produto_atual['modelo'];
        $cor = !empty($_POST['cor']) ? mysqli_real_escape_string($conectar, $_POST['cor']) : $produto_atual['cor'];
        $codmarca = !empty($_POST['codmarca']) ? intval($_POST['codmarca']) : $produto_atual['codmarca'];
        $codcategoria = !empty($_POST['codcategoria']) ? intval($_POST['codcategoria']) : $produto_atual['codcategoria'];
        $descricao = !empty($_POST['descricao']) ? mysqli_real_escape_string($conectar, $_POST['descricao']) : $produto_atual['descricao'];
        $especificacoes = !empty($_POST['especificacoes']) ? mysqli_real_escape_string($conectar, $_POST['especificacoes']) : $produto_atual['especificacoes'];
        $preco = !empty($_POST['preco']) ? floatval(str_replace(',', '.', $_POST['preco'])) : $produto_atual['preco'];
        $estoque = isset($_POST['estoque']) && $_POST['estoque'] !== '' ? intval($_POST['estoque']) : $produto_atual['estoque'];
        $estoque_minimo = isset($_POST['estoque_minimo']) && $_POST['estoque_minimo'] !== '' ? intval($_POST['estoque_minimo']) : $produto_atual['estoque_minimo'];
        $ativo = isset($_POST['ativo']) && $_POST['ativo'] !== '' ? intval($_POST['ativo']) : $produto_atual['ativo'];
        
        // Upload opcional de fotos
        $diretorio = __DIR__ . DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR;
        if (!is_dir($diretorio)) { @mkdir($diretorio, 0777, true); }
        
        $setFotos = '';
        if (isset($_FILES['foto1']) && is_uploaded_file($_FILES['foto1']['tmp_name'])) {
            $ext1 = strtolower(pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION));
            $nome1 = md5(uniqid('', true)) . '.' . $ext1;
            if (move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio . $nome1)) { 
                $setFotos .= ", foto1='$nome1'"; 
            }
        }
        if (isset($_FILES['foto2']) && is_uploaded_file($_FILES['foto2']['tmp_name'])) {
            $ext2 = strtolower(pathinfo($_FILES['foto2']['name'], PATHINFO_EXTENSION));
            $nome2 = md5(uniqid('', true)) . '.' . $ext2;
            if (move_uploaded_file($_FILES['foto2']['tmp_name'], $diretorio . $nome2)) { 
                $setFotos .= ", foto2='$nome2'"; 
            }
        }

        // Verificar se já existe outro produto com mesmo nome e modelo
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
} else {
    $mensagem = "Nenhuma ação realizada.";
    $tipo_mensagem = "warning";
}

mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado - Alterar Produto</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #1a1a2e; color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: rgba(255,255,255,0.08); padding: 2rem; border-radius: 16px; width: min(90%, 700px); text-align: center; border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(6px); }
        .title { font-family: 'Orbitron', monospace; font-size: 1.6rem; margin-bottom: 1rem; }
        .msg { margin: 1rem 0; font-size: 1rem; }
        .btn { display: inline-block; margin: .5rem; padding: .75rem 1.25rem; border-radius: 10px; background: linear-gradient(45deg, #ff6b6b, #4ecdc4); color: #fff; text-decoration: none; font-weight: 600; }
        .btn:hover { filter: brightness(1.05); }
        .alert-success { color: #4caf50; }
        .alert-warning { color: #ff9800; }
        .alert-error { color: #f44336; }
    </style>
    <meta http-equiv="refresh" content="3; URL=alterar_produto.html" />
</head>
<body>
    <div class="card">
        <div class="title">Alterar Produto</div>
        <div class="msg alert-<?php echo isset($tipo_mensagem) ? $tipo_mensagem : 'warning'; ?>">
            <?php echo isset($mensagem) ? $mensagem : 'Sem ação realizada.'; ?>
        </div>
        <div>
            <a class="btn" href="alterar_produto.html">Voltar</a>
            <a class="btn" href="listar_produtos.php">Listar produtos</a>
        </div>
    </div>
</body>
</html>