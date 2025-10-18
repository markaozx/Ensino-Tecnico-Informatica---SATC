<?php
// Sessão (para cliente) - compatível com PHP 5.3
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conn) { die('Erro de conexão: ' . mysqli_connect_error()); }

// Definir charset (alinhado ao dump latin1)
mysqli_set_charset($conn, 'latin1');

$erro = '';
$ok = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = md5($_POST['senha']);
    $cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
    $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
    $endereco = mysqli_real_escape_string($conn, $_POST['endereco']);
    $numero = mysqli_real_escape_string($conn, $_POST['numero']);
    $complemento = mysqli_real_escape_string($conn, $_POST['complemento']);
    $bairro = mysqli_real_escape_string($conn, $_POST['bairro']);
    $cidade = mysqli_real_escape_string($conn, $_POST['cidade']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $cep = mysqli_real_escape_string($conn, $_POST['cep']);
    $data_nascimento = mysqli_real_escape_string($conn, $_POST['data_nascimento']);
    $sexo = mysqli_real_escape_string($conn, $_POST['sexo']);

    $check = mysqli_query($conn, "SELECT 1 FROM usuario WHERE email='$email' OR cpf='$cpf' LIMIT 1");
    if ($check && mysqli_num_rows($check) > 0) {
        $erro = 'Email ou CPF já cadastrados.';
    } else {
        $sql = "INSERT INTO usuario (nome,email,senha,cpf,telefone,endereco,numero,complemento,bairro,cidade,estado,cep,data_nascimento,sexo) VALUES ('$nome','$email','$senha','$cpf','$telefone','$endereco','$numero','$complemento','$bairro','$cidade','$estado','$cep','$data_nascimento','$sexo')";
        if (mysqli_query($conn, $sql)) {
            $ok = 'Cadastro realizado com sucesso. Faça login para continuar.';
            header('Location: login_cliente.php?redirect=' . urlencode($redirect));
            exit;
        } else {
            $erro = 'Erro ao cadastrar: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="theme.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextLevel Tech - Registrar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box }
        body { font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif; background:#fff; color:#000; display:flex; justify-content:center; align-items:start; min-height:100vh }
        .cosmic-bg,.floating-particles{display:none}
        .card{ background:#fff; padding:24px; border-radius:16px; width:min(760px,95%); border:1px solid #e5e5e5; box-shadow:0 6px 20px rgba(0,0,0,.06); animation:slideIn .3s ease-out; margin:28px auto }
        @keyframes slideIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
        h1{ margin:0 0 18px; text-align:center; font-size:22px; color:#000; font-weight:700 }
        .grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:12px }
        .full{ grid-column:1 / -1 }
        .field{ display:flex; flex-direction:column }
        .field label{ font-size:12px; color:#555; margin:2px 0 6px; font-weight:600 }
        .grid input,.grid select{ width:100%; padding:12px 14px; border-radius:10px; border:1px solid #e5e5e5; background:#fff; color:#000; font-size:14px; transition:border-color .2s, box-shadow .2s }
        .grid input:focus,.grid select:focus{ outline:none; border-color:#FF6B00; box-shadow:0 0 0 4px rgba(255,107,0,0.12) }
        .btn{ width:100%; padding:12px 16px; border-radius:12px; border:none; background:#FF6B00; color:#fff; font-weight:600; cursor:pointer; margin-top:12px; transition:filter .2s }
        .btn:hover{ filter:brightness(1.05) }
        .back-home{ position:fixed; top:16px; left:16px; z-index:1000; text-decoration:none; padding:8px 12px; border-radius:10px; background:#fff; color:#000; border:1px solid #e5e5e5 }
        .erro{ background:#fdecea; color:#b71c1c; padding:12px; border-radius:10px; margin-bottom:12px; border:1px solid #f5c6c4 }
        .ok{ background:#e8f5e9; color:#1b5e20; padding:12px; border-radius:10px; margin-bottom:12px; border:1px solid #c8e6c9 }
        a{ color:#FF6B00; text-decoration:none }
    </style>
</head>
<body>
    <a class="back-home" href="home.php">← Voltar</a>
    <div class="card">
        <h1>Registrar Cliente</h1>
        <?php if (!empty($erro)): ?><div class="erro"><?php echo $erro; ?></div><?php endif; ?>
        <?php if (!empty($ok)): ?><div class="ok"><?php echo $ok; ?></div><?php endif; ?>
        <form method="post" action="?redirect=<?php echo htmlspecialchars($redirect); ?>">
            <div class="grid">
                <div class="field full"><label for="nome">Nome completo</label><input id="nome" name="nome" required></div>
                <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" required></div>
                <div class="field"><label for="senha">Senha</label><input id="senha" name="senha" type="password" required></div>
                <div class="field"><label for="cpf">CPF</label><input id="cpf" name="cpf" required></div>
                <div class="field"><label for="telefone">Telefone</label><input id="telefone" name="telefone" required></div>
                <div class="field full"><label for="endereco">Endereço</label><input id="endereco" name="endereco" required></div>
                <div class="field"><label for="numero">Número</label><input id="numero" name="numero" required></div>
                <div class="field"><label for="complemento">Complemento</label><input id="complemento" name="complemento"></div>
                <div class="field"><label for="bairro">Bairro</label><input id="bairro" name="bairro" required></div>
                <div class="field"><label for="cidade">Cidade</label><input id="cidade" name="cidade" required></div>
                <div class="field"><label for="estado">UF</label><input id="estado" name="estado" maxlength="2" required></div>
                <div class="field"><label for="cep">CEP</label><input id="cep" name="cep" required></div>
                <div class="field full"><label for="data_nascimento">Data de nascimento</label><input id="data_nascimento" name="data_nascimento" type="date" required></div>
                <div class="field full"><label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option value="">Selecione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                        <option value="O">Outro</option>
                    </select>
                </div>
                <button class="btn full" type="submit">🚀 Registrar</button>
            </div>
        </form>
        <p style="margin-top:1.5rem; text-align:center; color:#666">Já possui conta? <a href="login_cliente.php?redirect=<?php echo urlencode($redirect); ?>">Entrar</a></p>
    </div>
</body>
</html>

