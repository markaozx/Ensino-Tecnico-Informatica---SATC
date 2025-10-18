<?php
// Conectar ao servidor e banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'ecommerce_perifericos');

if (!$conectar) {
    echo "Erro de conexão: " . mysqli_connect_error();
    exit;
}

// Verificar se é uma requisição POST
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update') {
    $codigo = intval($_POST['codigo']);
    $nome = trim(isset($_POST['nome']) ? $_POST['nome'] : '');
    $descricao = trim(isset($_POST['descricao']) ? $_POST['descricao'] : '');
    
    if ($codigo > 0 && !empty($nome)) {
        // Escapar os dados para prevenir SQL injection
        $nome = mysqli_real_escape_string($conectar, $nome);
        $descricao = mysqli_real_escape_string($conectar, $descricao);
        
        $sql = "UPDATE categoria SET nome = '$nome', descricao = '$descricao' WHERE codigo = $codigo";
        $resultado = mysqli_query($conectar, $sql);
        
        if ($resultado) {
            if (mysqli_affected_rows($conectar) > 0) {
                echo "Categoria atualizada com sucesso!";
            } else {
                echo "Nenhuma alteração foi feita. Verifique se os dados são diferentes dos atuais.";
            }
        } else {
            echo "Erro ao atualizar categoria: " . mysqli_error($conectar);
        }
    } else {
        echo "Dados inválidos fornecidos! Nome é obrigatório.";
    }
} else {
    echo "Requisição inválida!";
}

mysqli_close($conectar);
?>
