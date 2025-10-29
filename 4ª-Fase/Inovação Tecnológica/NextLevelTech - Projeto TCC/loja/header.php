<?php
// Configurar timezone
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

// Contar itens no carrinho
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['qty']) ? (int)$item['qty'] : 0;
    }
}

$pesquisa_header = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
?>
<div class="top-bar">
    Frete grátis para compras acima de R$ 299 | <a href="#">Aproveite!</a>
</div>
<header class="header">
    <div class="header-content">
        <a href="home.php" class="logo">NEXT<span>LEVEL</span></a>
        <nav>
            <ul class="nav-menu">
                <li><a href="home.php">Todos</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <div class="search-box">
                <form method="get" action="home.php">
                    <input name="pesquisa" type="text" placeholder="Buscar produtos..." value="<?php echo htmlspecialchars($pesquisa_header); ?>">
                    <button type="submit">🔍</button>
                </form>
            </div>
            <?php if (isset($_SESSION['cliente_id'])): ?>
            <a class="icon-btn" href="logout_cliente.php?redirect=<?php echo urlencode(basename($_SERVER['PHP_SELF'])); ?>" title="Sair">🚪</a>
            <?php else: ?>
            <a class="icon-btn" href="login_cliente.php?redirect=<?php echo urlencode(basename($_SERVER['PHP_SELF'])); ?>" title="Entrar">👤</a>
            <?php endif; ?>
            <?php if (isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1')): ?>
            <a class="icon-btn" href="login_adm.php" title="Admin">🔑</a>
            <?php endif; ?>
            <a class="icon-btn" href="carrinho.php" title="Carrinho">
                🛒
                <?php if ($cart_count > 0): ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
            <button class="mobile-menu-btn">☰</button>
        </div>
    </div>
</header>

<style>
.top-bar{background:#111;color:#fff;font-size:12px;padding:8px 16px;text-align:center}
.top-bar a{color:#4fc3f7;text-decoration:none}
.header{position:sticky;top:0;z-index:1000;background:#0a0a0a;color:#fff;border-bottom:1px solid rgba(255,255,255,0.08)}
.header-content{max-width:1200px;margin:0 auto;padding:12px 20px;display:flex;align-items:center;justify-content:space-between}
.logo{font-family:'Orbitron', monospace;font-weight:700;font-size:22px;color:#fff;text-decoration:none}
.logo span{color:#4fc3f7}
.nav-menu{list-style:none;display:flex;gap:16px}
.nav-menu a{color:#bbb;text-decoration:none;padding:8px 10px;border-radius:8px}
.nav-menu a:hover,.nav-menu a.active{background:rgba(255,255,255,0.06);color:#fff}
.header-actions{display:flex;align-items:center;gap:12px}
.icon-btn{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.06);color:#fff;text-decoration:none}
.icon-btn:hover{background:rgba(255,255,255,0.12)}
.cart-count{background:#e53935;color:#fff;border-radius:10px;padding:2px 6px;font-size:12px;position:relative;top:-10px;left:-8px}
.search-box{display:flex;align-items:center;background:rgba(255,255,255,0.06);border-radius:10px;padding:4px 6px}
.search-box input{background:transparent;border:none;color:#fff;outline:none;padding:6px 8px}
.search-box button{background:linear-gradient(45deg,#ff6b6b,#4ecdc4);border:none;color:#fff;padding:6px 10px;border-radius:8px;cursor:pointer}
.mobile-menu-btn{display:none}
@media(max-width:768px){.nav-menu{display:none}.mobile-menu-btn{display:inline-flex}}
</style>

