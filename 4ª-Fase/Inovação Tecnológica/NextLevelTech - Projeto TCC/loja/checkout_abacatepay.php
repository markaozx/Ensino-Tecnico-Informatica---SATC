<?php
// Configurar timezone para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

// Verificar se o usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login_cliente.php?redirect=carrinho.php');
    exit;
}

// Verificar se há itens no carrinho
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: carrinho.php');
    exit;
}

require_once 'abacatepay_config.php';

// Configuração do banco de dados
$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "latin1");

$cliente_id = (int)$_SESSION['cliente_id'];

// Buscar dados do cliente
$sql_cliente = "SELECT nome, email, telefone, cpf FROM usuario WHERE codigo = $cliente_id";
$result_cliente = mysqli_query($conn, $sql_cliente);
$cliente = mysqli_fetch_assoc($result_cliente);

// Calcular total
$total_valor = 0;
$produtos = array();

foreach ($_SESSION['cart'] as $produto_id => $item) {
    $subtotal = $item['preco'] * $item['qty'];
    $total_valor += $subtotal;
    
    $produtos[] = array(
        'externalId' => 'PROD_' . $produto_id,
        'name' => $item['nome'],
        'quantity' => $item['qty'],
        'price' => (int)($item['preco'] * 100) // Converter para centavos
    );
}

// Criar pedido no banco de dados
$data_pedido = date('Y-m-d H:i:s');
$status = 'aguardando_pagamento';

$sql_pedido = "INSERT INTO pedido (cliente_id, data_pedido, total, status, forma_pagamento, criado_em) 
               VALUES ($cliente_id, '$data_pedido', $total_valor, '$status', 'abacatepay', '$data_pedido')";

if (!mysqli_query($conn, $sql_pedido)) {
    die('Erro ao criar pedido: ' . mysqli_error($conn));
}

$pedido_id = mysqli_insert_id($conn);

// Inserir itens do pedido
foreach ($_SESSION['cart'] as $produto_id => $item) {
    $produto_id = (int)$produto_id;
    $quantidade = (int)$item['qty'];
    $preco_unitario = (float)$item['preco'];
    $subtotal = $preco_unitario * $quantidade;
    
    $sql_item = "INSERT INTO pedido_item (pedido_id, produto_id, quantidade, preco_unitario, subtotal) 
                 VALUES ($pedido_id, $produto_id, $quantidade, $preco_unitario, $subtotal)";
    mysqli_query($conn, $sql_item);
}

// Criar cobrança na AbacatePay
$cobranca_data = array(
    'frequency' => 'ONE_TIME',
    'methods' => array('PIX'), // Apenas PIX no modo dev
    'products' => $produtos,
    'returnUrl' => SITE_URL . '/success_abacatepay.php?pedido_id=' . $pedido_id,
    'completionUrl' => SITE_URL . '/success_abacatepay.php?pedido_id=' . $pedido_id,
    'customer' => array(
        'name' => $cliente['nome'],
        'email' => $cliente['email'],
        'cellphone' => $cliente['telefone'],
        'taxId' => $cliente['cpf'],
        'metadata' => array(
            'pedido_id' => $pedido_id
        )
    )
);

$response = abacatepay_request('/billing/create', 'POST', $cobranca_data);

if (isset($response['error']) && $response['error'] !== null) {
    die('Erro ao criar cobrança: ' . print_r($response['error'], true));
}

if (!isset($response['data']['id']) || !isset($response['data']['url'])) {
    die('Erro: resposta inválida da AbacatePay');
}

// Salvar ID da cobrança no pedido
$billing_id = mysqli_real_escape_string($conn, $response['data']['id']);
$sql_update = "UPDATE pedido SET stripe_session_id = '$billing_id' WHERE codigo = $pedido_id";
mysqli_query($conn, $sql_update);

mysqli_close($conn);

// Redirecionar para a página de pagamento da AbacatePay
$payment_url = $response['data']['url'];
header('Location: ' . $payment_url);
exit;
?>

