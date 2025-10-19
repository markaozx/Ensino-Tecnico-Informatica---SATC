<?php
//conectar com banco de dados
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db('escola');

if (isset($_POST['cadastrar'])) {
    $codigo   = $_POST['codigo'];
    $nome     = $_POST['nome'];
    $codcoord = $_POST['codcoord'];

    $sql = "insert into curso (codigo, nome, codcoord) values ('$codigo', '$nome', '$codcoord')";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Cadastrado com sucesso!";
    } 
    else {
        echo "Erro ao cadastrar!";
    }
}

if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $nome   = $_POST['nome'];

    $sql = "update curso set nome = '$nome' where codigo = '$codigo'";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Alterado com sucesso!";
        }
    else {
        echo "Erro ao alterar!";
    }
}

if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];

    $sql = "delete from curso where codigo = '$codigo'";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Excluido com sucesso!";
    }
    else {
        echo "Erro ao excluir!";
    }
}

if (isset($_POST['listar'])) {
    $sql = "select * from curso";

    $resultado = mysql_query($sql);

    if (mysql_num_rows($resultado) == 0) {
        echo "Nenhum curso encontrado!";
        }   
        else {
            echo "<b>" . "Pesquisa por curso: "."</b><br>";
            while($dados = mysql_fetch_array($resultado)) {
                echo "Codigo: ".$dados['codigo']."<br>".
                "Nome: ".$dados['nome']."<br>".
                "Codigo Coordenador: ".$dados['codcoord']."<br><br>";

            }
        }
    }

?>