nome = input("Digite o nome: ")
altura = float(input("Digite a altura: "))
peso = float(input("Digite o peso: "))

imc = peso / (altura * altura)

if imc >= 0 and imc <= 18.5:
    nivel = "abaixo do peso"
elif imc >= 18.6 and imc <= 25:
    nivel = "peso normal"
elif imc >= 25.1 and imc <= 30:
    nivel = "acima do peso"
elif imc >= 30.1:
    nivel = "obeso"


print("Nome: ", nome)
print("Nivel: ", nivel)