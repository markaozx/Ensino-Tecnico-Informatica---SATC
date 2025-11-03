# 🧪 Como Testar o Sistema Completo

## ✅ Lista de Verificação para TCC

### 1️⃣ Acesso à Loja (Cliente)

**URL:** `http://localhost/NextLevelTech%20-%20Projeto%20TCC/loja/home.php`

**Teste:**
- [ ] Página carrega corretamente
- [ ] Banners do carrossel funcionam
- [ ] Produtos aparecem na grade
- [ ] Filtros por categoria funcionam
- [ ] Busca por nome funciona
- [ ] Ordenação funciona (preço, nome, etc)

### 2️⃣ Login de Cliente

**Login de Teste:**
- Email: `markinhuszanin@gmail.com`
- Senha: `123456`

**Teste:**
- [ ] Login funciona
- [ ] Nome aparece no header
- [ ] Ícone de logout aparece

### 3️⃣ Carrinho de Compras

**Teste:**
- [ ] Adicionar produto ao carrinho
- [ ] Contador do carrinho atualiza
- [ ] Ver carrinho mostra produtos
- [ ] Alterar quantidade funciona
- [ ] Remover produto funciona
- [ ] Total calculado corretamente

### 4️⃣ Finalização de Compra (AbacatePay)

**Teste:**
- [ ] Botão "Finalizar Compra com PIX" aparece
- [ ] Clique redireciona para AbacatePay
- [ ] Página de pagamento abre corretamente
- [ ] QR Code PIX é gerado
- [ ] Após pagamento, redireciona para success
- [ ] Pedido aparece em "Meus Pedidos"

### 5️⃣ Meus Pedidos

**URL:** `http://localhost/NextLevelTech%20-%20Projeto%20TCC/loja/meus_pedidos.php`

**Teste:**
- [ ] Lista de pedidos aparece
- [ ] Data e horário corretos (Brasília)
- [ ] Status do pedido correto
- [ ] Valor total correto

### 6️⃣ Login de Admin (Nível 1)

**URL:** `http://localhost/NextLevelTech%20-%20Projeto%20TCC/loja/login_adm.php`

**Login:** `marcusteste@gmail.com` / `123456`

**Teste:**
- [ ] Login funciona
- [ ] Acesso ao menu principal
- [ ] Pode gerenciar produtos
- [ ] Pode gerenciar categorias
- [ ] Pode gerenciar marcas
- [ ] NÃO vê seção de Admins
- [ ] NÃO vê seção Financeiro

### 7️⃣ Login de Admin (Nível 2 - Super)

**Login:** `matheusteste@gmail.com` / `123456`

**Teste:**
- [ ] Login funciona
- [ ] Vê TODAS as seções do menu
- [ ] Vê seção "Painel Financeiro" ✨
- [ ] Vê seção "Gerenciamento de Administradores"

### 8️⃣ Painel Financeiro (NOVO!)

**URL:** `http://localhost/NextLevelTech%20-%20Projeto%20TCC/loja/financeiro.php`

**Apenas Admin Nível 2**

**Aba Dashboard:**
- [ ] Mostra faturamento total
- [ ] Mostra vendas realizadas
- [ ] Mostra ticket médio
- [ ] Mostra pedidos pendentes
- [ ] Lista produtos mais vendidos
- [ ] Filtro por período funciona

**Aba Pedidos:**
- [ ] Lista todos os pedidos
- [ ] Mostra status correto
- [ ] Mostra dados do cliente
- [ ] Horários corretos (Brasília)

**Aba Estatísticas:**
- [ ] Mostra métricas gerais
- [ ] Vendas por categoria
- [ ] Filtro por período

**Aba Transações:**
- [ ] Lista transações da AbacatePay
- [ ] Mostra Billing IDs
- [ ] Mostra status dos pagamentos

### 9️⃣ Gestão de Produtos (Admin)

**Teste:**
- [ ] Listar produtos funciona
- [ ] Cadastrar novo produto
- [ ] Alterar produto existente
- [ ] Excluir produto
- [ ] Upload de fotos funciona

### 🔟 Estoque

**Teste:**
- [ ] Após compra, estoque diminui automaticamente
- [ ] Produtos sem estoque mostram "Indisponível"
- [ ] Não permite adicionar ao carrinho se sem estoque

================================================================================
🎯 CENÁRIO COMPLETO DE TESTE
================================================================================

1. Entre como CLIENTE
2. Adicione 3 produtos diferentes ao carrinho
3. Finalize a compra com PIX
4. Complete o pagamento na AbacatePay (modo dev)
5. Verifique que o pedido aparece em "Meus Pedidos"
6. Saia do cliente
7. Entre como ADMIN NÍVEL 2
8. Acesse o Painel Financeiro
9. Verifique que:
   - Faturamento aumentou
   - Pedido aparece na lista
   - Produtos vendidos aparecem no ranking
   - Estatísticas estão corretas

================================================================================
📊 MÉTRICAS ESPERADAS (Após Teste Completo)
================================================================================

- Total de Produtos: 30
- Total de Categorias: 7
- Total de Marcas: 12
- Pedidos Realizados: 1+ (seu teste)
- Faturamento: R$ XXX,XX (soma dos seus testes)

================================================================================
✅ TUDO PRONTO PARA APRESENTAR NO TCC!
================================================================================

Se todos os itens estiverem ✅ marcados, o sistema está 100% funcional!

Boa sorte na apresentação! 🎉

