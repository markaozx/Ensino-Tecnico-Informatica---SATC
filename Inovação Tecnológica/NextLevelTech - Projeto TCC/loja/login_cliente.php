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
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = md5($_POST['senha']);
    $sql = "SELECT codigo, nome, email FROM usuario WHERE email='$email' AND senha='$senha' LIMIT 1";
    $rs = mysqli_query($conn, $sql);
    if ($rs && mysqli_num_rows($rs) === 1) {
        $u = mysqli_fetch_assoc($rs);
        // Guardar carrinho da sessão antes do login para mesclar
        $preLoginCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

        $_SESSION['cliente_id'] = (int)$u['codigo'];
        $_SESSION['cliente_nome'] = $u['nome'];

        // ==== Persistência/Mesclagem do carrinho ====
        $usuarioId = (int)$u['codigo'];
        if ($usuarioId > 0) {
            // Garantir carrinho do usuário
            $carrinhoId = 0;
            $q = "SELECT codigo FROM carrinho WHERE usuario_id = $usuarioId LIMIT 1";
            $qr = mysqli_query($conn, $q);
            if ($qr && mysqli_num_rows($qr) > 0) {
                $row = mysqli_fetch_assoc($qr);
                $carrinhoId = (int)$row['codigo'];
            } else {
                $now = date('Y-m-d H:i:s');
                $insC = "INSERT INTO carrinho (usuario_id, criado_em) VALUES ($usuarioId, '$now')";
                if (mysqli_query($conn, $insC)) {
                    $carrinhoId = (int)mysqli_insert_id($conn);
                }
            }

            if ($carrinhoId > 0) {
                // Carregar itens existentes do DB
                $dbCart = array();
                $qItems = "SELECT ci.produto_id, ci.quantidade, ci.preco_unitario, p.nome, p.estoque, p.foto1
                           FROM carrinho_item ci
                           JOIN produto p ON p.codigo = ci.produto_id
                           WHERE ci.carrinho_id = $carrinhoId";
                $ri = mysqli_query($conn, $qItems);
                while ($ri && $row = mysqli_fetch_assoc($ri)) {
                    $pid = (int)$row['produto_id'];
                    $dbCart[$pid] = array(
                        'nome' => $row['nome'],
                        'preco' => (float)$row['preco_unitario'],
                        'qty' => (int)$row['quantidade'],
                        'estoque' => (int)$row['estoque'],
                        'foto1' => $row['foto1']
                    );
                }

                // Mesclar: somar quantidades, respeitando estoque atual do produto
                foreach ($preLoginCart as $pid => $it) {
                    $pid = (int)$pid;
                    if ($pid <= 0) continue;
                    // Obter dados atuais do produto
                    $rp = mysqli_query($conn, "SELECT nome, preco, estoque, foto1 FROM produto WHERE codigo = $pid LIMIT 1");
                    $p = $rp && mysqli_num_rows($rp) ? mysqli_fetch_assoc($rp) : null;
                    $estoqueAtual = $p ? (int)$p['estoque'] : (isset($dbCart[$pid]) ? (int)$dbCart[$pid]['estoque'] : (isset($it['estoque']) ? (int)$it['estoque'] : 0));
                    $nomeAtual = $p ? $p['nome'] : (isset($dbCart[$pid]) ? $dbCart[$pid]['nome'] : (isset($it['nome']) ? $it['nome'] : ''));
                    $fotoAtual = $p ? $p['foto1'] : (isset($dbCart[$pid]) ? $dbCart[$pid]['foto1'] : (isset($it['foto1']) ? $it['foto1'] : ''));
                    $precoAtual = $p ? (float)$p['preco'] : (isset($dbCart[$pid]) ? (float)$dbCart[$pid]['preco'] : (isset($it['preco']) ? (float)$it['preco'] : 0.0));

                    $qtdSessao = isset($it['qty']) ? (int)$it['qty'] : 1;
                    $qtdDb = isset($dbCart[$pid]) ? (int)$dbCart[$pid]['qty'] : 0;
                    $novaQtd = $qtdSessao + $qtdDb;
                    if ($estoqueAtual > 0) {
                        if ($novaQtd > $estoqueAtual) $novaQtd = $estoqueAtual;
                    } else {
                        // sem estoque, não adiciona
                        $novaQtd = $qtdDb; 
                    }

                    if ($novaQtd > 0) {
                        $dbCart[$pid] = array(
                            'nome' => $nomeAtual,
                            'preco' => $precoAtual,
                            'qty' => $novaQtd,
                            'estoque' => $estoqueAtual,
                            'foto1' => $fotoAtual
                        );
                    }
                }

                // Salvar o resultado no DB: limpar e regravar
                mysqli_query($conn, "DELETE FROM carrinho_item WHERE carrinho_id = $carrinhoId");
                foreach ($dbCart as $pid => $it) {
                    $pid = (int)$pid;
                    $qty = (int)$it['qty'];
                    if ($pid <= 0 || $qty <= 0) continue;
                    $preco = (float)$it['preco'];
                    mysqli_query($conn, "INSERT INTO carrinho_item (carrinho_id, produto_id, quantidade, preco_unitario) VALUES ($carrinhoId, $pid, $qty, $preco)");
                }

                // Atualizar sessão com o carrinho mesclado
                $_SESSION['cart'] = $dbCart;
            }
        }
        header('Location: ' . $redirect);
        exit;
    } else {
        $erro = 'Email ou senha inválidos';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="theme.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextLevel Tech - Login do Cliente</title>
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

        .cosmic-bg { display: none; }

        .card {
            background: #fff;
            padding: 32px;
            border-radius: 16px;
            width: 400px;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            margin: 0 0 18px;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-color);
        }

        input {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-primary);
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(255,107,0,0.12);
        }

        .btn {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: none;
            background: var(--accent-color);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: filter .2s ease;
            margin-top: 12px;
        }

        .btn:hover { filter: brightness(1.05); }

        .erro { background: #fdecea; color: #b71c1c; padding: 12px; border-radius: 10px; margin-bottom: 12px; border: 1px solid #f5c6c4; }
        .ok { background: #e8f5e9; color: #1b5e20; padding: 12px; border-radius: 10px; margin-bottom: 12px; border: 1px solid #c8e6c9; }
        .back-home{ position:fixed; top:16px; left:16px; z-index:1000; text-decoration:none; padding:8px 12px; border-radius:10px; background:#fff; color:#000; border:1px solid #e5e5e5 }
    </style>
</head>
<body>
    <a class="back-home" href="home.php">← Voltar</a>
    <div class="cosmic-bg"></div>
    <div class="card">
        <h1>Entrar</h1>
        <?php if (!empty($erro)) { echo "<div class='erro'>".htmlspecialchars($erro)."</div>"; } ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Digite seu email" required>
            <input type="password" name="senha" placeholder="Digite sua senha" required>
            <button type="submit" class="btn">Entrar</button>
            <a href="registrar_cliente.php?redirect=<?php echo urlencode($redirect); ?>" class="btn btn-secondary" style="display:block; text-align:center; margin-top:10px;">Criar conta</a>
        </form>
    </div>
</body>
</html>

