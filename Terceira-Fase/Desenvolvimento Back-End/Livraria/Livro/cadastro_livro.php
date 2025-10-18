<?php
//conectar com o servidor e banco
$conectar = mysql_connect('localhost','root','');
$banco    = mysql_select_db("livraria");

if (isset($_POST['gravar']))
{
    $codigo       = $_POST['codigo'];
    $titulo       = $_POST['titulo'];
    $nrpaginas    = $_POST['nrpaginas'];
    $ano          = $_POST['ano'];
    $codautor     = $_POST['codautor'];
    $codcategoria = $_POST['codcategoria'];
    $codeditora   = $_POST['codeditora'];
    $resenha      = $_POST['resenha'];
    $preco        = $_POST['preco'];
    $fotocapa1    = $_FILES['fotocapa1'];
    $fotocapa2    = $_FILES['fotocapa2'];

    // Criar pasta se não existir
    $diretorio = "fotos/";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    // Mover arquivos de imagem
    $extensao1 = strtolower(pathinfo($_FILES['fotocapa1']['name'], PATHINFO_EXTENSION));
    $novo_nome1 = md5(time().rand()) . '.' . $extensao1;
    if (move_uploaded_file($_FILES['fotocapa1']['tmp_name'], $diretorio.$novo_nome1)) {
        // Sucesso ao mover
    } else {
        echo "Erro ao mover a foto capa 1.";
    }

    $extensao2 = strtolower(pathinfo($_FILES['fotocapa2']['name'], PATHINFO_EXTENSION));
    $novo_nome2 = md5(time().rand()) . '.' . $extensao2;
    if (move_uploaded_file($_FILES['fotocapa2']['tmp_name'], $diretorio.$novo_nome2)) {
        // Sucesso ao mover
    } else {
        echo "Erro ao mover a foto capa 2.";
    }

    $sql = "INSERT INTO livro (titulo, nrpaginas, ano, codautor, codcategoria, codeditora, resenha, preco, fotocapa1, fotocapa2)
            VALUES ('$titulo', '$nrpaginas', '$ano', '$codautor', '$codcategoria', '$codeditora', '$resenha', '$preco', '$novo_nome1', '$novo_nome2')";
    
    $resultado = mysql_query($sql);

    if (!$resultado) {
        echo "Falha ao gravar os dados informados: " . mysql_error(); // Mensagem de erro detalhada
    } else {
        echo "Dados informados cadastrados com sucesso";
    }
}

if (isset($_POST['excluir']))
{
   $codigo = $_POST['codigo'];
   $sql = "DELETE FROM livro WHERE codigo = '$codigo'";
   $resultado = mysql_query($sql);

   if ($resultado === TRUE) {
       echo 'Exclusao realizada com Sucesso';
   } else {
       echo 'Erro ao excluir dados.';
   }
}

if (isset($_POST['alterar']))
{
   $codigo       = $_POST['codigo'];
   $titulo       = $_POST['titulo'];
   $nrpaginas    = $_POST['nrpaginas'];
   $ano          = $_POST['ano'];
   $codautor     = $_POST['codautor'];
   $codcategoria = $_POST['codcategoria'];
   $codeditora   = $_POST['codeditora'];
   $resenha      = $_POST['resenha'];
   $preco        = $_POST['preco'];

   $sql = "UPDATE livro SET titulo='$titulo', nrpaginas='$nrpaginas', ano='$ano', codautor='$codautor', codcategoria='$codcategoria', codeditora='$codeditora', resenha='$resenha', preco='$preco' WHERE codigo = '$codigo'";
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
                <a href="../Livro/cadastro_livro.html" class="btn">Cadastrar Livro</a>
                <a href="../Editora/cadastro_editora.html" class="btn">Cadastrar Editora</a>
                <a href="../Categoria/cadastro_categoria.html" class="btn">Cadastrar Categoria</a>
                <a href="../Autor/cadastro_autor.html" class="btn">Cadastrar Autor</a>
            </div>
        </div>
    </div>
</body>
</html>
