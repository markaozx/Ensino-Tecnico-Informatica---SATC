<?php
date_default_timezone_set('America/Sao_Paulo');
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');
if (!$conectar) {
    echo "Erro de conexão: " . mysqli_connect_error();
    exit;
}
mysqli_set_charset($conectar, "latin1");

// Verificar se é uma requisição POST
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
    $codigo = intval($_POST['codigo']);
    
    if ($codigo > 0) {
        // Buscar dados atuais
        $sql_atual = "SELECT * FROM categoria WHERE codigo = $codigo";
        $resultado_atual = mysqli_query($conectar, $sql_atual);
        
        if ($resultado_atual && mysqli_num_rows($resultado_atual) > 0) {
            $cat_atual = mysqli_fetch_assoc($resultado_atual);
            
            // Atualizar apenas o campo fornecido
            $nome = isset($_POST['nome']) ? mysqli_real_escape_string($conectar, $_POST['nome']) : $cat_atual['nome'];
            $descricao = isset($_POST['descricao']) ? mysqli_real_escape_string($conectar, $_POST['descricao']) : $cat_atual['descricao'];
            
            // Verificar se o nome não está vazio
            if (empty(trim($nome)) && isset($_POST['nome'])) {
                echo "Nome é obrigatório!";
                exit;
            }
            
            $sql = "UPDATE categoria SET nome = '$nome', descricao = '$descricao' WHERE codigo = $codigo";
            $resultado = mysqli_query($conectar, $sql);
            
            if ($resultado) {
                if (mysqli_affected_rows($conectar) > 0) {
                    echo "Categoria atualizada com sucesso!";
                } else {
                    echo "Nenhuma alteração foi feita.";
                }
            } else {
                echo "Erro ao atualizar categoria: " . mysqli_error($conectar);
            }
        } else {
            echo "Categoria não encontrada!";
        }
    } else {
        echo "Código de categoria inválido!";
    }
} else {
    echo "Requisição inválida!";
}

mysqli_close($conectar);
?>
