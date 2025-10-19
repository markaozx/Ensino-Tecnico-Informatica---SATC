# Marcus Vinicius Marangoni Zanin - 1190
C = float(input("Inisira a temperatura em celsius(C): "))
print ("--Temperaturas--")
print ("FA - Fahrenheit")
print ("KE - Kelvin    ")
print ("RE - Réaumur   ")
print ("RA - Rankine   ")
temp = input("Qual a temperatura para converter: ")
if temp == ("FA"):
    final = C * 1.8 + 32
if temp == ("KE"):
    final = C + 273.15
if temp == ("RE"):
    final = C * 0.8
if temp == ("RA"):
    final = C * 1.8 + 32 + 459,67
print("Sua temperatura convertida ficou: ",final)
