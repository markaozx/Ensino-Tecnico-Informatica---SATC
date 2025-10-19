# Marcus Vinicius Marangoni Zanin - 1190 - 14/05/2024

# ---------------------------------------------------

produtos = []
precos = []
quantidade = int(input("Quantos operações deseja fazer? "))
for contador in range(quantidade):
    print(" MENU DE OPÇÕES")
    print(" 1   -   Cadastrar")
    print(" 2   -   Excluir")
    print(" 3   -   Exibir")
    print(" 4   -   Sair")
    escolha = input("Qual operação deseja fazer? ")
    if escolha == ("1"):
        produto = input("Nome do produto: ")
        produtos.append(produto)
        preco = int(input("Digite o preço: "))
        precos.append(preco)
    if escolha == ("2"):
        produto = input("Nome do produto: ")
        produtos.remove(produto)
        preco = int(input("Digite o preço: "))
        precos.remove(preco)
    if escolha == ("3"):
        produtos.sort()
        precos.sort()
        print("Produtos: ", produtos)
        print("Preços: ", precos )
        qmaior = 100
        filtro_lista = [c for c in produtos if c > qmaior] 
        print("Preços maiores que 100: ", len(filtro_lista ))
        print("Total de funcionarios: ", len(produtos))
    if escolha == ("4"):
        print("Programa encerrado. ")
        break

