import mysql.connector
import tkinter as tk
from tkinter import messagebox

#Conexão com o banco de dados
conexao_banco = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='farmacia'
)

cursor = conexao_banco.cursor()

def janela_funcionarios():
    janela = tk.Toplevel(janela_menu)
    janela.title("Funcionários")
    janela.geometry("300x300")
    
    tk.Label(janela, text="Gestão de Funcionários", font=("Arial", 14)).pack(pady=10)

    # Botão para Cadastrar
    tk.Button(janela, text="Cadastrar Funcionario", width=20, command=abrir_cadastro_funcionario).pack(pady=5)
    # Botões para Excluir, Alterar e Pesquisar podem ser adicionados com suas funções
    tk.Button(janela, text="Excluir Funcionario", width=20, command=excluir_funcionario).pack(pady=5)
    tk.Button(janela, text="Alterar Funcionario", width=20, command=alterar_funcionario).pack(pady=5)
    tk.Button(janela, text="Pesquisar Funcionario", width=20, command=pesquisar_funcionario).pack(pady=5)

def abrir_cadastro_funcionario():
    janela_cadastro = tk.Toplevel(janela_menu)
    janela_cadastro.title("Cadastrar Funcionário")
    janela_cadastro.geometry("400x400")

    # Configuração do título
    tk.Label(janela_cadastro, text="Cadastrar Funcionário", font=("Arial", 14)).pack(pady=10)
    4
    # Campo para o ID
    tk.Label(janela_cadastro, text="ID:").pack()
    ID_entry = tk.Entry(janela_cadastro, width=30)
    ID_entry.pack(pady=5)

    # Campo para Nome
    tk.Label(janela_cadastro, text="Nome:").pack()
    nome_entry = tk.Entry(janela_cadastro, width=30)
    nome_entry.pack(pady=5)

    # Campo para Cargo
    tk.Label(janela_cadastro, text="Cargo:").pack()
    cargo_entry = tk.Entry(janela_cadastro, width=30)
    cargo_entry.pack(pady=5)

    # Campo para Vendas Totais
    tk.Label(janela_cadastro, text="Vendas Totais:").pack()
    vendas_totais_entry = tk.Entry(janela_cadastro, width=30)
    vendas_totais_entry.pack(pady=5)

    # Campo para Salário
    tk.Label(janela_cadastro, text="Salário:").pack()
    salario_entry = tk.Entry(janela_cadastro, width=30)
    salario_entry.pack(pady=5)

    # Função para salvar o funcionário no banco de dados
    def salvar_funcionario():
        id_func = ID_entry.get()
        nome = nome_entry.get()
        cargo = cargo_entry.get()
        vendas_totais = vendas_totais_entry.get()
        salario = salario_entry.get()

        # Verifica se o ID já existe
        cursor.execute(f'SELECT * FROM funcionários WHERE ID_funcionário = "{id_func}"')
        resultado = cursor.fetchone()

        if resultado:
            messagebox.showwarning("Aviso", "Este ID já está cadastrado!")
        else:
            # Inserindo os dados no banco de dados
            comando_sql = f'''INSERT INTO funcionários 
                             (ID_funcionário, Nome_funcionário, Cargo_funcionário, Vendas_totais_funcionários, Salário_funcionário) 
                             VALUES ("{id_func}","{nome}","{cargo}","{vendas_totais}","{salario}")'''
            cursor.execute(comando_sql)
            conexao_banco.commit()
            messagebox.showinfo("Sucesso", "Funcionário cadastrado com sucesso!")
            janela_cadastro.destroy()  # Fecha a janela de cadastro após salvar

    tk.Button(janela_cadastro, text="Salvar", command=salvar_funcionario).pack(pady=20)


    janela = tk.Toplevel(janela_menu)
    janela.title("Funcionários")
    janela.geometry("300x300")
    
    tk.Label(janela, text="Gestão de Funcionários", font=("Arial", 14)).pack(pady=10)

    # Botão para Cadastrar
    tk.Button(janela, text="Cadastrar Funcionario", width=20, command=abrir_cadastro_funcionario).pack(pady=5)
    # Botões para Excluir, Alterar e Pesquisar podem ser adicionados com suas funções
    tk.Button(janela, text="Excluir Funcionario", width=20, command=excluir_funcionario).pack(pady=5)
    tk.Button(janela, text="Alterar Funcionario", width=20, command=alterar_funcionario).pack(pady=5)
    tk.Button(janela, text="Pesquisar Funcionario", width=20, command=pesquisar_funcionario).pack(pady=5)

def excluir_funcionario():
    janela_excluir = tk.Toplevel(janela_menu)
    janela_excluir.title("Excluir Funcionário")
    janela_excluir.geometry("400x400")

    # Configuração do título
    tk.Label(janela_excluir, text="Excluir Funcionário", font=("Arial", 14)).pack(pady=10)
    
    # Campo para o ID
    tk.Label(janela_excluir, text="ID:").pack()
    ID_entry = tk.Entry(janela_excluir, width=30)
    ID_entry.pack(pady=5)

    # Função para salvar o funcionário no banco de dados
    def apagar_funcionario():
        id_func = ID_entry.get()

        # Verifica se o ID já existe
        cursor.execute(f'SELECT * FROM funcionários WHERE ID_funcionário = "{id_func}"')
        resultado = cursor.fetchone()

        if resultado:
            # Inserindo os dados no banco de dados
            comando_sql = f'''DELETE FROM funcionários WHERE ID_funcionário = {id_func}'''
            cursor.execute(comando_sql)
            conexao_banco.commit()
            messagebox.showinfo("Sucesso", "Funcionário excluido com sucesso!")
            janela_excluir.destroy()  # Fecha a janela de excluir após salvar
            
        else:
            messagebox.showwarning("Aviso", "Este ID não existe!")

    tk.Button(janela_excluir, text="Excluir", command=apagar_funcionario).pack(pady=20)

def alterar_funcionario():
    janela_alterar = tk.Toplevel(janela_menu)
    janela_alterar.title("Alterar Funcionário")
    janela_alterar.geometry("400x500")

    # Título da janela
    tk.Label(janela_alterar, text="Alterar Funcionário", font=("Arial", 14)).pack(pady=10)

    # Opção para buscar por ID ou Nome
    tk.Label(janela_alterar, text="Pesquisar por:").pack(pady=5)
    busca_var = tk.StringVar(value="ID")
    tk.Radiobutton(janela_alterar, text="ID", variable=busca_var, value="ID").pack()
    tk.Radiobutton(janela_alterar, text="Nome", variable=busca_var, value="Nome").pack()
    #Radiobutton = bolinhas de escolha

    # Entrada para ID ou Nome
    tk.Label(janela_alterar, text="Digite o ID ou Nome:").pack(pady=5)
    busca_entry = tk.Entry(janela_alterar, width=30)
    busca_entry.pack(pady=5)

    # Opção de campo a alterar (Vendas Totais ou Salário)
    tk.Label(janela_alterar, text="Escolha o campo a alterar:").pack(pady=10)
    campo_var = tk.StringVar(value="Vendas Totais")
    tk.Radiobutton(janela_alterar, text="Vendas Totais", variable=campo_var, value="Vendas Totais").pack()
    tk.Radiobutton(janela_alterar, text="Salário", variable=campo_var, value="Salário").pack()
    tk.Radiobutton(janela_alterar, text="Cargo", variable=campo_var, value="Cargo").pack()

    # Entrada para o novo valor
    tk.Label(janela_alterar, text="Novo valor/cargo:").pack(pady=5)
    novo_valcarg_entry = tk.Entry(janela_alterar, width=30)
    novo_valcarg_entry.pack(pady=5)

    # Função para buscar e alterar o funcionário
    def buscar_e_alterar():
        tipo_busca = busca_var.get()
        busca_valor = busca_entry.get()
        campo = campo_var.get()
        novo_valor = novo_valcarg_entry.get()

        # Condicional para busca por ID ou Nome
        if tipo_busca == "ID":
            cursor.execute(f'SELECT * FROM funcionários WHERE ID_funcionário = "{busca_valor}"')
        else:
            cursor.execute(f'SELECT * FROM funcionários WHERE Nome_funcionário = "{busca_valor}"')

        funcionario = cursor.fetchone()

        # Verificação se o funcionário existe
        if funcionario:
            if campo == "Vendas Totais":
                campo_sql = "Vendas_totais_funcionários"
            elif campo == "Salário":
                campo_sql = "Salário_funcionário"
            else:
                campo_sql = "Cargo_funcionário"

            # Atualiza o campo escolhido
            comando_sql = f'UPDATE funcionários SET {campo_sql} = "{novo_valor}" WHERE ID_funcionário = "{funcionario[0]}"'
            cursor.execute(comando_sql)
            conexao_banco.commit()
            messagebox.showinfo("Sucesso", f"{campo} atualizado com sucesso!")
            janela_alterar.destroy()
        else:
            messagebox.showwarning("Aviso", "Funcionário não encontrado!")

    # Botão para realizar a alteração
    tk.Button(janela_alterar, text="Alterar", command=buscar_e_alterar).pack(pady=20)

def pesquisar_funcionario():
    janela_pesquisa = tk.Toplevel(janela_menu)
    janela_pesquisa.title("Pesquisar Funcionário")
    janela_pesquisa.geometry("600x700")

    tk.Label(janela_pesquisa, text="Pesquisar Funcionário", font=("Arial", 14)).pack(pady=10)

    # Opção de pesquisa
    tk.Label(janela_pesquisa, text="Pesquisar por:").pack(pady=5)
    criterio_var = tk.StringVar(value="ID")
    tk.Radiobutton(janela_pesquisa, text="ID", variable=criterio_var, value="ID").pack()
    tk.Radiobutton(janela_pesquisa, text="Nome", variable=criterio_var, value="Nome").pack()
    tk.Radiobutton(janela_pesquisa, text="Cargo", variable=criterio_var, value="Cargo").pack()
    tk.Radiobutton(janela_pesquisa, text="Faixa de Salário", variable=criterio_var, value="Faixa de Salário").pack()
    tk.Radiobutton(janela_pesquisa, text="Quantidade de Vendas", variable=criterio_var, value="Quantidade de Vendas").pack()

    # Entrada para o valor de pesquisa
    tk.Label(janela_pesquisa, text="Digite o valor de pesquisa:").pack(pady=5)
    valor_entry = tk.Entry(janela_pesquisa, width=30)
    valor_entry.pack(pady=5)

    # Listbox para mostrar os resultados
    lista_resultados = tk.Listbox(janela_pesquisa, width=90, height=15)
    lista_resultados.pack(pady=10)

    # Função para buscar e exibir resultados
    def buscar():
        criterio = criterio_var.get()
        valor = valor_entry.get()
        lista_resultados.delete(0, tk.END)  # Limpa resultados anteriores

        if criterio == "ID":
            comando_sql = f'SELECT * FROM funcionários WHERE ID_funcionário = "{valor}"'
        elif criterio == "Nome":
            comando_sql = f'SELECT * FROM funcionários WHERE Nome_funcionário LIKE "%{valor}%"'
        elif criterio == "Cargo":
            comando_sql = f'SELECT * FROM funcionários WHERE Cargo_funcionário LIKE "%{valor}%"'
        elif criterio == "Faixa de Salário":
            try:
                salario_min, salario_max = map(float, valor.split("-"))
                comando_sql = f'SELECT * FROM funcionários WHERE Salário_funcionário BETWEEN {salario_min} AND {salario_max}'
            except ValueError:
                messagebox.showwarning("Formato Incorreto", "Por favor, insira uma faixa de salário no formato: min-max")
                return
        elif criterio == "Quantidade de Vendas":
            try:
                vendas_min, vendas_max = map(int, valor.split("-"))
                comando_sql = f'SELECT * FROM funcionários WHERE Vendas_totais_funcionários BETWEEN {vendas_min} AND {vendas_max}'
            except ValueError:
                messagebox.showwarning("Formato Incorreto", "Por favor, insira uma faixa de vendas no formato: min-max")
                return

        # Executa a pesquisa no banco de dados
        cursor.execute(comando_sql)
        resultados = cursor.fetchall()

        # Exibe os resultados na lista
        if resultados:
            for funcionario in resultados:
                lista_resultados.insert(tk.END, f"ID: {funcionario[0]}, Nome: {funcionario[1]}, Cargo: {funcionario[2]}, Vendas: {funcionario[3]}, Salário: {funcionario[4]}")
        else:
            lista_resultados.insert(tk.END, "Nenhum resultado encontrado.")


    # Botão para realizar a busca
    tk.Button(janela_pesquisa, text="Buscar", command=buscar).pack(pady=10)

def janela_vendas():
    janela = tk.Toplevel(janela_menu)
    janela.title("Vendas")
    janela.geometry("300x300")
    
    # Título da janela
    tk.Label(janela, text="Gestão de Vendas", font=("Arial", 14)).pack(pady=10)

    # Botão para Cadastrar
    tk.Button(janela, text="Cadastrar Venda", width=20, command=cadastrar_venda).pack(pady=5)
    # Botão para Excluir
    tk.Button(janela, text="Excluir Venda", width=20, command=excluir_venda).pack(pady=5)
    # Botão para Alterar
    tk.Button(janela, text="Alterar Venda", width=20, command=alterar_venda).pack(pady=5)
    # Botão para Pesquisar
    tk.Button(janela, text="Pesquisar Venda", width=20, command=pesquisar_venda).pack(pady=5)

def cadastrar_venda():
    janela_cadastro = tk.Toplevel()
    janela_cadastro.title("Cadastrar Venda")
    janela_cadastro.geometry("400x400")

    # Configuração do título
    tk.Label(janela_cadastro, text="Cadastrar Venda", font=("Arial", 14)).pack(pady=10)
    
    # Campo para ID da Venda
    tk.Label(janela_cadastro, text="ID da Venda:").pack()
    ID_vendas_entry = tk.Entry(janela_cadastro, width=30)
    ID_vendas_entry.pack(pady=5)

    # Campo para Código do Produto
    tk.Label(janela_cadastro, text="Código do Produto:").pack()
    codproduto_entry = tk.Entry(janela_cadastro, width=30)
    codproduto_entry.pack(pady=5)

    # Campo para Código do Funcionário
    tk.Label(janela_cadastro, text="Código do Funcionário:").pack()
    codfuncionario_entry = tk.Entry(janela_cadastro, width=30)
    codfuncionario_entry.pack(pady=5)

    # Campo para Quantidade do Produto
    tk.Label(janela_cadastro, text="Quantidade do Produto:").pack()
    quantidade_produto_entry = tk.Entry(janela_cadastro, width=30)
    quantidade_produto_entry.pack(pady=5)

    # Campo para Valor Unitário
    tk.Label(janela_cadastro, text="Valor Unitário:").pack()
    valor_unitario_entry = tk.Entry(janela_cadastro, width=30)
    valor_unitario_entry.pack(pady=5)

    # Campo para Data da Venda
    tk.Label(janela_cadastro, text="Data da Venda (YYYY-MM-DD):").pack()
    data_venda_entry = tk.Entry(janela_cadastro, width=30)
    data_venda_entry.pack(pady=5)

    # Função para salvar a venda no banco de dados
    def salvar_venda():
        id_venda = ID_vendas_entry.get()
        codproduto = codproduto_entry.get()
        codfuncionario = codfuncionario_entry.get()
        quantidade_produto = quantidade_produto_entry.get()
        valor_unitario = valor_unitario_entry.get()
        data_venda = data_venda_entry.get()

        # Verifica se o ID da venda já existe
        cursor.execute(f'SELECT * FROM vendas WHERE ID_vendas = "{id_venda}"')
        resultado_venda = cursor.fetchone()

        # Verifica se o código do produto existe
        cursor.execute(f'SELECT * FROM estoque WHERE ID_produto = "{codproduto}"')
        resultado_produto = cursor.fetchone()

        # Verifica se o código do funcionário existe
        cursor.execute(f'SELECT * FROM funcionários WHERE ID_funcionário = "{codfuncionario}"')
        resultado_funcionario = cursor.fetchone()

        quantidade_produto = int(quantidade_produto)
        valor_unitario = float(valor_unitario)
        valor_total = valor_unitario * quantidade_produto

        if resultado_venda:
            messagebox.showwarning("Aviso", "Este ID da venda já está cadastrado!")
        elif not resultado_produto:
            messagebox.showwarning("Aviso", "Código do produto não encontrado!")
        elif not resultado_funcionario:
            messagebox.showwarning("Aviso", "Código do funcionário não encontrado!")
        else:
            # Inserindo os dados na tabela de vendas
            comando_sql = f'''INSERT INTO vendas 
                            (ID_vendas, codproduto, codfuncionario, Quantidade_produto, Valor_unitário, Valor_total, Data_da_venda) 
                            VALUES ("{id_venda}", "{codproduto}", "{codfuncionario}", "{quantidade_produto}", "{valor_unitario}", "{valor_total}", "{data_venda}")'''
            cursor.execute(comando_sql)

            # Atualizando a coluna Vendas_totais_funcionários na tabela funcionários
            cursor.execute(f'''
                UPDATE funcionários 
                SET Vendas_totais_funcionários = Vendas_totais_funcionários + 1 
                WHERE ID_funcionário = "{codfuncionario}"
            ''')
            cursor.execute(f'''
                UPDATE estoque
                SET Quantidade_estoque = Quantidade_estoque - {quantidade_produto}
                WHERE ID_produto = "{codproduto}"
            ''')

            conexao_banco.commit()
            messagebox.showinfo("Sucesso", "Venda cadastrada com sucesso!")
            janela_cadastro.destroy()  # Fecha a janela de cadastro após salvar

    tk.Button(janela_cadastro, text="Salvar", command=salvar_venda).pack(pady=20)

def excluir_venda():
    janela_excluir_vendas = tk.Toplevel(janela_menu)
    janela_excluir_vendas.title("Excluir Venda")
    janela_excluir_vendas.geometry("400x400")

    # Configuração do título
    tk.Label(janela_excluir_vendas, text="Excluir Venda", font=("Arial", 14)).pack(pady=10)

    # Campo para o ID da venda
    tk.Label(janela_excluir_vendas, text="ID da Venda:").pack()
    ID_venda_entry = tk.Entry(janela_excluir_vendas, width=30)
    ID_venda_entry.pack(pady=5)

    # Função para apagar a venda no banco de dados
    def apagar_venda():
        id_venda = ID_venda_entry.get()

        # Verifica se o ID da venda já existe
        cursor.execute(f'SELECT * FROM vendas WHERE ID_vendaS = "{id_venda}"')
        resultado = cursor.fetchone()

        if resultado:
            # Comando SQL para deletar a venda
            comando_sql = f'''DELETE FROM vendas WHERE ID_vendaS = {id_venda}'''
            cursor.execute(comando_sql)
            conexao_banco.commit()
            messagebox.showinfo("Sucesso", "Venda excluída com sucesso!")
            janela_excluir_vendas.destroy()  # Fecha a janela de excluir após salvar
            
        else:
            messagebox.showwarning("Aviso", "Este ID não existe!")

    # Botão para excluir a venda
    tk.Button(janela_excluir_vendas, text="Excluir", command=apagar_venda).pack(pady=20)

def alterar_venda():
    janela_alterar_venda = tk.Toplevel(janela_menu)
    janela_alterar_venda.title("Alterar Venda")
    janela_alterar_venda.geometry("400x600")

    # Título da janela
    tk.Label(janela_alterar_venda, text="Alterar Venda", font=("Arial", 14)).pack(pady=10)

    # Opção para buscar por ID
    tk.Label(janela_alterar_venda, text="Pesquisar por ID da Venda:").pack(pady=5)
    busca_entry_venda = tk.Entry(janela_alterar_venda, width=30)
    busca_entry_venda.pack(pady=5)

    # Opção de campo a alterar
    tk.Label(janela_alterar_venda, text="Escolha o campo a alterar:").pack(pady=10)
    campo_var_venda = tk.StringVar(value="Quantidade_produto")
    tk.Radiobutton(janela_alterar_venda, text="Quantidade", variable=campo_var_venda, value="Quantidade_produto").pack()
    tk.Radiobutton(janela_alterar_venda, text="Valor Unitário", variable=campo_var_venda, value="Valor_unitário").pack()
    tk.Radiobutton(janela_alterar_venda, text="ID Venda", variable=campo_var_venda, value="ID_vendas").pack()
    tk.Radiobutton(janela_alterar_venda, text="Código Produto", variable=campo_var_venda, value="codproduto").pack()
    tk.Radiobutton(janela_alterar_venda, text="Código Funcionário", variable=campo_var_venda, value="codfuncionario").pack()

    # Entrada para o novo valor
    tk.Label(janela_alterar_venda, text="Novo valor:").pack(pady=5)
    novo_valor_entry = tk.Entry(janela_alterar_venda, width=30)
    novo_valor_entry.pack(pady=5)

    # Função para buscar e alterar a venda
    def buscar_e_alterar_venda():
        busca_valor_venda = busca_entry_venda.get()
        campo_venda = campo_var_venda.get()
        novo_valor_venda = novo_valor_entry.get()

        # Busca pela venda
        cursor.execute(f'SELECT * FROM vendas WHERE ID_vendas = "{busca_valor_venda}"')
        venda = cursor.fetchone()

        # Verificação se a venda existe
        if venda:
            # Armazena o codfuncionario antigo
            codfuncionario_antigo = venda[2]  # Supondo que o codfuncionario está na posição 5

            # Atualiza o campo correspondente
            if campo_venda == "Quantidade_produto":
                nova_quantidade = float(novo_valor_venda)  # Converte para float
                valor_unitario = float(venda[4])  # Converte o valor unitário para float
                novo_valor_total = nova_quantidade * valor_unitario
                
                # Atualiza no banco de dados
                comando_sql_venda = f'UPDATE vendas SET Quantidade_produto = "{nova_quantidade}", Valor_total = "{novo_valor_total}" WHERE ID_vendas = "{venda[0]}"'
            
            elif campo_venda == "Valor_unitário":
                novo_valor_unitario = float(novo_valor_venda)  # Converte para float
                quantidade = float(venda[3])  # Converte a quantidade para float
                novo_valor_total = quantidade * novo_valor_unitario
                
                # Atualiza no banco de dados
                comando_sql_venda = f'UPDATE vendas SET Valor_unitário = "{novo_valor_unitario}", Valor_total = "{novo_valor_total}" WHERE ID_vendas = "{venda[0]}"'

            elif campo_venda == "ID_vendas":
                novo_id_venda = novo_valor_venda
                
                # Verifica se o novo ID já existe
                cursor.execute(f'SELECT * FROM vendas WHERE ID_vendas = "{novo_id_venda}"')
                if cursor.fetchone():
                    messagebox.showwarning("Aviso", "Este ID da venda já está cadastrado!")
                    return
                
                # Atualiza no banco de dados
                comando_sql_venda = f'UPDATE vendas SET ID_vendas = "{novo_id_venda}" WHERE ID_vendas = "{venda[0]}"'
            
            elif campo_venda == "codproduto":
                novo_cod_produto = novo_valor_venda
                
                # Verifica se o código do produto existe na tabela estoque
                cursor.execute(f'SELECT * FROM estoque WHERE ID_produto = "{novo_cod_produto}"')
                if not cursor.fetchone():
                    messagebox.showwarning("Aviso", "Código do produto não encontrado na tabela estoque!")
                    return
                
                # Atualiza no banco de dados
                comando_sql_venda = f'UPDATE vendas SET codproduto = "{novo_cod_produto}" WHERE ID_vendas = "{venda[0]}"'
            
            elif campo_venda == "codfuncionario":
                novo_cod_funcionario = novo_valor_venda
                
                # Verifica se o novo codfuncionario existe na tabela funcionários
                cursor.execute(f'SELECT * FROM funcionários WHERE ID_funcionário = "{novo_cod_funcionario}"')
                if not cursor.fetchone():
                    messagebox.showwarning("Aviso", "Código do funcionário não encontrado na tabela funcionários!")
                    return
                
                # Atualiza o total de vendas do funcionário antigo
                cursor.execute(f'UPDATE funcionários SET Vendas_totais_funcionários = Vendas_totais_funcionários - 1 where ID_funcionário = "{codfuncionario_antigo}"')
                
                # Atualiza o total de vendas do novo funcionário
                cursor.execute(f'UPDATE funcionários SET Vendas_totais_funcionários = Vendas_totais_funcionários + 1 WHERE ID_funcionário = "{novo_cod_funcionario}"')
                
                # Atualiza no banco de dados
                comando_sql_venda = f'UPDATE vendas SET codfuncionario = "{novo_cod_funcionario}" WHERE ID_vendas = "{venda[0]}"'
            
            # Executa o comando de atualização
            cursor.execute(comando_sql_venda)
            conexao_banco.commit()
            messagebox.showinfo("Sucesso", f"{campo_venda} atualizado com sucesso!")
            janela_alterar_venda.destroy()
        else:
            messagebox.showwarning("Aviso", "Venda não encontrada!")

    # Botão para realizar a alteração
    tk.Button(janela_alterar_venda, text="Alterar", command=buscar_e_alterar_venda).pack(pady=20)
    
def pesquisar_venda():
    janela_pesquisa = tk.Toplevel(janela_menu)
    janela_pesquisa.title("Pesquisar Vendas")
    janela_pesquisa.geometry("800x700")

    tk.Label(janela_pesquisa, text="Pesquisar Vendas", font=("Arial", 14)).pack(pady=10)

    # Opção de pesquisa
    tk.Label(janela_pesquisa, text="Pesquisar por:").pack(pady=5)
    criterio_var = tk.StringVar(value="ID")
    tk.Radiobutton(janela_pesquisa, text="ID Venda", variable=criterio_var, value="ID").pack()
    tk.Radiobutton(janela_pesquisa, text="ID Funcionário", variable=criterio_var, value="ID Funcionário").pack()
    tk.Radiobutton(janela_pesquisa, text="Data", variable=criterio_var, value="Data").pack()
    tk.Radiobutton(janela_pesquisa, text="Valor Total", variable=criterio_var, value="Valor Total").pack()
    tk.Radiobutton(janela_pesquisa, text="Quantidade", variable=criterio_var, value="Quantidade").pack()

    # Entrada para o valor de pesquisa
    tk.Label(janela_pesquisa, text="Digite o valor de pesquisa:").pack(pady=5)
    valor_entry = tk.Entry(janela_pesquisa, width=30)
    valor_entry.pack(pady=5)

    # Listbox para mostrar os resultados
    lista_resultados = tk.Listbox(janela_pesquisa, width=110, height=15)
    lista_resultados.pack(pady=10)

    # Função para buscar e exibir resultados
    def buscar():
        criterio = criterio_var.get()
        valor = valor_entry.get()
        lista_resultados.delete(0, tk.END)  # Limpa resultados anteriores

        if criterio == "ID":
            comando_sql = f'SELECT * FROM vendas WHERE ID_vendas = "{valor}"'
        elif criterio == "ID Funcionário":
            comando_sql = f'SELECT * FROM vendas WHERE codfuncionario = "{valor}"'
        elif criterio == "Data":
            comando_sql = f'SELECT * FROM vendas WHERE Data_da_venda LIKE "%{valor}%"'
        elif criterio == "Valor Total":
            try:
                valor_min, valor_max = map(float, valor.split("-"))
                comando_sql = f'SELECT * FROM vendas WHERE Valor_total BETWEEN {valor_min} AND {valor_max}'
            except ValueError:
                messagebox.showwarning("Formato Incorreto", "Por favor, insira uma faixa de valor no formato: min-max")
                return
        elif criterio == "Quantidade":
            try:
                quantidade_min, quantidade_max = map(int, valor.split("-"))
                comando_sql = f'SELECT * FROM vendas WHERE Quantidade_produto BETWEEN {quantidade_min} AND {quantidade_max}'
            except ValueError:
                messagebox.showwarning("Formato Incorreto", "Por favor, insira uma faixa de quantidade no formato: min-max")
                return

        # Executa a pesquisa no banco de dados
        cursor.execute(comando_sql)
        resultados = cursor.fetchall()

        # Exibe os resultados na lista
        if resultados:
            for venda in resultados:
                lista_resultados.insert(tk.END, f"ID Venda: {venda[0]}, Cod Produto: {venda[1]}, ID Funcionário: {venda[2]}, Data: {venda[6]}, Valor Total: {venda[5]}, Valor Unitario: {venda[4]}, Quantidade: {venda[3]}, ")
        else:
            lista_resultados.insert(tk.END, "Nenhum resultado encontrado.")

    # Botão para realizar a busca
    tk.Button(janela_pesquisa, text="Buscar", command=buscar).pack(pady=10)

def janela_estoque():
    janela = tk.Toplevel(janela_menu)
    janela.title("Estoque")
    janela.geometry("300x300")
    
    tk.Label(janela, text="Gestão de Estoque", font=("Arial", 14)).pack(pady=10)

    tk.Button(janela, text="Cadastrar Estoque", width=20, command=abrir_cadastro_estoque).pack(pady=5)
    tk.Button(janela, text="Excluir Estoque", width=20, command=excluir_estoque).pack(pady=5)
    tk.Button(janela, text="Alterar Estoque", width=20, command=alterar_estoque).pack(pady=5)
    tk.Button(janela, text="Pesquisar Estoque", width=20, command=pesquisar_estoque).pack(pady=5)

def abrir_cadastro_estoque():
    janela_cadastro = tk.Toplevel(janela_menu)
    janela_cadastro.title("Cadastrar Produto no Estoque")
    janela_cadastro.geometry("400x500")

    tk.Label(janela_cadastro, text="Cadastrar Produto", font=("Arial", 14)).pack(pady=10)
    
    # Campos de entrada
    campos = ["ID do Produto", "Nome do Produto", "Categoria", "Tarja do Remédio", "Fabricante/Marca", "Valor do Produto", "Quantidade em Estoque"]
    entradas = {}
    for campo in campos:
        tk.Label(janela_cadastro, text=f"{campo}:").pack()
        entrada = tk.Entry(janela_cadastro, width=30)
        entrada.pack(pady=5)
        entradas[campo] = entrada

    def salvar_produto():
        try:
            # Obtendo os valores dos campos
            valores = {campo: entradas[campo].get() for campo in campos}

            # Conversão de tipos para os valores numéricos
            valor_produto = float(valores["Valor do Produto"])
            quantidade_estoque = int(valores["Quantidade em Estoque"])

            # Verifica se o ID já existe
            cursor.execute(f'SELECT * FROM estoque WHERE ID_produto = "{valores["ID do Produto"]}"')
            if cursor.fetchone():
                messagebox.showwarning("Aviso", "Este ID já está cadastrado!")
            else:
                # Inserindo os dados no banco de dados
                comando_sql = f'''
                    INSERT INTO estoque (ID_produto, Nome_produto, Categoria, Tarja_remedio, Fabricante_Marca, Valor_produto, Quantidade_estoque)
                    VALUES ("{valores["ID do Produto"]}", "{valores["Nome do Produto"]}", "{valores["Categoria"]}", "{valores["Tarja do Remédio"]}",
                            "{valores["Fabricante/Marca"]}", {valor_produto}, {quantidade_estoque})
                '''
                cursor.execute(comando_sql)
                conexao_banco.commit()
                messagebox.showinfo("Sucesso", "Produto cadastrado com sucesso!")
                janela_cadastro.destroy()
        except ValueError:
            messagebox.showerror("Erro", "Verifique se os valores estão corretos:\n- Valor do Produto deve ser um número.\n- Quantidade deve ser um número inteiro.")
        except Exception as e:
            messagebox.showerror("Erro", f"Ocorreu um erro: {e}")

    tk.Button(janela_cadastro, text="Salvar", command=salvar_produto).pack(pady=20)

def excluir_estoque():
    janela_excluir = tk.Toplevel(janela_menu)
    janela_excluir.title("Excluir Produto do Estoque")
    janela_excluir.geometry("400x200")

    tk.Label(janela_excluir, text="Excluir Produto", font=("Arial", 14)).pack(pady=10)
    tk.Label(janela_excluir, text="ID do Produto:").pack()
    id_produto_entry = tk.Entry(janela_excluir, width=30)
    id_produto_entry.pack(pady=5)

    def apagar_produto():
        id_produto = id_produto_entry.get()
        cursor.execute(f'SELECT * FROM estoque WHERE ID_produto = "{id_produto}"')
        if cursor.fetchone():
            cursor.execute(f'DELETE FROM estoque WHERE ID_produto = "{id_produto}"')
            conexao_banco.commit()
            messagebox.showinfo("Sucesso", "Produto excluído com sucesso!")
            janela_excluir.destroy()
        else:
            messagebox.showwarning("Aviso", "Produto não encontrado!")

    tk.Button(janela_excluir, text="Excluir", command=apagar_produto).pack(pady=20)

def alterar_estoque():
    janela_alterar = tk.Toplevel(janela_menu)
    janela_alterar.title("Alterar Produto no Estoque")
    janela_alterar.geometry("400x400")

    tk.Label(janela_alterar, text="Alterar Produto", font=("Arial", 14)).pack(pady=10)
    tk.Label(janela_alterar, text="ID do Produto:").pack()
    id_produto_entry = tk.Entry(janela_alterar, width=30)
    id_produto_entry.pack(pady=5)

    tk.Label(janela_alterar, text="Campo a alterar:").pack()
    campo_var = tk.StringVar(value="Nome_produto")
    opcoes = ["Nome_produto", "Categoria", "Tarja_remedio", "Fabricante/Marca", "Valor_produto", "Quantidade_estoque"]
    for opcao in opcoes:
        tk.Radiobutton(janela_alterar, text=opcao, variable=campo_var, value=opcao).pack()

    tk.Label(janela_alterar, text="Novo valor:").pack()
    novo_valor_entry = tk.Entry(janela_alterar, width=30)
    novo_valor_entry.pack(pady=5)

    def atualizar_produto():
        id_produto = id_produto_entry.get()
        campo = campo_var.get()
        novo_valor = novo_valor_entry.get()

        cursor.execute(f'SELECT * FROM estoque WHERE ID_produto = "{id_produto}"')
        if cursor.fetchone():
            cursor.execute(f'UPDATE estoque SET {campo} = "{novo_valor}" WHERE ID_produto = "{id_produto}"')
            conexao_banco.commit()
            messagebox.showinfo("Sucesso", f"{campo} atualizado com sucesso!")
            janela_alterar.destroy()
        else:
            messagebox.showwarning("Aviso", "Produto não encontrado!")

    tk.Button(janela_alterar, text="Alterar", command=atualizar_produto).pack(pady=20)

def pesquisar_estoque():
    janela_pesquisa = tk.Toplevel(janela_menu)
    janela_pesquisa.title("Pesquisar Produto no Estoque")
    janela_pesquisa.geometry("800x500")

    tk.Label(janela_pesquisa, text="Pesquisar Produto", font=("Arial", 14)).pack(pady=10)
    tk.Label(janela_pesquisa, text="Buscar por (ID ou Nome):").pack()
    busca_entry = tk.Entry(janela_pesquisa, width=30)
    busca_entry.pack(pady=5)

    lista_resultados = tk.Listbox(janela_pesquisa, width=100, height=15)
    lista_resultados.pack(pady=10)

    def buscar_produto():
        busca = busca_entry.get()
        cursor.execute(f'SELECT * FROM estoque WHERE ID_produto = "{busca}" OR Nome_produto LIKE "%{busca}%"')
        resultados = cursor.fetchall()
        lista_resultados.delete(0, tk.END)

        if resultados:
            for produto in resultados:
                lista_resultados.insert(tk.END, f"ID: {produto[0]}, Nome: {produto[1]}, Categoria: {produto[2]}, "
                                                f"Tarja: {produto[3]}, Marca: {produto[4]}, Valor: {produto[5]}, "
                                                f"Quantidade: {produto[6]}")
        else:
            lista_resultados.insert(tk.END, "Nenhum resultado encontrado.")

    tk.Button(janela_pesquisa, text="Buscar", command=buscar_produto).pack(pady=10)


    janela = tk.Toplevel(janela_menu)
    janela.title("Estoque")
    janela.geometry("300x300")
    
    tk.Label(janela, text="Gestão de Estoque", font=("Arial", 14)).pack(pady=10)

    tk.Button(janela, text="Cadastrar Estoque", width=20, command=abrir_cadastro_estoque).pack(pady=5)
    tk.Button(janela, text="Excluir Estoque", width=20, command=excluir_estoque).pack(pady=5)
    tk.Button(janela, text="Alterar Estoque", width=20, command=alterar_estoque).pack(pady=5)
    tk.Button(janela, text="Pesquisar Estoque", width=20, command=pesquisar_estoque).pack(pady=5)

def janela_financeiro():
    # Abre uma nova janela para o cálculo financeiro
    janela_financeiro = tk.Toplevel(janela_menu)
    janela_financeiro.title("Calcular Financeiro")
    janela_financeiro.geometry("400x400")

    tk.Label(janela_financeiro, text="Cálculo Financeiro", font=("Arial", 14)).pack(pady=10)

    # Campo para data inicial
    tk.Label(janela_financeiro, text="Data Inicial (YYYY-MM-DD):").pack()
    data_inicial_entry = tk.Entry(janela_financeiro, width=30)
    data_inicial_entry.pack(pady=5)

    # Campo para data final
    tk.Label(janela_financeiro, text="Data Final (YYYY-MM-DD):").pack()
    data_final_entry = tk.Entry(janela_financeiro, width=30)
    data_final_entry.pack(pady=5)

    def calcular():
        data_inicial = data_inicial_entry.get()
        data_final = data_final_entry.get()

        # Validação básica das datas
        if not data_inicial or not data_final:
            messagebox.showwarning("Aviso", "Por favor, preencha ambas as datas!")
            return

        try:
            # Calculando o valor bruto
            cursor.execute(f'''
                SELECT SUM(Valor_total) 
                FROM vendas 
                WHERE Data_da_venda BETWEEN "{data_inicial}" AND "{data_final}"
            ''')
            resultado_bruto = cursor.fetchone()
            valor_bruto = resultado_bruto[0] if resultado_bruto[0] is not None else 0.0

            # Calculando as despesas
            cursor.execute(f'''
                SELECT SUM(v.Quantidade_produto * e.Valor_produto) 
                FROM vendas v
                JOIN estoque e ON v.codproduto = e.ID_produto
                WHERE v.Data_da_venda BETWEEN "{data_inicial}" AND "{data_final}"
            ''')
            resultado_despesas = cursor.fetchone()
            despesas_totais = resultado_despesas[0] if resultado_despesas[0] is not None else 0.0

            # Calculando o valor líquido
            valor_liquido = valor_bruto - despesas_totais

            # Exibindo os resultados
            messagebox.showinfo("Resultados Financeiros", f""" 
                Valor Bruto: R$ {valor_bruto:.2f} 
                Despesas Totais: R$ {despesas_totais:.2f} 
                ------------------------------- 
                Valor Líquido: R$ {valor_liquido:.2f} 
            """)
        except Exception as e:
            messagebox.showerror("Erro", f"Ocorreu um erro ao calcular os valores: {e}")

            # Botão para calcular os valores financeiro
    tk.Button(janela_financeiro, text="Calcular", command=calcular).pack(pady=20)


    janela_pesquisa = tk.Toplevel(janela_menu)
    janela_pesquisa.title("Pesquisar Funcionário")
    janela_pesquisa.geometry("600x700")

    tk.Label(janela_pesquisa, text="Pesquisar Funcionário", font=("Arial", 14)).pack(pady=10)

    # Opção de pesquisa
    tk.Label(janela_pesquisa, text="Pesquisar por:").pack(pady=5)
    criterio_var = tk.StringVar(value="ID")
    tk.Radiobutton(janela_pesquisa, text="ID", variable=criterio_var, value="ID").pack()
    tk.Radiobutton(janela_pesquisa, text="Nome", variable=criterio_var, value="Nome").pack()
    tk.Radiobutton(janela_pesquisa, text="Cargo", variable=criterio_var, value="Cargo").pack()
    tk.Radiobutton(janela_pesquisa, text="Faixa de Salário", variable=criterio_var, value="Faixa de Salário").pack()
    tk.Radiobutton(janela_pesquisa, text="Quantidade de Vendas", variable=criterio_var, value="Quantidade de Vendas").pack()

    # Entrada para o valor de pesquisa
    tk.Label(janela_pesquisa, text="Digite o valor de pesquisa:").pack(pady=5)
    valor_entry = tk.Entry(janela_pesquisa, width=30)
    valor_entry.pack(pady=5)

    # Listbox para mostrar os resultados
    lista_resultados = tk.Listbox(janela_pesquisa, width=90, height=15)
    lista_resultados.pack(pady=10)

    # Função para buscar e exibir resultados
    def buscar():
        criterio = criterio_var.get()
        valor = valor_entry.get()
        lista_resultados.delete(0, tk.END)  # Limpa resultados anteriores

        if criterio == "ID":
            comando_sql = f'SELECT * FROM funcionários WHERE ID_funcionário = "{valor}"'
        elif criterio == "Nome":
            comando_sql = f'SELECT * FROM funcionários WHERE Nome_funcionário LIKE "%{valor}%"'
        elif criterio == "Cargo":
            comando_sql = f'SELECT * FROM funcionários WHERE Cargo_funcionário LIKE "%{valor}%"'
        elif criterio == "Faixa de Salário":
            try:
                salario_min, salario_max = map(float, valor.split("-"))
                comando_sql = f'SELECT * FROM funcionários WHERE Salário_funcionário BETWEEN {salario_min} AND {salario_max}'
            except ValueError:
                messagebox.showwarning("Formato Incorreto", "Por favor, insira uma faixa de salário no formato: min-max")
                return
        elif criterio == "Quantidade de Vendas":
            try:
                vendas_min, vendas_max = map(int, valor.split("-"))
                comando_sql = f'SELECT * FROM funcionários WHERE Vendas_totais_funcionários BETWEEN {vendas_min} AND {vendas_max}'
            except ValueError:
                messagebox.showwarning("Formato Incorreto", "Por favor, insira uma faixa de vendas no formato: min-max")
                return

        # Executa a pesquisa no banco de dados
        cursor.execute(comando_sql)
        resultados = cursor.fetchall()

        # Exibe os resultados na lista
        if resultados:
            for funcionario in resultados:
                lista_resultados.insert(tk.END, f"ID: {funcionario[0]}, Nome: {funcionario[1]}, Cargo: {funcionario[2]}, Vendas: {funcionario[3]}, Salário: {funcionario[4]}")
        else:
            lista_resultados.insert(tk.END, "Nenhum resultado encontrado.")


    # Botão para realizar a busca
    tk.Button(janela_pesquisa, text="Buscar", command=buscar).pack(pady=10)

def fechar_programa():
    confirmacao = messagebox.askyesno("Confirmação", "Deseja realmente fechar o programa?")
    if confirmacao:
        conexao_banco.close()  # Fecha a conexão com o banco de dados
        janela_menu.destroy()  # Encerra a janela principal



# Configuração da janela principal
janela_menu = tk.Tk()
janela_menu.title('Farmacia')
janela_menu.state('zoomed')  # Ativa o modo tela cheia

# Frame centralizado para os botões
frame_central = tk.Frame(janela_menu)
frame_central.place(relx=0.5, rely=0.5, anchor=tk.CENTER)

# Texto inicial
texto_inicial = tk.Label(frame_central, text='Selecione a opção desejada:', font=("Arial", 24))
texto_inicial.pack(pady=20)

# Botões centralizados e maiores
tk.Button(frame_central, text='Funcionários', font=("Arial", 18), width=20, height=2, command=janela_funcionarios).pack(pady=10)
tk.Button(frame_central, text='Vendas', font=("Arial", 18), width=20, height=2, command=janela_vendas).pack(pady=10)
tk.Button(frame_central, text='Estoque', font=("Arial", 18), width=20, height=2, command=janela_estoque).pack(pady=10)
tk.Button(frame_central, text='Financeiro', font=("Arial", 18), width=20, height=2, command=janela_financeiro).pack(pady=10)
tk.Button(frame_central, text='Sair', font=("Arial", 18), width=20, height=2, command=fechar_programa).pack(pady=10)


# Loop da janela principal
janela_menu.mainloop()