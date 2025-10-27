<?php
session_start();

// Verifica se o carrinho existe
if (empty($_SESSION['cart'])) {
    header("Location: carrinho.php");
    exit;
}

// Calcula o total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += floatval($item['price']) * intval($item['qty']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Pagamento via PIX - NEXTLEVEL</title>
<link rel="stylesheet" href="style.css">
<style>
main { max-width: 700px; margin: 100px auto; text-align: center; }
.qr-box { margin: 20px 0; }
#qrCode img { width: 250px; }
button { padding: 12px 24px; font-size: 16px; border: none; background: #111; color: #fff; cursor: pointer; border-radius: 6px; }
.copiar { background: #ff3333; margin-top: 10px; }
</style>
</head>
<body>

<main>
  <h1>Pagamento via PIX</h1>
  <p>Valor total: <strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong></p>

  <div id="qrCode">
    <p>Gerando QR Code...</p>
  </div>

  <button class="copiar" onclick="copiar()">Copiar código PIX</button>
  <p id="statusPag">⏳ Aguardando pagamento...</p>
</main>

<script>
async function gerarPix() {
  const resp = await fetch("gerar_pix.php");
  const data = await resp.json();

  if (data.qr_code_base64) {
    document.getElementById("qrCode").innerHTML = `<img src="data:image/png;base64,${data.qr_code_base64}" alt="QR Code PIX">`;
    window.pixCopiaCola = data.qr_code;
  } else {
    document.getElementById("qrCode").innerHTML = "<p>Erro ao gerar PIX.</p>";
  }

  // Verificar status a cada 5s
  setInterval(async () => {
    const check = await fetch("gerar_pix.php?check=" + encodeURIComponent(data.payment_id));
    const status = await check.text();
    document.getElementById("statusPag").textContent = status;
  }, 5000);
}

function copiar() {
  if (window.pixCopiaCola) {
    navigator.clipboard.writeText(window.pixCopiaCola);
    alert("Código PIX copiado!");
  }
}

gerarPix();
</script>

</body>
</html>
