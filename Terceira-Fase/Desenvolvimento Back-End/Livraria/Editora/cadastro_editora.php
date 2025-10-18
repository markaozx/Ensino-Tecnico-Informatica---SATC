<?php
// Conectar ao servidor e banco de dados
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db("livraria");

// Processar cadastro de categoria
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];

    // Inserir nova categoria
    $sql = "INSERT INTO editora (nome) VALUES ('$nome')"; // Atualizar a consulta SQL
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "editora cadastrada com sucesso!";
    } else {
        echo "Falha ao cadastrar a editora: " . mysql_error();
    }
}

// Processar exclusão de editora
if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];

    // Excluir editora
    $sql = "DELETE FROM editora WHERE codigo = '$codigo'";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "editora excluída com sucesso!";
    } else {
        echo "Falha ao excluir a editora: " . mysql_error();
    }
}

// Processar alteração de editora
if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];

    // Alterar editora
    $sql = "UPDATE editora SET nome='$nome' WHERE codigo = '$codigo'"; // Atualizar a consulta SQL
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "editora alterada com sucesso!";
    } else {
        echo "Falha ao alterar a editora: " . mysql_error();
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
                <a href="../Autor/cadastro_Autor.html" class="btn">Cadastrar Autor</a>
            </div>
        </div>
    </div>
</body> 
</html>
