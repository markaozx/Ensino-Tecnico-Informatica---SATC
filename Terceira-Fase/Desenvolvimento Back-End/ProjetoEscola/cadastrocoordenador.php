<?php
// Connect to the database
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db('escola');



if (isset($_POST['gravar'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];

    $sql = "INSERT INTO coordenador (codigo, nome) VALUES ('$codigo', '$nome')";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar!";
    }
}

if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];

    $sql = "UPDATE coordenador SET nome = '$nome' WHERE codigo = '$codigo'";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Alterado com sucesso!";
    } else {
        echo "Erro ao alterar!";
    }
}

if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];

    $sql = "DELETE FROM coordenador WHERE codigo = '$codigo'";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Excluido com sucesso!";
    } else {
        echo "Erro ao excluir!";
    }
}

if (isset($_POST['pesquisar'])) {
    $sql = "SELECT * FROM coordenador";

    $resultado = mysql_query($sql);

    if (mysql_num_rows($resultado) == 0) {
        echo "Nenhum coordenador encontrado!";
    } else {
        echo "<b>Pesquisa por coordenador:</b><br>";
        while ($dados = mysql_fetch_array($resultado)) {
            echo "Codigo: " . $dados['codigo'] . "<br>" .
                 "Nome: " . $dados['nome'] . "<br><br>";
        }
    }
}
?>
