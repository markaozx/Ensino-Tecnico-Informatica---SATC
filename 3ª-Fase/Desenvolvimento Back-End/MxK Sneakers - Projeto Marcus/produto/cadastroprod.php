<?php
//conectar com o servidor e banco
$conectar = mysql_connect('localhost','root','');
$banco    = mysql_select_db("loja");

if (isset($_POST['gravar']))
{
    $codigo            = $_POST['codigo'];
    $descricao         = $_POST['descricao'];
    $codcategoria      = $_POST['codcategoria'];
    $codclassificacao  = $_POST['codclassificacao'];
    $codmarca          = $_POST['codmarca'];
    $cor               = $_POST['cor'];
    $tamanho           = $_POST['tamanho'];
    $preco             = $_POST['preco'];
    $foto1             = $_FILES['foto1'];
    $foto2             = $_FILES['foto2'];

    // Criar pasta se não existir
    $diretorio = "fotos/";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    // Mover arquivos de imagem
    $extensao1 = strtolower(substr($_FILES['foto1']['name'], -4));
    $novo_nome1 = md5(time().$extensao1);
    if (move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio.$novo_nome1)) {
        // Sucesso ao mover
    } else {
        echo "Erro ao mover a foto 1.";
    }

    $extensao2 = strtolower(substr($_FILES['foto2']['name'], -6));
    $novo_nome2 = md5(time().$extensao2);
    if (move_uploaded_file($_FILES['foto2']['tmp_name'], $diretorio.$novo_nome2)) {
        // Sucesso ao mover
    } else {
        echo "Erro ao mover a foto 2.";
    }

    $sql = "INSERT INTO produto (descricao,codcategoria,codtipo,codmarca, cor,tamanho,preco,foto1,foto2)
            values ('$descricao','$codcategoria','$codclassificacao','$codmarca','$cor','$tamanho','$preco','$novo_nome1','$novo_nome2')";
    
    $resultado = mysql_query($sql);

    if (!$resultado) {
        echo "Falha ao gravar os dados informados: " . mysql_error(); // Mensagem de erro detalhada
    } else {
        echo "Dados informados cadastrados com sucesso";
    }
}

if (isset($_POST['excluir']))
{
   $codigo            = $_POST['codigo'];
   $sql = "DELETE FROM produto WHERE codigo = '$codigo'";
   $resultado = mysql_query($sql);

   if ($resultado === TRUE) {
       echo 'Exclusao realizada com Sucesso';
   } else {
       echo 'Erro ao excluir dados.';
   }
}

if (isset($_POST['alterar']))
{
   $codigo            = $_POST['codigo'];
   $descricao         = $_POST['descricao'];
   $preco             = $_POST['preco'];
   $sql = "UPDATE produto SET descricao='$descricao',preco='$preco' WHERE codigo = '$codigo'";
   $resultado = mysql_query($sql);

   if ($resultado === TRUE) {
       echo 'Dados alterados com Sucesso';
   } else {
       echo 'Erro ao alterar dados.';
   }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pós Cadastro</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="cosmic-bg"></div>
    <div class="container">
        <nav class="nav-menu">
            <ul>
                <li><a href="../loja/menu.html">Menu</a></li>
            </ul>
        </nav>
        <div class="card">
            <h1>Voltar Para:</h1>
            <div class="menu-grid">
                <a href="../produto/cadastroprod.html" class="btn">Cadastrar Produto</a>
                <a href="../marca/cadastro_marca.html" class="btn">Cadastrar Marca</a>
                <a href="../categoria/cadastro_categoria.html" class="btn">Cadastrar Categoria</a>
                <a href="../tipo/cadastro_tipo.html" class="btn">Cadastrar Tipo</a>
            </div>
        </div>
    </div>
</body>
</html>
