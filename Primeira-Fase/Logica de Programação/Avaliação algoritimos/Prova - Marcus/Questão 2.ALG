Algoritmo "semnome"
// MARCUS - 1190
// EM CASO DE ERRO, FECHE A FEERRAMENTA OU TIRE OS COMENTARIOS
// EM ALGUNS LUGARES NÃO TEM COMENTARIOS POIS ESTA BUGANDO
Var
Produto : caracter
Quantidade : inteiro
Preco : real
Total : real
Metodo : caracter
Valorf : Real
Inicio
escreva ("Produto: ")
leia (Produto)
escreva ("Quantidade: ")
leia (Quantidade)
escreva ("Preço: ")
leia (preco)
Total := quantidade * preco
escreval ("Total: ",total)
escreval ("Formas de pagamento:")
escreval ("1 - À vista no diheiro")
escreval ("2 - À vista no crédito")
escreval ("3 - Em duas vezes")
escreval ("4 - Em três veezes")
escreval ("Digite o numero da escolhida:")  //Mostro o preço e pergunto a forma de pagamento
leia (metodo)
escolha (metodo)
caso "1"   //verifica a escolha
      Valorf := Total - (total * 0.10) //Calculo do preço final
      escreva ("Total: ",Valorf,"R$")
caso "2"  //verifica a escolha
      Valorf := Total - (total * 0.05)    //Calculo do preço final
      escreva ("Total: ",Valorf,"R$")
caso "3" //verifica a escolha
      Valorf := total / 2     //Calculo do preço final
      escreva ("Total: ",Valorf,"R$ durante 2 meses")
caso "4"     //verifica a escolha
      Valorf := (total / 3) + (total * 0.05)        //Calculo do preço final
      escreva ("Total: ",Valorf,"R$ durante 3 meses")
fimescolha
Fimalgoritmo