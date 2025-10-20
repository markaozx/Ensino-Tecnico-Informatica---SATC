<?php
define('MP_ACCESS_TOKEN', 'SEU_ACCESS_TOKEN_AQUI');

$input = file_get_contents("php://input");
$log = date('c') . " - " . $input . PHP_EOL;
file_put_contents("mp_pix_webhook.log", $log, FILE_APPEND);

$data = json_decode($input, true);
if (!empty($data['data']['id'])) {
    $id = $data['data']['id'];
    $ch = curl_init("https://api.mercadopago.com/v1/payments/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . MP_ACCESS_TOKEN]);
    $resp = curl_exec($ch);
    curl_close($ch);
    $payment = json_decode($resp, true);

    if (($payment['status'] ?? '') === 'approved') {
        // Aqui você pode atualizar o banco e marcar pedido como pago
        // Exemplo:
        // $stmt = $conn->prepare("UPDATE pedidos SET status='pago' WHERE mp_id=?");
        // $stmt->bind_param('s', $id);
        // $stmt->execute();
    }
}
http_response_code(200);
