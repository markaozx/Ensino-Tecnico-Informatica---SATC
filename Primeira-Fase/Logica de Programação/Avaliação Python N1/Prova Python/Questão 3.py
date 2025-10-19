# Marcus Vinicius Marangoni Zanin - 1190
nome = input("Insira o nome: ")
salario = float(input("Insira o salario: "))
print("Codigo   Cargo       Aumento")
print("  P      Projetos        10%")
print("  E      Engenharia       8%")
print("  A      Administrativo  13%")
print("  C      Coordenador      5%")
cargo = input("Insira o cargo: ")
if cargo == ("P"):
    final = salario + (salario * 0.10)
if cargo == ("E"):
    final = salario + (salario * 0.08)
if cargo == ("A"):
    final = salario + (salario * 0.13)
if cargo == ("C"):
    final = salario + (salario * 0.5)
print("Salario final: ", final)