<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$response = array(
    "success" => false,
    "message" => "Erro ao buscar imagem de gato",
    "image" => "https://via.placeholder.com/400x300?text=Erro"
);

try {
    $url = "https://api.thecatapi.com/v1/images/search";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $apiResponse = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        $response['message'] = "Erro de conexão: " . $error;
    } elseif ($httpcode == 200) {
        $catData = json_decode($apiResponse, true);
        
        if ($catData && is_array($catData) && count($catData) > 0) {
            $response = array(
                "success" => true,
                "message" => "Imagem de gato encontrada com sucesso",
                "image" => $catData[0]['url'],
                "id" => isset($catData[0]['id']) ? $catData[0]['id'] : '',
                "width" => isset($catData[0]['width']) ? $catData[0]['width'] : '',
                "height" => isset($catData[0]['height']) ? $catData[0]['height'] : ''
            );
        } else {
            $response['message'] = "Resposta da API inválida";
        }
    } else {
        $response['message'] = "Erro HTTP: " . $httpcode;
    }
    
} catch (Exception $e) {
    $response['message'] = "Erro interno: " . $e->getMessage();
}

echo json_encode($response);
?>