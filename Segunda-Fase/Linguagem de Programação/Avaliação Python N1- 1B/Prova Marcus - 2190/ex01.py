# Marcus Vinicius Zanin
# 2190      -       27/08
a = 0
b = 20
times = ('BotaFogo','Fortaleza','Flamengo','Palmeiras','São Paulo','Cruzeiro','Bahia','Athletico-PR',
         'Atletico-MG','Vasco','Bragantino','Juventude','Grêmio','Criciuma','Internacional','Vitoria',
         'Corinthians','Fluminense','Cuiaba','Atletico-GO')
# PRIMEIROS 5 COLOCADOS
print("TOP 5 COLOCADOS: ")
for time in times:
    print(time)
    a = a + 1
    if a == 5:
        break
print('-'*30)
# ULTIMOS 4 COLOCADOS
print('ULTIMOS 4 DA TABELA: ')
for time in times:
    b = b - 1
    if b <= 3:
        print(time)
print('-'*30)
# ORDEM ALFABETICA
print('LISTA EM ORDEM ALFABETICA: ')
ordem = sorted(times)
print(ordem)
print('-'*30)
# POSIÇÃO TIME CRICIUMA
print('POSIÇÃO DO CRICIUMA: ')
print('-'*30)