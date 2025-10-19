# Marcus Vinicius Marangoni Zanin - 1190 - 14/05/2024

# ---------------------------------------------------

alunos = []
cursos = []

x = int(input("Quantas ações deseja realizar? ")) #pergunta quantas ações vão ser feitas
for contador in range(x):
    print("    MENU DE OPÇÕES    ") #exibe as opções
    print(" 1  -  Cadastrar alunos")
    print(" 2  -  Cadastrar cursos")
    print(" 3  -  Excluir")
    print(" 4  -  Pesquisar")
    print(" 5  -  Sair")
    escolha = input("Digite a ação: ") #pergunta qual a ação escolhida
    if escolha == ("1"): #verifica a escolha
        nome = input("Nome do aluno: ")
        alunos.append(nome) #adiciona na lita
    if escolha == ("2"): #verifica a escolha
        nome = input("Nome do curso: ")
        cursos.append(nome) #adiciona na lista
    if escolha == ("3"): #verifica a escolha
        opcao = input("Deseja remover um aluno ou curso: ")
        if opcao == ("curso"):
            nome = input("Nome do curso: ")
            cursos.remove(nome) #remove da lista
        if opcao == ("aluno"):
            nome = input("Nome do aluno: ")
            alunos.remove(nome) #remove da lista
    if escolha == ("4"): #verifica a escolha
        alunos.sort() #ordena a lista
        cursos.sort() #ordena a lista
        print("Alunos: ", alunos)
        print("Cursos: ", cursos)
    if escolha == ("5"):
        print("Programa fechado")
        break #encerra o programa
            
