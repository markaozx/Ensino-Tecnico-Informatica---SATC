funcionario = {'Pedro':'recepção','Arthur':'gerente'}

while True:
    print('MENU DE OPÇÕES')
    print('C - Cadastrar')
    print('A - Alterar')
    print('E - Excluir')
    print('P - Pesquisar')
    print('S - Sair')
    opcao = input('Qual ação deseja executar: ')
    if opcao == 'C':  # Verifica a opção
        nome=input('Digite o nome do funcionario: ')
        cargo=input('Digite o codigo do funcionario: ')
        funcionario[nome:cargo]
    elif opcao == 'A':  # Verifica a opção
        alterar = input("Deseja alterar Cargo ou Nome do Funcionario: ")
        if alterar == 'Cargo':
            cargo = input('Digite o Cargo que deseja alterar: ')
            if cargo in funcionario:
                novo = input('Digite o novo Cargo: ')
                funcionario.update(cargo,novo)
                print('Cargo alterado')
            else:
                print('Cargo inexistente')
        elif alterar == 'Nome':
            nome = input('Digite o Nome que deseja alterar: ')
            if nome in funcionario:
                novo = input('Digite o novo Nome: ')
                funcionario.update(nome,novo)
                print('Nome alterado')
            else:
                print('Nome inexistente')
        else:
            print('Opção invalida')
        
    elif opcao == 'E':  # Verifica a opção
        excluir = input("Deseja excluir Cargo ou Nome do Funcionario: ")
        if excluir == 'Cargo':  # Verifica a opção
            cargo = input('Digite o Cargo que deseja excluir: ')
            if cargo in funcionario: # Verifica se existe
                funcionario.pop(cargo)  # Apaga
                print('Cargo excluido')
            else:
                print('Cargo inexistente')
        elif excluir == 'Nome':  # Verifica a opção
            nome = input('Digite o Nome que deseja excluir: ')
            if nome in funcionario:   # Verifica se existe
                funcionario.pop(nome)  # Apaga
                print('Nome excluido')
            else:
                print('Nome inexistente')
        else:  # Verifica a opção
            print('Opção invalida')
    elif opcao == 'P':  # Verifica a opção
        print(f"Lista completa: {funcionario}")
        ordem = sorted(funcionario)
        print(f"Lista em ordem alfabetica: {ordem}")
        quantidade = len(funcionario)
        print(f"Quantidade de funcionarios: {quantidade}")
    else:  # Verifica a opção
        print('Opção invalida!')
        print('Lembre de colocar a letra em caixa alta.')
        