<?php
// Conectar ao servidor e banco de dados
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db("livraria");

// Processar cadastro de autor
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $pais = $_POST['pais']; 

    // Inserir nova autor
    $sql = "INSERT INTO autor (nome,pais) VALUES ('$nome', '$pais')"; // Atualizar a consulta SQL
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "autor cadastrado com sucesso!";
    } else {
        echo "Falha ao cadastrar o autor: " . mysql_error();
    }
}

// Processar exclusão de autor
if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];

    // Excluir autor
    $sql = "DELETE FROM autor WHERE codigo = '$codigo'";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "autor excluído com sucesso!";
    } else {
        echo "Falha ao excluir o autor: " . mysql_error();
    }
}

// Processar alteração de autor
if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $pais = $_POST['pais'];

    // Alterar autor
    $sql = "UPDATE autor SET nome='$nome', pais='$pais' WHERE codigo = '$codigo'"; // Atualizar a consulta SQL
    
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "autor alterado com sucesso!";
    } else {
        echo "Falha ao alterar o autor: " . mysql_error();
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
