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
    
    if ($codigo > 0) {
        // Buscar dados atuais
        $sql_atual = "SELECT * FROM marca WHERE codigo = $codigo";
        $resultado_atual = mysqli_query($conectar, $sql_atual);
        
        if ($resultado_atual && mysqli_num_rows($resultado_atual) > 0) {
            $marca_atual = mysqli_fetch_assoc($resultado_atual);
            
            // Atualizar apenas o campo fornecido
            $nome = isset($_POST['nome']) ? mysqli_real_escape_string($conectar, $_POST['nome']) : $marca_atual['nome'];
            $pais = isset($_POST['pais']) ? mysqli_real_escape_string($conectar, $_POST['pais']) : $marca_atual['pais'];
            
            $sql = "UPDATE marca SET nome = '$nome', pais = '$pais' WHERE codigo = $codigo";
            $resultado = mysqli_query($conectar, $sql);
            
            if ($resultado) {
                if (mysqli_affected_rows($conectar) > 0) {
                    echo "Marca atualizada com sucesso!";
                } else {
                    echo "Nenhuma alteração foi feita.";
                }
            } else {
                echo "Erro ao atualizar marca: " . mysqli_error($conectar);
            }
        } else {
            echo "Marca não encontrada!";
        }
    } else {
        echo "Código de marca inválido!";
    }
} else {
    echo "Requisição inválida!";
}

mysqli_close($conectar);
?>
