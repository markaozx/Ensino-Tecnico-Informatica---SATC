<?php
/**
 * Webhook da AbacatePay
 * Recebe notificações sobre pagamentos confirmados
 * Configure no Dashboard: https://www.abacatepay.com/
 */

// Configurar timezone para Brasília
date_default_timezone_set('America/Sao_Paulo');

require_once 'abacatepay_config.php';

// Configuração do banco de dados
$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conn) {
    http_response_code(500);
    exit;
}
mysqli_set_charset($conn, "latin1");

// Ler dados do webhook
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Log para debug (opcional)
$log_file = dirname(__FILE__) . '/abacatepay_webhook_log.txt';
$log_entry = date('Y-m-d H:i:s') . " - Webhook recebido: " . $payload . "\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    exit;
}

// Processar evento de pagamento
$billing_id = mysqli_real_escape_string($conn, $data['id']);
$status = isset($data['status']) ? $data['status'] : '';

if ($status === 'PAID') {
    // Buscar pedido pelo billing_id
    $sql = "SELECT codigo FROM pedido WHERE stripe_session_id = '$billing_id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $pedido_id = (int)$row['codigo'];
        
        // Atualizar status do pedido
        $sql_update = "UPDATE pedido SET status = 'pago', atualizado_em = NOW() WHERE codigo = $pedido_id";
        mysqli_query($conn, $sql_update);
        
        // Atualizar estoque
        $sql_items = "SELECT produto_id, quantidade FROM pedido_item WHERE pedido_id = $pedido_id";
        $result_items = mysqli_query($conn, $sql_items);
        
        while ($item = mysqli_fetch_assoc($result_items)) {
            $produto_id = (int)$item['produto_id'];
            $quantidade = (int)$item['quantidade'];
            $sql_estoque = "UPDATE produto SET estoque = estoque - $quantidade WHERE codigo = $produto_id";
            mysqli_query($conn, $sql_estoque);
        }
        
        $log_entry = date('Y-m-d H:i:s') . " - Pedido #$pedido_id confirmado\n";
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    }
}

mysqli_close($conn);

// Retornar 200 OK
http_response_code(200);
echo json_encode(array('status' => 'success'));
?>

