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
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $codigo = intval($_POST['codigo']);
    
    if ($codigo > 0) {
        // Verificar se a marca tem produtos associados
        $sql_check = "SELECT COUNT(*) as total FROM produto WHERE codmarca = $codigo";
        $resultado_check = mysqli_query($conectar, $sql_check);
        $row = mysqli_fetch_assoc($resultado_check);
        
        if ($row['total'] > 0) {
            echo "Não é possível excluir esta marca pois ela possui " . $row['total'] . " produto(s) associado(s).";
        } else {
            // Excluir a marca
            $sql = "DELETE FROM marca WHERE codigo = $codigo";
            $resultado = mysqli_query($conectar, $sql);
            
            if ($resultado) {
                if (mysqli_affected_rows($conectar) > 0) {
                    echo "Marca excluída com sucesso!";
                } else {
                    echo "Marca não encontrada ou já foi excluída.";
                }
            } else {
                echo "Erro ao excluir marca: " . mysqli_error($conectar);
            }
        }
    } else {
        echo "Código de marca inválido!";
    }
} else {
    echo "Requisição inválida!";
}

mysqli_close($conectar);
?>
