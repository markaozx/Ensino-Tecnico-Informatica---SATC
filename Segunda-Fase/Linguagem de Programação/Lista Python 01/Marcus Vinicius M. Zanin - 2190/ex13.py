tarefas = []

while True:
    print("MENU DE OPÇÕES")
    print("1 - Adicionar")
    print("2 - Remover")
    print("3 - Exibir")
    print("4 - Buscar")
    print("5 - Sair")
    
    escolha = input("Escolha uma opção: ")
    
    if escolha == '1':
        tarefa = input("Digite tarefa: ")
        tarefas.append(tarefa)
        print("adicionada.")
    
    elif escolha == '2':
        tarefa = input("Digite a tarefa que deseja remover: ")
        if tarefa in tarefas:
            tarefas.remove(tarefa)
            print("removida.")
        else:
            print("não encontrada.")
    
    elif escolha == '3':
        if tarefas:
            print("Lista de tarefas:")
            for tarefa in tarefas:
                print("-", tarefa)
        else:
            print("Nenhuma tarefa.")
    
    elif escolha == '4':
        tarefa = input("Digite tarefa: ")
        if tarefa in tarefas:
            print("Tarefa encontrada.")
        else:
            print("Tarefa não encontrada.")
    
    elif escolha == '5':
        print("Saindo do programa.")
        break