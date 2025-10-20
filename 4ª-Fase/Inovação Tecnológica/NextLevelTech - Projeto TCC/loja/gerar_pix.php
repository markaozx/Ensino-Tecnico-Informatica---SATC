<?php
session_start();

define('MP_ACCESS_TOKEN', 'SEU_ACCESS_TOKEN_AQUI'); // use token de teste (TEST-...)
header('Content-Type: application/json');

if (isset($_GET['check'])) {
    // Verifica status de pagamento
    $id = $_GET['check'];
    $ch = curl_init("https://api.mercadopago.com/v1/payments/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . MP_ACCESS_TOKEN
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);
    $payment = json_decode($resp, true);
    $status = $payment['status'] ?? 'pending';
    if ($status === 'approved') echo '✅ Pagamento aprovado!';
    elseif ($status === 'pending') echo '⏳ Aguardando pagamento...';
    else echo '❌ ' . $status;
    exit;
}

// Cria pagamento PIX
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += floatval($item['price']) * intval($item['qty']);
}

$data = [
  "transaction_amount" => round($total, 2),
  "description" => "Compra em NEXTLEVEL",
  "payment_method_id" => "pix",
  "payer" => [
    "email" => $_SESSION['cliente_email'] ?? "cliente@teste.com"
  ]
];

$ch = curl_init("https://api.mercadopago.com/v1/payments");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Bearer ' . MP_ACCESS_TOKEN,
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$resp = curl_exec($ch);
curl_close($ch);

$json = json_decode($resp, true);
echo json_encode([
  'payment_id' => $json['id'] ?? null,
  'qr_code' => $json['point_of_interaction']['transaction_data']['qr_code'] ?? null,
  'qr_code_base64' => $json['point_of_interaction']['transaction_data']['qr_code_base64'] ?? null
]);
