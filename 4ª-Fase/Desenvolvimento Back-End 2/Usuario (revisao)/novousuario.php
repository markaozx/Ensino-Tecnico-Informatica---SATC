<?php
// Conectar ao servidor e banco de dados
$conectar = mysql_connect('localhost', 'root', '');
$banco = mysql_select_db("login");

// Processar cadastro de usuario
if (isset($_POST['logar'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Inserir nova usuario
    $sql = "INSERT INTO usuario (usuario, senha) VALUES ('$usuario','$senha')";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo "usuario cadastrada com sucesso!";
        setcookie('login',$login);
        header('Location:login.html');
    } else {
        echo "Falha ao logar a usuario: " . mysql_error();
    }
}