<?php
session_start();

// ==== CONFIGURAÇÃO BANCO DE DADOS ====
$servidor = "localhost";
$usuario  = "root";     // ajuste conforme seu MySQL
$senha    = "";         // ajuste conforme seu MySQL
$banco    = "ecommerce_perifericos";

// ==== CONEXÃO ====
// Migrado para mysqli
$conn = mysqli_connect($servidor, $usuario, $senha, $banco);
if (!$conn) { die("Erro ao conectar: " . mysqli_connect_error()); }
mysqli_set_charset($conn, "latin1");

// ==== LOGIN ====
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = md5($_POST['senha']); // senha no banco está em md5

    $sql = "SELECT * FROM administrador WHERE email='$email' AND senha='$senha' LIMIT 1";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $adm = mysqli_fetch_assoc($resultado);
        $_SESSION['adm_id']   = $adm['codigo'];
        $_SESSION['adm_nome'] = $adm['nome'];
        // Normalizar nível: 2 => super, outros => padrao
        $_SESSION['nivel']    = ($adm['nivel_acesso'] == 2 ? 'super' : 'padrao');

        header("Location: menu.php");
        exit;
    } else {
        $erro = "⚠ Email ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="theme.css">
    <meta charset="UTF-8">
    <title>NextLevel Tech - Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary-color: #000;
            --secondary-color: #fff;
            --accent-color: #FF6B00;
            --text-primary: #000;
            --text-secondary: #666;
            --border-color: #e5e5e5;
            --bg-gray: #f5f5f5;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #fff;
            color: var(--text-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .cosmic-bg, .floating-particles { display: none; }
        .login-box {
            background: #fff;
            padding: 32px;
            border-radius: 16px;
            width: 400px;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            animation: slideIn .3s ease-out;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .login-box h2 { margin-bottom: 18px; font-size: 22px; color: var(--primary-color); font-weight: 700; }
        .login-box input { width: 100%; padding: 12px 14px; margin: 10px 0; border: 1px solid var(--border-color); border-radius: 10px; background: #fff; color: var(--text-primary); font-size: 14px; transition: border-color .2s, box-shadow .2s; }
        .login-box input:focus { outline: none; border-color: var(--accent-color); box-shadow: 0 0 0 4px rgba(255,107,0,0.12); }
        .login-box button { background: var(--accent-color); color: #fff; border: none; padding: 12px 16px; border-radius: 12px; cursor: pointer; width: 100%; font-size: 14px; font-weight: 600; transition: filter .2s; margin-top: 12px; }
        .login-box button:hover { filter: brightness(1.05); }
        .erro { background: #fdecea; color: #b71c1c; padding: 12px; border-radius: 10px; margin-bottom: 12px; border: 1px solid #f5c6c4; }
        .back-home { position:fixed; top:16px; left:16px; z-index:1000; text-decoration:none; padding:8px 12px; border-radius:10px; background:#fff; color:#000; border:1px solid #e5e5e5; transition: all .2s; }
        .back-home:hover { background: var(--bg-gray); border-color: var(--primary-color); }
    </style>
</head>
<body>
    <a class="back-home" href="home.php">← Voltar</a>
    <div class="cosmic-bg"></div>
    <div class="floating-particles"></div>
    
    <div class="login-box">
        <h2>NextLevel Tech - Admin</h2>
        <?php if (!empty($erro)) { echo "<div class='erro'>$erro</div>"; } ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Digite seu email" required>
            <input type="password" name="senha" placeholder="Digite sua senha" required>
            <button type="submit">🚀 Entrar</button>
        </form>
    </div>

    <script>
        // Criar partículas flutuantes
        (function createParticles() {
            const container = document.querySelector('.floating-particles');
            if (!container) return;
            const total = 20;
            for (let i = 0; i < total; i++) {
                const el = document.createElement('div');
                el.className = 'particle';
                const size = Math.random() * 6 + 3; // 3-9px
                el.style.width = size + 'px';
                el.style.height = size + 'px';
                el.style.left = Math.random() * 100 + '%';
                el.style.animationDuration = (Math.random() * 10 + 10) + 's';
                el.style.animationDelay = (Math.random() * 5) + 's';
                container.appendChild(el);
            }
        })();
    </script>
</body>
</html>
