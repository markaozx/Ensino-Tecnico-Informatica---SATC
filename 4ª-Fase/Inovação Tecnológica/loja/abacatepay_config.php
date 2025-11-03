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
 * Função para sanitizar strings para UTF-8
 * Converte strings de latin1 para UTF-8 para garantir compatibilidade com JSON
 */
function sanitize_utf8($string) {
    if (!is_string($string)) {
        return $string;
    }
    // Remover caracteres nulos
    $string = str_replace("\0", '', $string);
    // Tentar detectar encoding e converter para UTF-8
    if (function_exists('mb_detect_encoding') && mb_detect_encoding($string, 'UTF-8', true) !== 'UTF-8') {
        // Tentar converter de latin1 para UTF-8
        $string = mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
    }
    // Garantir que a string está em UTF-8 válido
    if (!mb_check_encoding($string, 'UTF-8')) {
        // Se ainda assim não for UTF-8 válido, tentar sanitizar
        $string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }
    return $string;
}

/**
 * Função auxiliar para fazer requisições à API da AbacatePay
 * Compatível com PHP 5.3+ SEM cURL
 */
function abacatepay_request($endpoint, $method = 'GET', $data = null) {
    $url = ABACATEPAY_API_URL . $endpoint;
    
    // Preparar corpo da requisição
    $postdata = '';
    if ($method === 'POST' && $data !== null) {
        $postdata = json_encode($data);
        if ($postdata === false) {
            return array('error' => 'Erro ao codificar JSON: ' . json_last_error_msg());
        }
    }
    
    // Preparar headers
    $headers = array();
    $headers[] = 'Authorization: Bearer ' . ABACATEPAY_API_KEY;
    
    // Preparar contexto
    $opts = array(
        'http' => array(
            'method' => $method,
            'timeout' => 30,
            'ignore_errors' => true
        )
    );
    
    // Só adicionar headers de JSON se houver conteúdo
    if ($method === 'POST' && $postdata !== '') {
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($postdata);
        $opts['http']['header'] = implode("\r\n", $headers);
        $opts['http']['content'] = $postdata;
    } else {
        // Para GET ou POST sem dados, não adicionar Content-Type: application/json
        $opts['http']['header'] = implode("\r\n", $headers);
    }
    
    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    
    // Se falhou, retornar erro
    if ($response === false) {
        return array('error' => 'Erro ao conectar com a API AbacatePay');
    }
    
    // Decodificar JSON
    $result = json_decode($response, true);
    
    // Se deu erro ao decodificar, retornar resposta crua
    if ($result === null) {
        return array('error' => 'Erro ao processar resposta: ' . $response);
    }
    
    return $result;
}
?>

