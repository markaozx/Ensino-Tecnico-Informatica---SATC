<?php
$conectar = mysql_connect('localhost','root','');
$banco    = mysql_select_db('login');

if (isset($_POST['logar']))
{
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$sql = "SELECT usuario, senha FROM usuario
        WHERE usuario = '$usuario' and senha = '$senha'";

$resultado = mysql_query($sql);

if (mysql_num_rows($resultado) <= 0 )
{
   echo "<script language='javascript' type='text/javascript'>  
        alert('usuario e/ou senha incorretos');
        window.location.href='login.php';
        </script>";
    }
    else
    {
        setcookie('login',$login);
        header('Location:../loja/menu.html');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="cosmic-bg"></div>
    <div class="container">
        <div class="card">
            <h1>Fazer Login</h1>
            <form method="post" action="login.php" class="form-grid">
                <div class="form-group">
                    <label for="usuario">Usuário</label>
                    <input type="text" name="usuario" id="usuario" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="logar" class="btn">Entrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

