# Marcus Vinicius Marangoni Zanin - 1190 - 14/05/2024

# ---------------------------------------------------

nomes = []
salarios = []
quantidade = int(input("Quantos funcionarios deseja cadastrar? "))
for contador in range(quantidade):
    print(" MENU DE OPÇÕES")
    print(" 1   -   Cadastrar")
    print(" 2   -   Excluir")
    print(" 3   -   Exibir")
    print(" 4   -   Sair")
    escolha = input("Qual operação deseja fazer? ")
    if escolha == ("1"):
        nome = input("Nome do funcionario: ")
        nomes.append(nome)
        salario = int(input("Digite o salario: "))
        salarios.append(salario)
    if escolha == ("2"):
        nome = input("Nome do funcionario: ")
        nomes.remove(nome)
        salario = int(input("Digite o salario: "))
        salarios.remove(salario)
    if escolha == ("3"):
        nomes.sort()
        print("Funcionarios: ", nomes)
        print("Menor salario: ", min(salarios), "Maior salario: ", max(salarios))
        print("Soma dos salarios: ", sum(salarios))
        print("Total de funcionarios: ", len(nomes))
    if escolha == ("4"):
        print("Programa encerrado. ")
        break

