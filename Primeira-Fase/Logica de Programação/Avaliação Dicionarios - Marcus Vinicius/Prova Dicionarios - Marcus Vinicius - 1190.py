# Marcus Vinicius Marangoni Zanin

dicionario = {} # Criação do dicionario vazio

while True:
    print("MENU DE OPÇÕES") # exibição do menu
    print("1 - Cadastrar") # exibição do menu
    print("2 - Alterar") # exibição do menu
    print("3 - Excluir") # exibição do menu
    print("4 - Pesquisar") # exibição do menu
    print("5 - Listar Totais") # exibição do menu
    print("6 - Sair") # exibição do menu
    opcao = input("Qual ação deseja fazer? ") # pergunta qual ação vai fazer
    if opcao == "1": # verifica a ação escolhida
        nome = input("nome da serie: ")
        if nome not in dicionario: # verifica se o nome ja existe
            cod = int(input("codigo da serie: "))
            if cod not in dicionario: # verifica se o codigo ja existe
                dicionario[cod] = nome # adiciona no dicionario
            else:
                print("Cod ja cadastrado")
        else:
            print("Serie ja cadastrada")
        
    if opcao == "2": # verifica a ação escolhida
        codigo = int(input("Digite o codigo da serie que deseja alterar: "))
        if codigo in dicionario.keys(): # verifica se o codigo ja existe
            new_nome = input("Digite o nome novo: ")
            dicionario.update({codigo:new_nome}) # atualiza o nome antigo para o nome novo
        else:
            print("Codigo invalido")
    if opcao == "3": # verifica a ação escolhida
        cod = input("Digite o codigo da serie que deseja excluir: ")
        if cod in dicionario: # verifica se o codigo ja existe
            del dicionario[cod] # deleta o codigo escolhido
        else:
            print ("Codigo invalido ou inexistente")
    if opcao == "4": # verifica a ação escolhida
        escolha = input("Deseja pesquisar por CODIGO ou por NOME: ")
        if escolha == "codigo" or "CODIGO":
            cod = int(input("Digite o codigo da serie: "))
            if cod in dicionario.keys(): # verifica se o codigo ja existe
                print("Serie encontrada")
            else:
                print("Codigo invalido ou inexistente")
        elif escolha == "nome" or "NOME":
            nome = input("Digite o nome da serie: ")
            if nome in dicionario.values(): # verifica se o nome esta cadastrado
                print("Serie encontrada")
            else:
                print("Nome invalido ou inexistente")
    
    if opcao == "5": # verifica a ação escolhida
        x = sorted(dicionario,reverse=[True]) # coloca em ordem
        print("Total de series: ", len(dicionario)) # ve quantas series tem
        print("Series e codigos: ", dicionario)
        print("Codigos em ordem: ", x)
    if opcao == "6": # verifica a ação escolhida
        print ("Programa encerrado")
        break # encerra o loop
