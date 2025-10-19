<?php
// Conectar ao servidor e banco de dados
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db("loja");

// Processar cadastro de marca
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];

    // Inserir nova marca
    $sql = "INSERT INTO marca (nome) VALUES ('$nome')";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "Marca cadastrada com sucesso!";
    } else {
        echo "Falha ao cadastrar a marca: " . mysql_error();
    }
}

// Processar exclusão de marca
if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];

    // Excluir marca
    $sql = "DELETE FROM marca WHERE codigo = '$codigo'";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "Marca excluída com sucesso!";
    } else {
        echo "Falha ao excluir a marca: " . mysql_error();
    }
}

// Processar alteração de marca
if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];

    // Alterar marca
    $sql = "UPDATE marca SET nome='$nome' WHERE codigo = '$codigo'";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "Marca alterada com sucesso!";
    } else {
        echo "Falha ao alterar a marca: " . mysql_error();
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