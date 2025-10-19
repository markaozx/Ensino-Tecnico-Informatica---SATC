pessoas = 50
maior_altura = float(0)
menor_altura = float(9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999)
soma_M = 0
contagem_M = 0
contagem_H = 0

for x in range(pessoas):
    altura = float(input("Digite altura: "))
    sexo = input("Digite altura: ")

    if altura > maior_altura:
        maior_altura = altura
    if altura < menor_altura:
        menor_altura = altura

    if sexo == "feminino":
        soma_M = soma_M + altura
        contagem_M = contagem_M + 1
    if sexo == "masculino":
        contagem_H = contagem_H + 1

if contagem_M > 0:
    media_M = soma_M / contagem_M
else:
    media_M = 0

porcentagem_H = (contagem_H / pessoas) * 100
porcentagem_M = (contagem_M / pessoas) * 100

print("Maior altura: ", maior_altura)
print("Menor altura: ", menor_altura)
print("Media da altura entre as mulheres: ", media_M)
print("Numero de homens: ", contagem_H)
print("Numero de mulheres: ", contagem_M)
print("Porcentagem Mulheres: ", porcentagem_M, "%")
print("Porcentagem Homens: ",porcentagem_H,"%")