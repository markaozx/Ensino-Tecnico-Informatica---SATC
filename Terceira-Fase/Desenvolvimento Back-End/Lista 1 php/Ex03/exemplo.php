<?php

// cadastrar dados html

$nome = $_POST['nome'];
$curso = $_POST['curso'];
$nota1 = $_POST['nota1'];
$nota2 = $_POST['nota2'];

//calcular

$media = ($nota1 + $nota2) / 2;

//exibir msg

echo "Nome: " . $nome ." Curso: ". $curso ." Media: ". $media;
?>