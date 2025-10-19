Algoritmo "exercicio1"
// MARCUS - 1190
// EM CASO DE ERRO, FECHE A FEERRAMENTA OU TIRE OS COMENTARIOS
// EM ALGUNS LUGARES NÃO TEM COMENTARIOS POIS ESTA BUGANDO
Var
nome : caracter
peso : real
altura : real
IMC : real
Inicio
escreva ("Qual seu nome?")
leia (nome)
escreva ("Qual sua altura?")
leia (altura)
escreva ("Qual seu peso?")
leia (peso)
IMC := peso / (altura * altura)   //calculo do IMC de acordo com a formula passada
escolha (IMC)
caso 0.1 ate 18.5
     escreva ("IMC: ",IMC,". Condição: Abaixo do peso")
caso 18.6 ate 25.0
     escreva ("IMC: ",IMC,". Condição: Peso normal")
caso 25.1 ate 30.0     //verifica resultado
     escreva ("IMC: ",IMC,". Condição: Acima do peso")
caso 30.1 ate 100.0       //verifica resultado
     escreva ("IMC: ",IMC,". Condição: Obeso")
fimescolha
Fimalgoritmo