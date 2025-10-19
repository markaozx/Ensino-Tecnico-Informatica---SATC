nome = input("Digite o nome: ")

idade = int(input("Digite a idade: "))

if idade >= 0 and idade <= 15:
    nivel = "Não votantes"
elif idade == 16 or 17 or idade >= 65:
    nivel = "Eleitor facultativo"
elif idade >= 18 and idade <= 64:
    nivel = "Eleitor obrigatorio"


print("Nome: ", nome)
print("Nivel: ", nivel)