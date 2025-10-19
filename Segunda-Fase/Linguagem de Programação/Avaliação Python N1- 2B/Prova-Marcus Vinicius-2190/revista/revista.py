import mysql.connector

conexao_banco = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='revista'
)

cursor = conexao_banco.cursor()


def cadastrar():
    id_input = int(input("Digite o ID da assinatura: "))
    nome = input("Digite o nome do cliente: ")
    revista = input("Digite a revista desejada: ")
    data = input("Digite a data da assinatura: ")
    status = input("Digite se a assinatura esta ativa ou cancelada: ")
    comando = f'select * from assinatura where id = {id_input}'
    cursor.execute(comando)
    dados = cursor.fetchall()
    if dados:
        print("ID ja usada")
    else: 
        comando = f'insert into assinatura (id,nome,revista,data,status) values ({id_input},"{nome}","{revista}",{data},"{status}")'
        cursor.execute(comando)
        conexao_banco.commit()
        print("Adicionado com sucesso.")

def excluir():
    opc = int(input("Deseja excluir por ID(1) ou por Cliente(2): "))
    if opc == 1:
        id_input = int(input("Digite o id que deseja remover: "))
        comando = f'delete from assinatura where id = {id_input}'
        cursor.execute(comando)
        conexao_banco.commit()
        print("assinatura removida")
    if opc == 2:
        nome = input('Digite o nome do cliente que deseja remover: ')
        comando = f'delete from assinatura where nome = "{nome}"'
        cursor.execute(comando)
        conexao_banco.commit()
        print("assinatura(s) removida")

def alterar():
    id_input = int(input("Digite a ID da assinatura que deseja alterar o status: "))
    comando = f'select * from assinatura where id = {id_input}'
    cursor.execute(comando)
    dados = cursor.fetchall()
    if dados:
        status = int(input("A assinatura esta ativa(1) ou cancelada(2): "))
        if status == 1:
            comando = f'update assinatura set status = "ativa" where id = {id_input}'
            cursor.execute(comando)
            conexao_banco.commit()
            print("Alterado com sucesso")
        if status == 2:
            comando = f'update assinatura set status = "cancelada" where id = {id_input}'
            cursor.execute(comando)
            conexao_banco.commit()
            print("Alterado com sucesso")
        else:
            print("Opção invalida")
    else: 
        print("ID não encontrado")

def pesquisar():
    opc = int(input("Deseja pesquisar por cliente(1) ou por revista(2): "))
    if opc == 1:
        nome = input("Digite o nome do cliente: ")
        comando = f'select id,revista,data,status from assinatura where nome = "{nome}"'
        cursor.execute(comando)
        dados = cursor.fetchall()
        for i in dados:
            print(i)
    elif opc == 2:
        nome = input("Digite o nome da revista: ")
        comando = f'select id,nome,data,status from assinatura where revista = "{nome}"'
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