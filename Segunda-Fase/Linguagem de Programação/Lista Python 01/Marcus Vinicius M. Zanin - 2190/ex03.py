nome = input("Digite o nome do aluno: ")

idade = int(input("Digite a idade do aluno: "))

if idade >= 0 and idade <= 2:
    nivel = "Berçário"
elif idade >= 3 and idade <= 6:
    nivel = "Educação Infantil"
elif idade >= 7 and idade <= 10:
    nivel = "Fundamental Nível I"
elif idade >= 11 and idade <= 15:
    nivel = "Fundamental Nível II"
elif idade >= 16 and idade <= 18:
    nivel = "Ensino Médio"
else:
    nivel = "Idade fora do intervalo definido"

print("Aluno: ", nome)
print("Nivel: ", nivel)