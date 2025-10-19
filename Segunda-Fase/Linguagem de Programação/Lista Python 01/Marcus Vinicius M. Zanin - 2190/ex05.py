nome = input("Nome do produto: ")
quant = int(input("Quantidade: "))
preco = float(input("Preço: "))
print("Tipo Pagamento")
print("1 - À vista em dinheiro")
print("2 - À vista no cartão de crédito")
print("3 - Em duas vezes")
print("4 - Em três vezes")
tipo = input("Tipo de pagamento: ")
if tipo == "1":
    desconto = preco - (preco * 0.1)
    print("Uma vez de: ",desconto)
if tipo == "2":
    desconto = preco - (preco * 0.05)
    print("Uma vez de: ",desconto)
if tipo == "3":
    parcela = preco / 2
    print("Duas vezes de: ",parcela)
if tipo == "4":
    juros = preco + (preco * 0.05)
    parcela = juros / 3
    print("Tres vezes de: ",parcela)