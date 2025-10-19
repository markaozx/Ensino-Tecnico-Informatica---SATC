Lista = []

while True:
    print("""
    1- adicionar
    2- remover
    3- mostrar todos
    4- mostrar pares
    5- mostrar ímpares
    6- mostrar primos
    7- sair
    """)

    escolha = int(input('>> '))

    if escolha == 1:
        num = int(input('Digite um número: '))
        Lista.append(num)

    elif escolha == 2:
        num = int(input('Digite um número para remover: '))
        if num in Lista:
            Lista.remove(num)
        else:
            print("Número não encontrado na lista.")

    elif escolha == 3:
        print("Todos os números na lista:")
        for i in Lista:
            print(i)

    elif escolha == 4:
        print("Números pares na lista:")
        for i in Lista:
            if i % 2 == 0:
                print(i)

    elif escolha == 5:
        print("Números ímpares na lista:")
        for i in Lista:
            if i % 2 != 0:
                print(i)

    elif escolha == 6:
        print("Números primos na lista:")
        for i in Lista:
            if i > 1:
                for j in range(2, i):
                    if (i % j) == 0:
                        print(i)

    elif escolha == 7:
        print("Saindo...")
        break