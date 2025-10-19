import mysql.connector

conexao_banco = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='imobiliaria'
)

cursor = conexao_banco.cursor()

def cadastrar():
    id_input = int(input("Digite o ID: "))
    endereco = input("Digite o endereço: ")
    tipo = input("Digite o tipo: ")
    preco = float(input("Digite o preço: "))
    status = input("Digite o status(disponivel ou vendido): ")
    comando = f'select * from imoveis where endereco = "{endereco}"'
    cursor.execute(comando)
    dados = cursor.fetchall()
    if dados:
        print("endereço ja usado")
    else: 
        comando = f'insert into imoveis (id,endereco,tipo,preco,status) values ({id_input},"{endereco}","{tipo}",{preco},"{status}")'
        cursor.execute(comando)
        conexao_banco.commit()
        print("Adicionado com sucesso.")

def alterar():
    id_input = int(input("Digite a ID do imovel que deseja alterar o status: "))
    comando = f'select * from imoveis where id = {id_input}'
    cursor.execute(comando)
    dados = cursor.fetchall()
    if dados:
        status = int(input("O imovel esta disponivel(1) ou vendido(2): "))
        if status == 1:
            comando = f'update imoveis set status = "disponivel" where id = {id_input}'
            cursor.execute(comando)
            conexao_banco.commit()
            print("Alterado com sucesso")
        if status == 2:
            comando = f'update imoveis set status = "vendido" where id = {id_input}'
            cursor.execute(comando)
            conexao_banco.commit()
            print("Alterado com sucesso")
        else:
            print("Opção invalida")
    else: 
        print("ID não encontrado")

def excluir():
    id_input = int(input("Digite o id que deseje apagar: "))
    comando = f'select * from imoveis where id = {id_input}'
    cursor.execute(comando)
    dados = cursor.fetchall()
    if dados:
        comando = f'delete from imoveis where id = {id_input}'
        cursor.execute(comando)
        conexao_banco.commit()
        print("imovel removido")
    else:
        print("ID não encontrada")

def pesquisar():
    opc = int(input("Deseja pesquisar por tipo(1) ou por preço(2): "))
    if opc == 1:
        tipo = input("Digite o tipo: ")
        comando = f'select * from imoveis where tipo = "{tipo}"'
        cursor.execute(comando)
        dados = cursor.fetchall()
        for i in dados:
            print(i)
    elif opc == 2:
        precomin = input("Digite o preço minimo: ")
        precomax = input("Digite o preço maximo: ")
        comando = f'select * from imoveis where preco >= {precomin} and preco <= {precomax}'
        cursor.execute(comando)
        dados = cursor.fetchall()
        for i in dados:
            print(i)
    else:
        print("Opção invalida")

while True:
    print('''MENU DE OPÇÔES
    1 - cadastrar
    2 - excluir
    3 - alterar
    4 - pesquisar
    5 - sair''')
    opcao = int(input("Digite a opção desejada: "))
    if opcao == 1:
        cadastrar()
    if opcao == 2:
        excluir()
    if opcao == 3:
        alterar()
    if opcao == 4:
        pesquisar()
    if opcao == 5:
        print("Programa encerrado")
        break