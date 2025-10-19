Algoritmo "semnome"
// MARCUS - 1190
// EM CASO DE ERRO, FECHE A FEERRAMENTA OU TIRE OS COMENTARIOS
// EM ALGUNS LUGARES NÃO TEM COMENTARIOS POIS ESTA BUGANDO
Var
Celsius : Real
Temp_escolhida : caracter
Conversao : Real
Inicio
escreva ("Temperatura em Graus Celsius (C): ")   //Temperatura inicial
leia (Celsius)
Escreval ("Temperaturas: F - Fahrenheit    ")
Escreval ("              K - Kelvin        ")
Escreval ("              Re - Réaumur      ")
Escreval ("Temperatura para conversão: ")  //possiveis opoções e escolha
Leia (Temp_escolhida)
Se (Temp_escolhida = "F") entao   //verifica a escolha do usuario
    Conversao := Celsius * 1.8 + 32    //conversão de Celsius para a temperatura escolhida
    Escreva ("Sua temperatura em Fahrenheit é:", Conversao)
Fimse
Se (Temp_escolhida = "K") entao   //verifica a escolha do usuario
    Conversao := Celsius + 273.15       //conversão de Celsius para a temperatura escolhida
    Escreva ("Sua temperatura em Kelvin é: ", Conversao)
Fimse
Se (Temp_escolhida = "Re") entao  //verifica a escolha do usuario
    Conversao := Celsius * 0.8,         //conversão de Celsius para a temperatura escolhida
    Escreva ("Sua temperatura em Réaumur é: ", Conversao)
Fimse
Fimalgoritmo