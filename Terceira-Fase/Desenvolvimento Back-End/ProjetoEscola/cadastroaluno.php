<?php
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors on the screen

// Connect to the database
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db('escola');

if (isset($_POST['cadastrar'])) {
    header('Content-Type: application/json'); // Set header for JSON response
    $response = array(); // Initialize response array

    $codigo   = $_POST['codigo'];
    $nome     = $_POST['nome'];
    $fone = $_POST['fone'];
    $codcurso = $_POST['codcurso'];

    $sql = "insert into aluno (codigo, nome, fone, codcurso) values ('$codigo', '$nome', '$fone', '$codcurso')";

    $resultado = mysql_query($sql) or die(json_encode(array('message' => 'Database query error: ' . mysql_error())));

    if ($resultado == TRUE) {
        $response['message'] = "Cadastrado com sucesso!";
        echo json_encode($response); // Return success message as JSON
    } else {
        $response['message'] = "Erro ao cadastrar!";
        echo json_encode($response); // Return error message as JSON
    }
}

if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $nome   = $_POST['nome'];

    $sql = "update aluno set nome = '$nome' where codigo = '$codigo'";

    $resultado = mysql_query($sql) or die(json_encode(array('message' => 'Database query error: ' . mysql_error())));

    if ($resultado == TRUE) {
        $response['message'] = "Alterado com sucesso!";
        echo json_encode($response); // Return success message as JSON
    } else {
        $response['message'] = "Erro ao alterar!";
        echo json_encode($response); // Return error message as JSON
    }
}

if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];
    
    $sql = "delete from aluno where codigo = '$codigo'";

    $resultado = mysql_query($sql) or die(json_encode(array('message' => 'Database query error: ' . mysql_error())));

    if ($resultado == TRUE) {
        $response['message'] = "Excluido com sucesso!";
        echo json_encode($response); // Return success message as JSON
    } else {
        $response['message'] = "Erro ao excluir!";
        echo json_encode($response); // Return error message as JSON
    }
}

if (isset($_POST['listar'])) {
    $sql = "select * from aluno";

    $resultado = mysql_query($sql) or die(json_encode(array('message' => 'Database query error: ' . mysql_error())));

    if (mysql_num_rows($resultado) == 0) {
        $response['message'] = "Nenhum aluno encontrado!";
        echo json_encode($response); // Return message as JSON
    } else {
        $response['data'] = array(); // Initialize data array
        while($dados = mysql_fetch_array($resultado)) {
            $response['data'][] = $dados; // Add each student to the data array
        }
        echo json_encode($response); // Return data as JSON
    }
}
?>
