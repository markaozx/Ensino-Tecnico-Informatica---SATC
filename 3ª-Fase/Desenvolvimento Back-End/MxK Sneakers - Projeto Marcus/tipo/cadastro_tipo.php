<?php
// Conectar ao servidor e banco de dados
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db("loja");

// Processar cadastro de tipo
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];

    // Inserir nova tipo
    $sql = "INSERT INTO tipo (nome) VALUES ('$nome')"; // Atualizar a consulta SQL
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "tipo cadastrada com sucesso!";
    } else {
        echo "Falha ao cadastrar a tipo: " . mysql_error();
    }
}

// Processar exclusão de tipo
if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];

    // Excluir tipo
    $sql = "DELETE FROM tipo WHERE codigo = '$codigo'";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "tipo excluída com sucesso!";
    } else {
        echo "Falha ao excluir a tipo: " . mysql_error();
    }
}

// Processar alteração de tipo
if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];

    // Alterar tipo
    $sql = "UPDATE tipo SET nome='$nome' WHERE codigo = '$codigo'"; // Atualizar a consulta SQL
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "tipo alterada com sucesso!";
    } else {
        echo "Falha ao alterar a tipo: " . mysql_error();
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
