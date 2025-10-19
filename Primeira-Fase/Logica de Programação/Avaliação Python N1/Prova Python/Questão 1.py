# Marcus Vinicius Marangoni Zanin - 1190
nome = input("Insira o nome do cliente: ")
print("Codigo  Lanche  Preço")
print("  1     Pastel     3,50")
print("  2     Assado     4,50")
print("  3     Coxinha    3,00")
print("  4     Sanduiche  5,00")
lanche = input("Insira o codigo do lanche escolhido: ")
quantidade = int(input("Insira a quantidade: "))
if lanche == ("1"):
    total = 3.50 * quantidade
if lanche == ("2"):
    total = 4.50 * quantidade
if lanche == ("3"):
    total = 3 * quantidade
if lanche == ("4"):
    total = 5 * quantidade
print ("total a pagar: ", total)