c = float(input("Temperatura em celsius"))

print("Menu de Opções:")
print("1 - Graus F (Fahrenheit)")
print("2 - Graus K (Kelvin)")
print("3 - Graus RE (Réaumur)")

opc = input("Qual opção você escolhe? ")

if opc == "1":
    f = c * 1.8 + 32
    print("Sua temperatura convertida é: ",f)
if opc == "2":
    k = c + 273.15
    print("Sua temperatura convertida é: ",k)
if opc == "3":
    re = c * 0.8
    print("Sua temperatura convertida é: ",re)