<?php
// Configurar charset para UTF-8

// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    echo "Erro de conexão: " . mysqli_connect_error();
    exit;
}
mysqli_set_charset($conectar, 'utf8');

// Verificar se é uma requisição POST
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
    $codigo = intval($_POST['codigo']);
    $nome = trim(isset($_POST['nome']) ? $_POST['nome'] : '');
    $pais = trim(isset($_POST['pais']) ? $_POST['pais'] : '');
    
    if ($codigo > 0 && !empty($nome) && !empty($pais)) {
        // Escapar os dados para prevenir SQL injection
        $nome = mysqli_real_escape_string($conectar, $nome);
        $pais = mysqli_real_escape_string($conectar, $pais);
        
        $sql = "UPDATE marca SET nome = '$nome', pais = '$pais' WHERE codigo = $codigo";
        $resultado = mysqli_query($conectar, $sql);
        
        if ($resultado) {
            if (mysqli_affected_rows($conectar) > 0) {
                echo "Marca atualizada com sucesso!";
            } else {
                echo "Nenhuma alteração foi feita. Verifique se os dados são diferentes dos atuais.";
            }
        } else {
            echo "Erro ao atualizar marca: " . mysqli_error($conectar);
        }
    } else {
        echo "Dados inválidos fornecidos!";
    }
} else {
    echo "Requisição inválida!";
}

mysqli_close($conectar);
?>
