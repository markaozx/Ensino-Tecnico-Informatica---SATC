<?php

// cadastrar dados html

$frase1 = $_POST['frase1'];
$frase2 = $_POST['frase2'];
$frase3 = $_POST['frase3'];

//calcular

$frase = $frase1 . $frase2 . $frase3;

//exibir msg

echo $frase;
?>