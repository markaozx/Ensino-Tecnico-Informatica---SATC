horas = float(input("Quanto você ganha por hora? "))

mensal = float(input("Quantas horas você trabalha por mes? "))

bruto = horas * mensal

print("Seu salario bruto é: ",bruto)

inss = bruto * 0.08

sindicato = bruto * 0.05

imposto = bruto * 0.11

liquido = bruto - inss - sindicato - imposto

print("Pagou ao INSS: ",inss)

print("Pagou ao sindicato: ",sindicato)

print("Salario liquido: ",liquido)