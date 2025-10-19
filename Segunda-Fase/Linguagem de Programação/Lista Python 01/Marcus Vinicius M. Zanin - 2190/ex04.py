nome = input("Digite o nome do paciente: ")
idade = int(input("Digite a idade do paciente: "))
peso = float(input("Digite o peso do paciente (em kg): "))

if idade <= 15:
    print(nome + ", não pode ser doador. Idade abaixo da permitida.")
elif idade <= 17:
    if peso > 55:
        print(nome + ", pode ser doador com autorização dos pais ou responsáveis.")
    else:
        print(nome + ", não pode ser doador. Peso abaixo da exigência.")
elif idade <= 69:
    if peso > 60:
        print(nome + ", pode ser doador.")
    else:
        print(nome + ", não pode ser doador. Peso abaixo da exigência.")
else:
    print(nome + ", não pode ser doador. Idade acima da permitida.")