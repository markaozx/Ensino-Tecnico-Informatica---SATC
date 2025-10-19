# Marcus Vinicius Marangoni Zanin - 1190 - 14/05/2024

# ---------------------------------------------------

nomes = []
quantidade = []
x = int(input("Quantas ações deseja fazer? "))
for contador in range(x):
    print(" MENU DE OPÇÕES")
    print(" 1   -   Cadastrar peças")
    print(" 2   -   Excluir peças")
    print(" 3   -   Pesquisar/exibir")
    print(" 4   -   Sair do programa")
    escolha = input("Qual operação deseja fazer? ")
    if escolha == ("1"):
        nome = input("Nome da peça: ")
        nomes.append(nome)
        quantia = int(input("Digite a quantidade: "))
        quantidade.append(quantia)
    if escolha == ("2"):
        nome = input("Nome da peça: ")
        nomes.remove(nome)
        quantia = int(input("Digite a quantidade: "))
        quantidade.remove(quantia)
    if escolha == ("3"):
        print("lista de peças: ", nomes)
        print("lista de quantidades: ", quantidade)
        print("Soma das quantidades: ", sum(quantidade))
        print("Menor das quantidades: ", min(quantidade))
        print("Maior das quantidades: ", max(quantidade))
        print("Total de peças cadastradas: ", len(nomes))
    if escolha == ("4"):
        print("Encerrando o programa, aguarde. ")
        print("programa finalizado.")
        break

