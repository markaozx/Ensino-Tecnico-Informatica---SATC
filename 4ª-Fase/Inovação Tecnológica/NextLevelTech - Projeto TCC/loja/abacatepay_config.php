<?php
/**
 * Configuração da AbacatePay
 * API simples e brasileira para pagamentos
 */

// Chave de API da AbacatePay (MODO DEV/TESTE)
// Obtenha sua chave em: https://www.abacatepay.com/
define('ABACATEPAY_API_KEY', 'abc_dev_krAhwAG5ZqnjS1zKyccWUTA0');

// URL da API
define('ABACATEPAY_API_URL', 'https://api.abacatepay.com/v1');

// URL do seu site (ajuste conforme necessário)
define('SITE_URL', 'http://localhost/NextLevelTech%20-%20Projeto%20TCC/loja');

// Nome da loja
define('STORE_NAME', 'NextLevel Tech');

/**
 * Função auxiliar para fazer requisições à API da AbacatePay
 * Versão SEM cURL - compatível com qualquer PHP
 */
function abacatepay_request($endpoint, $method = 'GET', $data = null) {
    $url = ABACATEPAY_API_URL . $endpoint;
    
    // Preparar headers
    $headers = array(
        'Authorization: Bearer ' . ABACATEPAY_API_KEY,
        'Content-Type: application/json'
    );
    
    // Configurar contexto para file_get_contents
    $options = array(
        'http' => array(
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true,
            'timeout' => 30
        )
    );
    
    if ($method === 'POST' && $data !== null) {
        $options['http']['content'] = json_encode($data);
    }
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return array('error' => 'Erro ao conectar com a API');
    }
    
    return json_decode($response, true);
}
?>

