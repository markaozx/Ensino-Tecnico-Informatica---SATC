# Marcus Vinicius Marangoni Zanin - 1190 - 14/05/2024

# ---------------------------------------------------

livros = []
precos = []

x = int(input("Quantas ações deseja realizar? ")) #Pergunta quantas ações do menu serão feitas
for contador in range(x):
    print("    MENU DE OPÇÕES    ") #exibe as opções
    print(" 1  -  Cadastrar livros e preços") #exibe as opções
    print(" 2  -  Excluir livros/preços") #exibe as opções
    print(" 3  -  Pesquisar/exibir") #exibe as opções
    print(" 4  -  Encerrar o programa") #exibe as opções
    escolha = input("Digite sua escolha: ") #pergunta qual ação sera feita
    if escolha == ("1"): #verifica a ação escolhida
        nome = input("Digite o nome do livro: ")
        livros.append(nome) #cadastra o intem na lista
        preco = int(input("Digite o preço do livro: "))
        precos.append(preco) #cadastra o intem na lista
    if escolha == ("2"): #verifica a ação escolhida
        nome = input("Digite o nome do livro: ")
        livro.remove(nome) #remove o item da lista
        preco = int(input("Digite o preço do livro: "))
        precos.remove(preco) #remove o item da lista
    if escolha == ("3"): #verifica a ação escolhida
        livros.sort() #bota a lista em ordem
        precos.sort() #bota a lista em ordem
        print("Lista de livros: ", livros)
        print("Lista de preços: ", precos)
        print("Quantidade de livros cadastrados: ", len(livros)) #conta quantos itens tem e exibe
        print("Soma dos preos: ", sum(precos)) #soma os preços da lista
    if escolha == ("4"): #verifica a ação escolhida
        print("Encerrando o programa, aguarde...")
        print("Programa finalizado.")
        break #encerra o loop
            
